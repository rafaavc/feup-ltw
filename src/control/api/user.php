<?php

namespace API;

use Session;
use Database;
use Router;

include_once(dirname(__FILE__)."/existence.php");
include_once(dirname(__FILE__)."/pet.php");


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

$GLOBALS['userTableQuery'] = "
    User
    LEFT JOIN (
        SELECT userId, count(userId) as petCount FROM Pet GROUP BY userId
    ) ON(id=userId)";

function getUsers() {
    $stmt = Database::db()->prepare(
        "SELECT *
        FROM ".$GLOBALS['userTableQuery']."
        ORDER BY petCount DESC");
    $stmt->execute();
    return $stmt;
}

function getPublicUsers() {
    $stmt = Database::db()->prepare(
        "SELECT id, name, username, description, petCount
        FROM ".$GLOBALS['userTableQuery']."
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

    return getRootURL(). "/user/" . $username;
}

function updateMail($mail) {
    if (emailExists($mail)) return 0;

    if (!preg_match("/^[a-zA-Z0-9_.@]+$/", $mail)) {
        Router\errorBadRequest("You didn't give a correct email.");
    }

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

function updatePassword($password) {
    $stmt = Database::db()->prepare("UPDATE User SET password = :password WHERE username = :username");
    $stmt->bindParam(':username', $_SESSION['username']);
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bindParam(':password', $passwordHash);
    $stmt->execute();
    return $stmt->rowCount();
}

function createList($title, $visibility, $description) {
    $stmt = Database::db()->prepare("INSERT INTO List(title, description, public, userId) VALUES (:title, :description, :public, :userId);");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':public', $visibility);
    $stmt->bindParam(':userId', Session\getAuthenticatedUser()['id']);
    $stmt->execute();
    return $stmt->rowCount();
}

function deleteList($listId) {
    $stmt = Database::db()->prepare("DELETE FROM List WHERE id  = :listId");
    $stmt->bindParam(':listId', $listId);
    $stmt->execute();
    return $stmt->rowCount();
}

function handleNewPasswordRequest() {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == 'POST' && isset($_POST['currentPassword'], $_POST['newPassword'], $_POST['confirmPassword'])) {
        if (!password_verify($_POST['currentPassword'], Session\getAuthenticatedUser()['password'])) {
            responseJSON(array('success' => -1));
            return;
        }
        if (strcmp($_POST['newPassword'], $_POST['confirmPassword']) != 0) {
            responseJSON(array('success' => -2));
            return;
        }
        updatePassword($_POST['newPassword']);
        responseJSON(array('success' => 1));
    }
}

function handleListDeletionRequest() {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == 'POST' && isset($_POST['listId'])) {
        responseJSON(array('deleted' => deleteList($_POST['listId'])));
    }
}

function handleListCreationRequest() {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == 'POST' && isset($_POST['title'], $_POST['visibility'], $_POST['description'])) {
        createList($_POST['title'], $_POST['visibility'], $_POST['description']);
        responseJSON(array('id' => Database::db()->lastInsertId()));
    }
}

function handleTilesRequest() {
    $method = $_SERVER['REQUEST_METHOD'];

    $arr = array();
    if ($method == 'POST' && isset($_POST['userLists'])) {
        $userId = getUserByUsername($_POST['userLists'])['id'];
        $userLists = getUserLists($userId);
        foreach ($userLists as $userList){
            if ((Session\isAuthenticated() && $_POST['userLists'] == Session\getAuthenticatedUser()['username'])
                    || ($userList['public'] == 1)) {
                array_push($arr, getListPets($userList));
            }
        }

        responseJSON(array('pets' => getArrayFromSTMT(getUserPets($userId), true), 'lists' => $arr));
    }
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
    handleTilesRequest();
    handleListCreationRequest();
    handleListDeletionRequest();
    handleNewPasswordRequest();
}
