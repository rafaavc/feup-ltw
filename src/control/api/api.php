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

function verifyCSRF($array = null) {  // for PUT request, need to file_get_contents( 'php://input', 'r' );
    $method = $_SERVER['REQUEST_METHOD'];
    $csrf = getArrayParameter($array ? $array : (($method == "POST") ? $_POST : $GLOBALS), 'csrf');

    if ($csrf == null || $csrf != $_SESSION['csrf']) {
        Router\errorForbidden("CSRF");
    }
}

?>