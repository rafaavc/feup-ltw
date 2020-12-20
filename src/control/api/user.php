<?php

namespace API;

use Session;
use Database;
use Exception;
use Router;

use function Session\success;

require_once(dirname(__FILE__)."/existence.php");
require_once(dirname(__FILE__)."/pet.php");


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
    if (strlen($name) < 1) return array('success' => false, 'message' => 'Name cannot be empty');
    if (strlen($name) > 40) return array('success' => false, 'message' => 'Name cannot have more than 40 characters');

    try {
        $stmt = Database::db()->prepare("UPDATE User SET name = :name WHERE username = :username AND name <> :name");
        $stmt->bindParam(':username', $_SESSION['username']);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
    } catch(Exception $e) {
        return array('success' => false, 'message' => 'Name does not fulfill database requirements');
    }
    return array('success' => true, 'message' => 'Name updated successfully!');
}

function updateUsername($username) {
    if (usernameExists($username)) return array('success' => false, 'message' => 'Username already exists');

    if (!preg_match('/^[a-zA-Z0-9]+((_|\.)[a-zA-Z0-9]+)*$/', $username))
        return array('success' => false, 'message' => 'Invalid username');

    if (strlen($username) < 5) return array('success' => false, 'message' => 'Username needs at least 5 characters');
    if (strlen($username) > 15) return array('success' => false, 'message' => 'Username cannot have more than 15 characters');

    try {
        $stmt = Database::db()->prepare("UPDATE User SET username = :newUsername WHERE username = :username");
        $stmt->bindParam(':username', $_SESSION['username']);
        $stmt->bindParam(':newUsername', $username);
        $stmt->execute();
    } catch(Exception $e) {
        return array('success' => false, 'message' => 'Username does not fulfill database requirements');
    }

    $_SESSION['username'] = $username;

    return array('success' => true, 'message' => 'Username updated successfully!', 'updateUrl' => getRootURL(). "/user/" . $username);
}

function updateMail($mail) {
    if (emailExists($mail)) return array('success' => false, 'message' => 'Email already in use');

    if (!preg_match('/^([a-zA-Z0-9]+(_|\.))*[a-zA-Z0-9]+@([a-zA-Z0-9]+\.)*[a-zA-Z]+$/', $mail))
        return array('success' => false, 'message' => 'Invalid email');

    try {
        $stmt = Database::db()->prepare("UPDATE User SET mail = :mail WHERE username = :username");
        $stmt->bindParam(':username', $_SESSION['username']);
        $stmt->bindParam(':mail', $mail);
        $stmt->execute();
    } catch(Exception $e) {
        return array('success' => false, 'message' => 'Email does not fulfill database requirements');
    }
    return array('success' => true, 'message' => 'Email updated successfully!');
}

function updateBio($bio) {
    if (strlen($bio) > 300) return array('success' => false, 'message' => 'Bio cannot have more than 300 characters');

    try {
        $stmt = Database::db()->prepare("UPDATE User SET description = :description WHERE username = :username AND description <> :description");
        $stmt->bindParam(':username', $_SESSION['username']);
        $stmt->bindParam(':description', $bio);
        $stmt->execute();
    } catch(Exception $e) {
        return array('success' => false, 'message' => 'Bio does not fulfill database requirements');
    }
    return array('success' => true, 'message' => 'Bio updated successfully!');
}

function updatePassword($password) {
    try {
        $stmt = Database::db()->prepare("UPDATE User SET password = :password WHERE username = :username");
        $stmt->bindParam(':username', $_SESSION['username']);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->execute();
    } catch(Exception $e) {
        return array('success' => false, 'message' => 'Password does not fulfill database requirements');
    }
    return array('success' => true, 'message' => 'Password updated successfully!');
}

