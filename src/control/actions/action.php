<?php

require_once(dirname(__FILE__)."/../config.php");

if (!isset($_POST['csrf']) || $_SESSION['csrf'] != $_POST['csrf']) {
    Router\errorForbidden();
}

function &initAction($parameters) {
    $res = Router\getPostParameters($parameters);

    if ($res == null) {
        Router\errorBadRequest();
    }
    return $res;
}

?>

