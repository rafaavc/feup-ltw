<?php
namespace Session;
use API;

$urlComponents = parse_url(getRootUrl());
session_set_cookie_params(0, isset($urlComponents['path']) ? $urlComponents['path'] : '/', $urlComponents['host'], true, true);
session_start();

if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
}

function popMessage() {
    $res = isset($_SESSION['message']) ? $_SESSION['message'] : null;
    unset($_SESSION['message']);
    return $res;
}

function success() { return 'success'; }
function error() { return 'error'; }

function setMessage($type, $message) {
    $_SESSION['message'] = array('type' => $type, 'content' => $message);
}

function getAuthenticatedUser() {
    if (!isset($_SESSION['username'])) return false;
    return API\getUserByUsername($_SESSION['username']);
}

function isAuthenticated(){
    return isset($_SESSION['username']);
}




?>