function createList($title, $visibility, $description) {
    try {
        $stmt = Database::db()->prepare("INSERT INTO List(title, description, public, userId) VALUES (:title, :description, :public, :userId);");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':public', $visibility);
        $stmt->bindParam(':userId', Session\getAuthenticatedUser()['id']);
        $stmt->execute();
    } catch(Exception $e) {
        return array('success' => false, 'message' => 'Title does not fulfill database requirements');
    }
    return array('id' => Database::db()->lastInsertId());
}

function deleteList($listId) {
    $stmt = Database::db()->prepare("DELETE FROM List WHERE id  = :listId");
    $stmt->bindParam(':listId', $listId);
    $stmt->execute();
    return $stmt->rowCount();
}

function handleNewPasswordRequest($params) {
    if (!Session\isAuthenticated()) {
        Router\errorUnauthorized("User is not logged in", "success", "message");
    }
    if (!password_verify($params['currentPassword'], Session\getAuthenticatedUser()['password'])) {
        Router\errorUnauthorized("Wrong current password", "success", "message");
    }
    if (strcmp($params['newPassword'], $params['confirmPassword']) != 0) {
        Router\errorBadRequest("Passwords do not match", "success", "message");
    }
    responseJSON(updatePassword($params['newPassword']));
}

function handleListDeletionRequest() {
    responseJSON(array('deleted' => deleteList($GLOBALS['listParam'])));
}

function handleListCreationRequest() {
    responseJSON(createList($_POST['title'], $_POST['visibility'], $_POST['description']));
}

function handleTilesRequest() {
    $arr = array();
    $user = getUserByUsername($GLOBALS['listParam']);
    if (!$user) Router\errorBadRequest("Invalid user", "success", "message");
    $userId = $user['id'];
    $userLists = getUserLists($userId);
    $authenticatedUser = Session\getAuthenticatedUser();
    foreach ($userLists as $userList){
        // Only checks the csrf to get the user private lists
        if (($authenticatedUser && $GLOBALS['listParam'] == $authenticatedUser['username'] && isset($GLOBALS['csrf']) && $GLOBALS['csrf'] == $_SESSION['csrf'])
                || ($userList['public'] == 1)) {
            array_push($arr, getListPets($userList));
        }
    }

    responseJSON(array('pets' => getArrayFromSTMT(getUserPets($userId), true), 'lists' => $arr));
}

function handleUpdateRequest($params) {
    $field = $params['field'];
    $value = $params['value'];

    if ($field == "name") $response = updateName($value);
    else if ($field == "username") $response = updateUsername($value);
    else if ($field == "mail") $response = updateMail($value);
    else if ($field == "bio") $response = updateBio($value);
    else Router\errorBadRequest(null, "success", "message");

    responseJSON($response);
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
    $stmt = $db->prepare('SELECT id, userId, name, birthdate, specie, race, size, color, location, description, state FROM ListPet JOIN '.$GLOBALS['petQuery'].' WHERE listId=? AND id = ListPet.petId');
    $stmt->execute(array($list['id']));
    return $stmt->fetchAll();
}

if (Router\isAPIRequest(__FILE__)) {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == "GET" && getArrayParameter($GLOBALS, 'listParam') != null) {
        handleTilesRequest();
    } else if ($method == "PUT") {
        $data = file_get_contents( 'php://input', 'r' );
        parse_str($data, $params);

        verifyCSRF($params);
        if (getArrayParameters($params, ['field', 'value'])) handleUpdateRequest($params);
        else if (getArrayParameters($params, ['currentPassword', 'newPassword', 'confirmPassword'])) handleNewPasswordRequest($params);
        else {
            Router\errorBadRequest(null, "success", "message");
        }
    } else if ($method == "POST" && getArrayParameters($_POST, ['title', 'visibility', 'description'])) {
        verifyCSRF();
        handleListCreationRequest();
    } else if ($method == "DELETE" && getArrayParameter($GLOBALS, 'listParam')) {
        verifyCSRF();
        handleListDeletionRequest();
    } else {
        Router\errorBadRequest(null, "success", "message");
    }
}
