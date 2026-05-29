<?php
isLogin();
require BASEPATH . "/src/pages/Dashboard/sites_data.php";
require_once BASEPATH . "/src/common/stripe_signup_sync.php";
// Only start if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION["user_id"])) {
    header("Location: /new_signin");
    exit;
}

// ALWAYS refresh plan_id / planable from DB on every dashboard load.
// Why: login set these into the session ONCE when the user logged in.
// If they then went to Stripe Checkout and the webhook updated
// users.plan_id afterwards, their session still has the stale empty
// values until they log out + back in. Users see the UNPAID dashboard
// even though their payment went through. This is a 1-row indexed
// lookup; the cost is negligible vs. the support cost of "I paid
// but dashboard says I didn't".
//
// Uses its own short-lived connection because the main $conn isn't
// opened until further down -- and we want this refresh to happen
// before the Stripe fallback (which depends on the result of this).
if (!empty($_SESSION["user_id"])) {
    try {
        $refreshConn = getDBConnection();
        $refreshStmt = $refreshConn->prepare("SELECT plan_id, plan_end FROM users WHERE id = ? LIMIT 1");
        $refreshStmt->bind_param("i", $_SESSION["user_id"]);
        $refreshStmt->execute();
        $fresh = $refreshStmt->get_result()->fetch_assoc();
        $refreshStmt->close();
        $refreshConn->close();
        if ($fresh !== null) {
            $hasActivePlan = !empty($fresh["plan_id"]) && !empty($fresh["plan_end"]);
            $isPlanValid  = $hasActivePlan && (new DateTime() < new DateTime($fresh["plan_end"]));
            $_SESSION["plan_id"]         = $fresh["plan_id"] ?? null;
            $_SESSION["planable"]        = $isPlanValid;
            $_SESSION["signup_complete"] = $isPlanValid ? 1 : ($_SESSION["signup_complete"] ?? 0);
        }
    } catch (Throwable $e) {
        error_log('dashboard_bootstrap session refresh: ' . $e->getMessage());
    }
}

// Fallback: if STILL not paid in session after the DB refresh AND the
// user just came back from Stripe Checkout (book-call flow sets this
// flag), pull from Stripe directly. The webhook may simply not have
// landed yet. Only runs for that specific intent because Stripe API
// calls cost a few hundred ms and we don't want to do them on every
// pageload for already-paid users.
if (
    !empty($_SESSION["pd_book_call_intent"]) &&
    (empty($_SESSION["planable"]) || empty($_SESSION["plan_id"])) &&
    !empty($_SESSION["email"])
) {
    try {
        $syncConn = getDBConnection();
        stripe_sync_privacyduck_subscription_for_email($syncConn, (string) $_SESSION["email"], true);
        $refreshStmt = $syncConn->prepare("SELECT plan_id, plan_end FROM users WHERE id = ? LIMIT 1");
        $refreshStmt->bind_param("i", $_SESSION["user_id"]);
        $refreshStmt->execute();
        $fresh = $refreshStmt->get_result()->fetch_assoc();
        $refreshStmt->close();
        $syncConn->close();
        if ($fresh) {
            $hasActivePlan = !empty($fresh["plan_id"]) && !empty($fresh["plan_end"]);
            $isPlanValid   = $hasActivePlan && (new DateTime() < new DateTime($fresh["plan_end"]));
            $_SESSION["plan_id"]         = $fresh["plan_id"] ?? null;
            $_SESSION["planable"]        = $isPlanValid;
            $_SESSION["signup_complete"] = $isPlanValid;
        }
    } catch (Throwable $e) {
        error_log('dashboard_bootstrap stripe fallback sync: ' . $e->getMessage());
    }
}

// Only force the "Add Your Info" modal after successful payment
$showSignup = !empty($_SESSION["needs_profile_info"]);
$conn = getDBConnection();
//google scan start
$main_stmt = $conn->prepare("SELECT * FROM results WHERE user_id = ? AND kind=3");
$main_stmt->bind_param("i", $_SESSION["user_id"]);
$main_stmt->execute();
$main_result = $main_stmt->get_result();
if ($main_result->num_rows == 0) {
    $values = [];
    $params = [];
    $types = "";

    // Build values and placeholders
    foreach (["googlecom", "googlecom2", "googlecom3"] as $url) {
        $values[] = "(?, ?, ?, ?)";
        $params[] = $url;
        $params[] = $_SESSION["user_id"];
        $params[] = 3;
        $params[] = 0;
        $types .= "ssii";
    }
    $sql = "INSERT INTO results (target_domain, user_id, kind, step) VALUES " . implode(", ", $values);
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
} else {
    $data = $main_result->fetch_all(MYSQLI_ASSOC);
    $count = count(array_filter($data, function ($item) {
        return $item["step"] < 2;
    }));
    if ($count == 0) {
        $sql = "UPDATE results SET step = 0 WHERE user_id = ? AND kind=3";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
    }
}
//google scan end

