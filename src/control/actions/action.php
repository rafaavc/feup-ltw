<?php

require_once(dirname(__FILE__)."/../config.php");

if (!isset($_POST['csrf']) || $_SESSION['csrf'] != $_POST['csrf']) {
    Router\errorForbidden("CSRF");
}

function &initAction($parameters) {
    $res = getArrayParameters($_POST, $parameters);

    if ($res == null) {
        Router\errorBadRequest();
    }
    return $res;
}

?>

