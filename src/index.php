<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$rootUrl = sprintf(
    "%s://%s:%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME'],
    $_SERVER['SERVER_PORT']
);

include_once(dirname(__FILE__)."/control/api/api.php");

include_once(dirname(__FILE__)."/pages/index.php");
?>
