<?php
// Dev-only session dump. Disabled in production for security - leaked session contents.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
session_start();
print_r($_SESSION);
