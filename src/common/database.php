<?php
    define("DB_HOST", "teletype-news-db-do-user-12424917-0.c.db.ondigitalocean.com");
    define("DB_USER", "doadmin");
    define("DB_PASSWORD", "AVNS_I2CkbNcVv-bhA-W7Ej9");
    define("DB_NAME", "privacyduck");
    define('DB_PORT', 25060); // Important: number, not string
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