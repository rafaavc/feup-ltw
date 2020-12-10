<?php

namespace API;
include_once(dirname(__FILE__)."/../db.php");

function responseJSON($response) {
    echo json_encode($response);
}

?>

