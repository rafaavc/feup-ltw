<?php

namespace API;
use Router;
use Database;

function usernameExists($username) {
    $stmt = Database::db()->prepare("SELECT id FROM User WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    return $stmt->fetch() == false ? false : true;
}

function emailExists($mail) {
    $stmt = Database::db()->prepare("SELECT id FROM User WHERE mail = :mail");
    $stmt->bindParam(':mail', $mail);
    $stmt->execute();

    return $stmt->fetch() == false ? false : true;
}

function handleExistenceRequest() {
    $type = $GLOBALS['type'];
    $value = $GLOBALS['value'];
    $method = $_SERVER['REQUEST_METHOD'];

    if ($type == 'username' && $method == 'GET') {
        
        responseJSON(array('value' => usernameExists($value)));

    } else if ($type == 'mail' && $method == 'GET') {

        responseJSON(array('value' => emailExists($value)));

    } else {
        http_response_code(400); // BAD REQUEST
        exit();
    }
}

if (Router\isAPIRequest(__FILE__) && isset($GLOBALS['type']) && isset($GLOBALS['value'])) {
    handleExistenceRequest();
}

?>