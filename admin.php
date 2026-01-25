<?php
define("ADMINBASEPATH", $_SERVER["DOCUMENT_ROOT"]);
include_once(ADMINBASEPATH . "/src/common/config.php");
include_once(ADMINBASEPATH . "/src/common/database.php");
include_once(ADMINBASEPATH . "/admin/utils/index.php");
$fullPath = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$request = preg_replace('#^super/admin/?#', '', $fullPath);
$routes = [

    'login' => 'views/Login/index.php',
    'content/usermanage' => 'views/Dashboard/user/index.php',
    'content/familymanage' => 'views/Dashboard/family/index.php',
    'content/removalmanage' => 'views/Dashboard/removal/index.php',
    'content/businessmanage' => 'views/Dashboard/business/index.php',
    'content/emailingsystem' => 'views/Dashboard/emailing/index.php',


    'api/login' => 'controllers/login/index.php',
    'api/logout' => 'controllers/logout/index.php',
    'api/user/getlist' => 'controllers/user/getlist.php',
    'api/emailing/getlist' => 'controllers/emailing/getlist.php',
    'api/emailing/auto' => 'controllers/emailing/autoemailing.php',
    '.*' => 'views/Dashboard/index.php',
    // 'dashboard(?:/(\w+))?'=> 'Dashboard/index.php',
];

foreach ($routes as $pattern => $file) {
    if (preg_match("#^$pattern$#", $request, $matches)) {
        $GLOBALS['matches'] = $matches;
        require ADMINBASEPATH . "/admin/$file";
        exit;
    }
}

http_response_code(404);
echo "404 - Page not found";

?>