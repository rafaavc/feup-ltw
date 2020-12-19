<?php

namespace API;
use Router;
require_once(dirname(__FILE__)."/../db.php");

function responseJSON($response) {
    echo json_encode($response);
}

function getArrayFromSTMT($stmt, $amount) {
    if ($amount == true) {
        return $stmt->fetchAll();
    }
    $res = array();
    $count = 0;
    while(($row = $stmt->fetch()) != false && $count < $amount) {
        array_push($res, $row);
        $count++;
    }
    return $res;
}

function verifyCSRF() {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == "POST")
        $csrf = isset($_POST['csrf']) ? $_POST['csrf'] : null;
    else if ($method == "GET")
        $csrf = isset($GLOBALS['csrf']) ? $GLOBALS['csrf'] : null;

    if ($csrf == null || $csrf != $_SESSION['csrf']) {
        Router\errorForbidden();
    }
}

?>