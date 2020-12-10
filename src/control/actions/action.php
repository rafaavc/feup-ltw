<?php

require_once(dirname(__FILE__)."/../config.php");

if (!isset($_POST['csrf']) || $_SESSION['csrf'] != $_POST['csrf']) {
    http_response_code(503); // FORBIDDEN
    exit();
}

function &initAction($parameters) {
    $res = Router\getPostParameters($parameters);

    if ($res == null) {
        http_response_code(400); // BAD REQUEST
        //Session\setLastMessage(Session\error(), 'Bad request.');
        exit();
    }
    return $res;
}

?>

