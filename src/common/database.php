<?php
    if (!function_exists('pd_db_env')) {
        function pd_db_env(string $name, string $default = ''): string
        {
            $value = getenv($name);
            if ($value !== false) {
                return (string) $value;
            }
            if (isset($_ENV[$name])) {
                return (string) $_ENV[$name];
            }
            if (isset($_SERVER[$name])) {
                return (string) $_SERVER[$name];
            }
            return $default;
        }
    }

    define("DB_HOST", pd_db_env("DB_HOST"));
    define("DB_USER", pd_db_env("DB_USER"));
    define("DB_PASSWORD", pd_db_env("DB_PASSWORD"));
    define("DB_NAME", pd_db_env("DB_NAME"));
    define('DB_PORT', (int) pd_db_env('DB_PORT', '3306'));
    // define("DB_HOST", "localhost");
    // define("DB_USER", "root");
    // define("DB_PASSWORD", "");
    // define("DB_NAME", "privacyduck");
    // define('DB_PORT', '3306');

    // Create connection
    function getDBConnection() {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
        $conn->ssl_set(NULL, NULL, NULL, NULL, NULL);
        if($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }

    function closeDBConnection($conn) {
        $conn->close();
    }

?>