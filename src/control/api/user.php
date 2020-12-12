<?php

namespace API;

use Router;
use Database;

include_once(dirname(__FILE__)."/existence.php");


function login($username, $password) {
    $stmt = Database::db()->prepare("SELECT password FROM User WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $res = $stmt->fetch();

    return $res == false ? false : password_verify($password, $res['password']);
}

function register($name, $username, $password, $birthdate, $mail, $description) {
    $stmt = Database::db()->prepare("INSERT INTO User(name, username, password, birthdate, mail, description) VALUES(:name, :username, :password, :birthdate, :mail, :description)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));
    $stmt->bindParam(':birthdate', $birthdate);
    $stmt->bindParam(':mail', $mail);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    /* TODO */
    return true;
}

function getUser($username) {
    $stmt = Database::db()->prepare("SELECT * FROM User WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch();
    if ($user == false) return false;
    $splittedName = explode(' ', $user['name']);
    $user['shortName'] = sizeof($splittedName) > 1 ? $splittedName[0] . " " . $splittedName[sizeof($splittedName) - 1] : $user['name'];

    return $user;
}

function updateName($name) {
    $stmt = Database::db()->prepare("UPDATE User SET name = :name WHERE username = :username AND name <> :name");
    $stmt->bindParam(':username', $GLOBALS['username']);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    return $stmt->rowCount();
}

function updateUsername($username) {
    if (usernameExists($username)) return 0;
    $stmt = Database::db()->prepare("UPDATE User SET username = :newUsername WHERE username = :username");
    $stmt->bindParam(':username', $GLOBALS['username']);
    $stmt->bindParam(':newUsername', $username);
    $stmt->execute();

    $GLOBALS['username'] = $username;
    $_SESSION['username'] = $username;

    return $stmt->rowCount();
}

function updateMail($mail) {
    echo $GLOBALS['username'];
    if (emailExists($mail)) return 0;
    $stmt = Database::db()->prepare("UPDATE User SET mail = :mail WHERE username = :username");
    $stmt->bindParam(':username', $GLOBALS['username']);
    $stmt->bindParam(':mail', $mail);
    $stmt->execute();
    return $stmt->rowCount();
}

function updateBio($bio) {
    $stmt = Database::db()->prepare("UPDATE User SET description = :description WHERE username = :username AND description <> :description");
    $stmt->bindParam(':username', $GLOBALS['username']);
    $stmt->bindParam(':description', $bio);
    $stmt->execute();
    return $stmt->rowCount();
}

function handleUpdateRequest() {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == 'POST' && isset($_POST['field']) && isset($_POST['value'])) {
        $field = $_POST['field'];
        $value = $_POST['value'];
        echo $_SESSION['username'];

        $response = 0;
        //TODO: filter input
        if ($field == "name") $response = updateName($value);
        else if ($field == "username") $response = updateUsername($value);
        else if ($field == "mail") $response = updateMail($value);
        else if ($field == "bio") $response = updateBio($value);

        echo $response;
    }
}

// use getAuthenticatedUser() ?
// if (isset($GLOBALS['username'])) {
//     handleUpdateRequest();
// }

handleUpdateRequest();