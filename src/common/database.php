<?php
// database.php — MySQL connection helpers.
//
// Credentials are loaded from an .env file at the project root (preferred)
// or environment variables (set via systemd / nginx fastcgi_param /
// apache SetEnv). The hardcoded fallback below points at the EXISTING
// doadmin credentials and exists ONLY so the site keeps working during
// the transition window between this deploy and the (overdue) DB password
// rotation. After rotation, DELETE the fallback block.

// ----- env loader (project-root /.env) --------------------------------------

if (!function_exists('pd_db_load_env')) {
    function pd_db_load_env(): void
    {
        static $loaded = false;
        if ($loaded) return;
        $loaded = true;

        // /.env at the repo root, sibling of /src
        $envPath = dirname(__DIR__, 2) . '/.env';
        if (!is_readable($envPath)) {
            return;
        }
        $fh = @fopen($envPath, 'r');
        if (!$fh) return;
        try {
            while (($line = fgets($fh)) !== false) {
                $line = trim($line);
                if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
                    continue;
                }
                [$k, $v] = explode('=', $line, 2);
                $k = trim($k);
                $v = trim($v);
                // strip surrounding quotes
                if (strlen($v) >= 2) {
                    $first = $v[0];
                    $last = $v[strlen($v) - 1];
                    if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                        $v = substr($v, 1, -1);
                    }
                }
                if ($k !== '' && getenv($k) === false) {
                    putenv("$k=$v");
                    $_ENV[$k] = $v;
                }
            }
        } finally {
            fclose($fh);
        }
    }
}
pd_db_load_env();

// ----- credential resolution ------------------------------------------------

// REMOVE the inline fallback after rotating the doadmin password (track #17).
$_pd_db_host_default = "teletype-news-db-do-user-12424917-0.c.db.ondigitalocean.com";
$_pd_db_user_default = "doadmin";
$_pd_db_pass_default = "AVNS_I2CkbNcVv-bhA-W7Ej9";  // KNOWN LEAKED - rotate ASAP
$_pd_db_name_default = "privacyduck";
$_pd_db_port_default = 25060;

if (!defined('DB_HOST'))     define('DB_HOST',     getenv('DB_HOST')     ?: $_pd_db_host_default);
if (!defined('DB_USER'))     define('DB_USER',     getenv('DB_USER')     ?: $_pd_db_user_default);
if (!defined('DB_PASSWORD')) define('DB_PASSWORD', getenv('DB_PASSWORD') ?: $_pd_db_pass_default);
if (!defined('DB_NAME'))     define('DB_NAME',     getenv('DB_NAME')     ?: $_pd_db_name_default);
if (!defined('DB_PORT'))     define('DB_PORT', (int) (getenv('DB_PORT') ?: $_pd_db_port_default));

unset($_pd_db_host_default, $_pd_db_user_default, $_pd_db_pass_default, $_pd_db_name_default, $_pd_db_port_default);


function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
    $conn->ssl_set(NULL, NULL, NULL, NULL, NULL);
    if ($conn->connect_error) {
        // Don't leak the connect_error string (may include credentials in
        // some MySQL error formats). Log server-side, surface a generic
        // message client-side.
        error_log('pd database connect failed: ' . $conn->connect_error);
        http_response_code(500);
        die("Database connection failed.");
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

function closeDBConnection($conn) {
    if ($conn) {
        @$conn->close();
    }
}
