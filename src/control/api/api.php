<?php

namespace API;
include_once(dirname(__FILE__)."/../db.php");

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

?>