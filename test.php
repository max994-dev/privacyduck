<?php
// SECURITY NOTE (2026-05-28): This file is publicly accessible at
// /test.php and dumps the CURRENT visitor's $_SESSION. The CLI-only
// gate that was here was deliberately removed at user request to
// restore the original debugging behavior.
//
// What this leaks to whoever visits the URL:
//   - their own session (auth state, user_id, plan_id, planable,
//     csrf token, fullName, email, etc.)
//
// What it does NOT leak (PHP sessions are per-visitor cookies):
//   - other users' sessions
//
// Still: any visitor can quickly see what the server stores about
// them. If that's no longer wanted, the simplest restoration is
// to put back the two-line check:
//
//   if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
//
// or gate behind an admin/IP check.
session_start();
print_r($_SESSION);