// if (isset($_SESSION["planable"]) && $_SESSION['planable']) {
$main_stmt = $conn->prepare("SELECT * FROM results WHERE user_id = ? AND kind=0");
$main_stmt->bind_param("i", $_SESSION["user_id"]);
$main_stmt->execute();
$main_result = $main_stmt->get_result();
if ($main_result->num_rows == 0) {
    $values = [];
    $params = [];
    $types = "";

    // Build values and placeholders
    foreach ($websites as $url => $removal_url) {
        $values[] = "(?, ?, ?, ?)";
        $params[] = $url;
        $params[] = $_SESSION["user_id"];
        $params[] = 0;
        $params[] = 0;
        $types .= "ssii";
    }
    $sql = "INSERT INTO results (target_domain, user_id, kind, step) VALUES " . implode(", ", $values);
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
} else {
    $data = $main_result->fetch_all(MYSQLI_ASSOC);
    $count = count(array_filter($data, function ($item) {
        return $item["step"] < 2;
    }));
    if ($count == 0) {
        $sql = "UPDATE results SET step = 0 WHERE user_id = ? AND kind=0";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
    }
}
// }
if (isset($_SESSION["planable"]) && $_SESSION['planable']) {
    function plan($userId, $websites, $websitesUrl, $data)
    {
        $conn = getDBConnection();
        $main_stmt = $conn->prepare("SELECT planable FROM results WHERE user_id = ? AND planable=0");
        $main_stmt->bind_param("i", $userId);
        $main_stmt->execute();
        $main_result = $main_stmt->get_result();
        if ($main_result->num_rows > 0) {
            $sql = "UPDATE users SET planedAt = NOW() WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();

            $main_stmt = $conn->prepare("UPDATE results SET planable = 1 WHERE user_id = ? AND planable=0");
            $main_stmt->bind_param("i", $userId);
            $main_stmt->execute();
        }

        $main_stmt = $conn->prepare("SELECT * FROM results WHERE user_id = ? AND kind=1");
        $main_stmt->bind_param("i", $userId);
        $main_stmt->execute();
        $main_result = $main_stmt->get_result();
        if ($main_result->num_rows == 0) {
            $values = [];
            $params = [];
            $types = "";

            // Build values and placeholders
            foreach ($websites as $url => $removal_url) {
                $values[] = "(?, ?, ?, ?, ?, ?, ?, ?)";
                $params[] = $url;
                $params[] = $userId;
                $params[] = 1;
                $params[] = 0;
                $params[] = 1;
                $params[] = $websitesUrl[$url] ?? '';
                $params[] = $removal_url;
                $params[] = $data;
                $types .= "ssiiisss";
            }
            $sql = "INSERT INTO results (target_domain, user_id, kind, step, planable, site_url, removal_url, data) VALUES " . implode(", ", $values);
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                error_log('dashboard_bootstrap plan() prepare failed user_id=' . $userId . ' err=' . $conn->error);
            } else {
                $stmt->bind_param($types, ...$params);
                if (!$stmt->execute()) {
                    error_log('dashboard_bootstrap plan() kind=1 bulk insert failed user_id=' . $userId . ' err=' . $stmt->error);
                }
                $stmt->close();
            }
            $sql = "UPDATE users SET planedAt = NOW() WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
        } else {
            $rows = $main_result->fetch_all(MYSQLI_ASSOC);
            $existingTargets = [];
            foreach ($rows as $r) {
                if (isset($r["target_domain"])) $existingTargets[$r["target_domain"]] = true;
            }

            // Backfill new sites added to sites_data.php (keep existing progress intact)
            $missing = [];
            foreach ($websites as $url => $removal_url) {
                if (!isset($existingTargets[$url])) {
                    $missing[$url] = $removal_url;
                }
            }

            if (!empty($missing)) {
                $values = [];
                $params = [];
                $types = "";
                foreach ($missing as $url => $removal_url) {
                    $values[] = "(?, ?, ?, ?, ?, ?, ?, ?)";
                    $params[] = $url;
                    $params[] = $userId;
                    $params[] = 1;
                    $params[] = 0;
                    $params[] = 1;
                    $params[] = $websitesUrl[$url] ?? "";
                    $params[] = $removal_url;
                    $params[] = $data;
                    $types .= "ssiiisss";
                }
                $sql = "INSERT INTO results (target_domain, user_id, kind, step, planable, site_url, removal_url, data) VALUES " . implode(", ", $values);
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    error_log('dashboard_bootstrap plan() kind=1 backfill prepare failed user_id=' . $userId . ' err=' . $conn->error);
                } else {
                    $stmt->bind_param($types, ...$params);
                    if (!$stmt->execute()) {
                        error_log('dashboard_bootstrap plan() kind=1 backfill insert failed user_id=' . $userId . ' err=' . $stmt->error);
                    }
                    $stmt->close();
                }
            }

            $count = count(array_filter($rows, function ($item) {
                return $item["step"] < 2;
            }));
            $sql = "Select planedAt from users WHERE id = ? AND (planedAt <= NOW() - INTERVAL 90 DAY OR planedAt IS NULL)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            if ($count == 0 && $stmt->get_result()->num_rows > 0) {
                $sql = "UPDATE users SET planedAt = NOW() WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $sql = "UPDATE results SET data = ?, step = 0, planable = 1 WHERE user_id = ? AND kind=1";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $data, $userId);
                $stmt->execute();
            }
        }
    }
    function removalPlan($userId, $websites, $websitesUrl)
    {
        $conn = getDBConnection();
        $main_stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $main_stmt->bind_param("i", $userId);
        $main_stmt->execute();
        $main_result = $main_stmt->get_result();
        $userData = $main_result->fetch_assoc();
        //2026
	if (!$userData) {
	     session_unset();
	     session_destroy();
	     header("Location: /new_signin");
 	     exit;
	}
        $contacts = $userData["contacts"] ?? "[]";
	$contacts = json_decode($contacts, true) ?: [];

        // birth_date (DATE column added 2026-05) is the canonical DOB.
        // Decompose to day/month/year strings for the broker JSON; the
        // Python pipeline's REQUIRED_FIELDS check needs all three present
        // and non-empty before it'll dispatch to a broker. Empty strings
        // when null so the pipeline correctly marks the row missing_pii
        // instead of dispatching with garbage like "0/0/0".
        $birthDay = '';
        $birthMonth = '';
        $birthYear = '';
        if (!empty($userData["birth_date"]) && $userData["birth_date"] !== '0000-00-00') {
            try {
                $dt = new DateTime($userData["birth_date"]);
                $birthDay = $dt->format('d');
                $birthMonth = $dt->format('m');
                $birthYear = $dt->format('Y');
            } catch (Exception $e) {
                // malformed DATE — leave empty, downstream will skip the row
            }
        }

        $buildPayload = function (array $userData, array $contact = []) use ($birthDay, $birthMonth, $birthYear) {
            return [
                "email"       => $userData["email"]     ?? '',
                "firstname"   => $userData["firstname"] ?? '',
                "lastname"    => $userData["lastname"]  ?? '',
                "age"         => $userData["age"]       ?? '',
                "birth_day"   => $birthDay,
                "birth_month" => $birthMonth,
                "birth_year"  => $birthYear,
                "city"        => $contact["city"]    ?? ($userData["city"]    ?? ''),
                "zip"         => $contact["zip"]     ?? ($userData["zip"]     ?? ''),
                "state"       => $contact["state"]   ?? ($userData["state"]   ?? ''),
                "phone"       => $contact["phone"]   ?? ($userData["phone"]   ?? ''),
                "address"     => $contact["address"] ?? ($userData["address"] ?? ''),
            ];
        };

        if (count($contacts) > 0) {
            foreach ($contacts as $contact) {
                plan($userId, $websites, $websitesUrl, json_encode($buildPayload($userData, $contact)));
            }
        } else {
            plan($userId, $websites, $websitesUrl, json_encode($buildPayload($userData)));
        }
    }
    removalPlan($_SESSION["user_id"], $websites, $websitesUrl);

    // Reset TRANSIENT failure states back to step=0 so the pipeline
    // tries them again. Policy (May 2026):
    //   step=3 (broker_raised: scraper exception)  -> retry
    //   step=5 (missing_pii: broker wants a field) -> retry
    //
    // step=4 (module_missing) is INTENTIONALLY not reset (no
    // scraper exists; retrying churns the pipeline for no benefit).
    // step=1 (in_flight) NOT reset (actively claimed).
    // step=2 (done) is terminal.
    //
    // CACHED per-session: this UPDATE used to run on EVERY dashboard
    // load. For users with many failed rows, that's a slow write
    // on a 875K-row table. New behavior: run at most once every
    // 5 minutes per session. The pipeline's own loop will pick up
    // newly-reset rows within seconds anyway.
    $lastResetAt = (int) ($_SESSION['pd_last_step_reset_at'] ?? 0);
    if (time() - $lastResetAt > 300) {
        $resetStmt = $conn->prepare(
            "UPDATE results SET step = 0 WHERE user_id = ? AND kind = 1 AND step IN (3, 5)"
        );
        $resetStmt->bind_param("i", $_SESSION["user_id"]);
        $resetStmt->execute();
        if ($resetStmt->affected_rows > 0) {
            error_log("dashboard_bootstrap: user_id=" . (int) $_SESSION["user_id"] .
                      " reset " . $resetStmt->affected_rows .
                      " step={3,5} rows to step=0 (try-again policy)");
        }
        $resetStmt->close();
        $_SESSION['pd_last_step_reset_at'] = time();
    }

    $main_stmt = $conn->prepare("SELECT * FROM family WHERE core_id = ? AND status = 0");
    $main_stmt->bind_param("i", $_SESSION["user_id"]);
    $main_stmt->execute();
    $main_result = $main_stmt->get_result();
    if ($main_result->num_rows > 0) {
        $data = $main_result->fetch_all(MYSQLI_ASSOC);
        for ($i = 0; $i < count($data); $i++) {
            removalPlan($data[$i]["invite_id"], $websites, $websitesUrl);
        }
    }
} else {
    $main_stmt = $conn->prepare("SELECT * FROM results WHERE user_id = ? AND kind=1");
    $main_stmt->bind_param("i", $_SESSION["user_id"]);
    $main_stmt->execute();
    $main_result = $main_stmt->get_result();
    if ($main_result->num_rows == 0) {
        $values = [];
        $params = [];
        $types = "";

        // Build values and placeholders
        foreach ($websites as $url => $removal_url) {
            $values[] = "(?, ?, ?, ?, ?, ?, ?)";
            $params[] = $url;
            $params[] = $_SESSION["user_id"];
            $params[] = 1;
            $params[] = 0;
            $params[] = 0;
            $params[] = $websitesUrl[$url] ?? '';
            $params[] = $removal_url;
            $types .= "ssiiiss";
        }
        $sql = "INSERT INTO results (target_domain, user_id, kind, step, planable, site_url, removal_url) VALUES " . implode(", ", $values);
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log('dashboard_bootstrap (no plan) kind=1 prepare failed user_id=' . (int) $_SESSION['user_id'] . ' err=' . $conn->error);
        } else {
            $stmt->bind_param($types, ...$params);
            if (!$stmt->execute()) {
                error_log('dashboard_bootstrap (no plan) kind=1 bulk insert failed user_id=' . (int) $_SESSION['user_id'] . ' err=' . $stmt->error);
            }
            $stmt->close();
        }
    } else {
        // Backfill new sites for locked (not planable) users too
        $rows = $main_result->fetch_all(MYSQLI_ASSOC);
        $existingTargets = [];
        foreach ($rows as $r) {
            if (isset($r["target_domain"])) $existingTargets[$r["target_domain"]] = true;
        }
        $missing = [];
        foreach ($websites as $url => $removal_url) {
            if (!isset($existingTargets[$url])) $missing[$url] = $removal_url;
        }
        if (!empty($missing)) {
            $values = [];
            $params = [];
            $types = "";
            foreach ($missing as $url => $removal_url) {
                $values[] = "(?, ?, ?, ?, ?, ?, ?)";
                $params[] = $url;
                $params[] = $_SESSION["user_id"];
                $params[] = 1;
                $params[] = 0;
                $params[] = 0;
                $params[] = $websitesUrl[$url] ?? "";
                $params[] = $removal_url;
                $types .= "ssiiiss";
            }
            $sql = "INSERT INTO results (target_domain, user_id, kind, step, planable, site_url, removal_url) VALUES " . implode(", ", $values);
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                error_log('dashboard_bootstrap (no plan) kind=1 backfill prepare failed user_id=' . (int) $_SESSION['user_id'] . ' err=' . $conn->error);
            } else {
                $stmt->bind_param($types, ...$params);
                if (!$stmt->execute()) {
                    error_log('dashboard_bootstrap (no plan) kind=1 backfill insert failed user_id=' . (int) $_SESSION['user_id'] . ' err=' . $stmt->error);
                }
                $stmt->close();
            }
        }
    }
}
