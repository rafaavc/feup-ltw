<?php

namespace API;

use Database;
use Router;

include_once(dirname(__FILE__)."/existence.php");


function login($username, $password) {
    $stmt = Database::db()->prepare("SELECT password FROM User WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $res = $stmt->fetch();

    return $res == false ? false : password_verify($password, $res['password']);
}

function register($name, $username, $password, $birthdate, $mail, $description) {
    try {
        $stmt = Database::db()->prepare("INSERT INTO User(name, username, password, birthdate, mail, description) VALUES(:name, :username, :password, :birthdate, :mail, :description)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':username', $username);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':mail', $mail);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
    } catch (Exception $e) {
        return false;
    }

    return Database::db()->lastInsertId();
}

function ownsPet($userId, $petId) {
    $stmt = Database::db()->prepare("SELECT id FROM Pet WHERE userId = ? AND id = ?");
    $stmt->execute(array($userId, $petId));
    $pet = $stmt->fetch();
    return $pet != false;
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
    $user['shortName'] = sizeof($splittedName) > 1 ? $splittedName[0] . " " . $splittedName[sizeof($splittedName) - 1] : $user['name'];

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

function getPublicUsers() {
    $stmt = Database::db()->prepare(
        "SELECT id, name, username, description, petCount
        FROM User
            JOIN (
                SELECT userId, count(userId) as petCount FROM Pet GROUP BY userId
            ) ON(id=userId)
        ORDER BY petCount DESC");
    $stmt->execute();
    return $stmt;
}

function updateName($name) {
    $stmt = Database::db()->prepare("UPDATE User SET name = :name WHERE username = :username AND name <> :name");
    $stmt->bindParam(':username', $_SESSION['username']);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    return $stmt->rowCount();
}

function updateUsername($username) {
    if (usernameExists($username)) return 0;
    $stmt = Database::db()->prepare("UPDATE User SET username = :newUsername WHERE username = :username");
    $stmt->bindParam(':username', $_SESSION['username']);
    $stmt->bindParam(':newUsername', $username);
    $stmt->execute();

    $_SESSION['username'] = $username;

    return getRootURL(). "/user". "/" . $username;
}

function updateMail($mail) {
    if (emailExists($mail)) return 0;
    $stmt = Database::db()->prepare("UPDATE User SET mail = :mail WHERE username = :username");
    $stmt->bindParam(':username', $_SESSION['username']);
    $stmt->bindParam(':mail', $mail);
    $stmt->execute();
    return $stmt->rowCount();
}

function updateBio($bio) {
    $stmt = Database::db()->prepare("UPDATE User SET description = :description WHERE username = :username AND description <> :description");
    $stmt->bindParam(':username', $_SESSION['username']);
    $stmt->bindParam(':description', $bio);
    $stmt->execute();
    return $stmt->rowCount();
}

function handleUpdateRequest() {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == 'POST' && isset($_POST['field']) && isset($_POST['value'])) {
        $field = $_POST['field'];
        $value = $_POST['value'];

        $response = 0;

        if ($field == "name") $response = updateName($value);
        else if ($field == "username") $response = updateUsername($value);
        else if ($field == "mail") $response = updateMail($value);
        else if ($field == "bio") $response = updateBio($value);

        echo $response;
    }
}

function getProposedToAdopt($userId, $petId){
    $stmt = Database::db()->prepare("SELECT * FROM ProposedToAdopt WHERE userId = ? AND petId = ?");
    $stmt->execute(array($userId, $petId));

    return $stmt->fetch();
}

function getUserLists($userId){
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT * FROM List WHERE userId=?');
    $stmt->execute(array($userId));
    return $stmt->fetchAll();
}

function getListPets($list){
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT id, userId, name, birthdate, specie, race, size, color, location, description FROM ListPet, Pet WHERE listId=? AND Pet.id = ListPet.petId');
    $stmt->execute(array($list['id']));
    return $stmt->fetchAll();
}

if (Router\isAPIRequest(__FILE__)) {
    handleUpdateRequest();
}
