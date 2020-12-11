<?php

namespace API;
use Router;
use Database;

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

function getUserByUsername($username) {
    $stmt = Database::db()->prepare("SELECT * FROM User WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch();
    if ($user == false) return false;
    $splittedName = explode(' ', $user['name']);
    $user['shortName'] = sizeof($splittedName) > 1 ? $splittedName[0]." ".$splittedName[sizeof($splittedName)-1] : $user['name'];

    return $user;
}

function getUserById($userId){
    $stmt = Database::db()->prepare("SELECT * FROM User WHERE id = :id");
    $stmt->bindParam(':id', $userId);
    $stmt->execute();

    $user = $stmt->fetch();
    if ($user == false) return false;
    $splittedName = explode(' ', $user['name']);
    $user['shortName'] = sizeof($splittedName) > 1 ? $splittedName[0]." ".$splittedName[sizeof($splittedName)-1] : $user['name'];

    return $user;
}

function getUsers() {
    $stmt = Database::db()->prepare(
        "SELECT *
        FROM User
            JOIN (
                SELECT userId, count(userId) as petCount FROM Pet GROUP BY userId
            ) ON(id=userId) 
        ORDER BY petCount DESC");
    $stmt->execute();
    return $stmt;
}


?>