<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$rootUrl = sprintf(
    "%s://%s:%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME'],
    $_SERVER['SERVER_PORT']
);

function getRootURL() {
    return $GLOBALS['rootUrl'];
}

function import($callingFile, $module) {
    require_once(dirname($callingFile).'/'.$module);
}

import(__FILE__, "session.php");
import(__FILE__, "api/api.php");

?>