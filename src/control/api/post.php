<?php

namespace API;
use Session;
use Router;
use Database;
use Exception;

require_once(dirname(__FILE__). '/pet.php');
require_once(dirname(__FILE__)."/user.php");

function addComment($petId, $userId, $comment) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('INSERT INTO Post(petId, userId, description, postDate) VALUES(?, ?, ?, ?)');
    $stmt->execute(array($petId, $userId, $comment, date('Y-m-d H:i:s')));
}

if (Router\isAPIRequest(__FILE__)) {
    verifyCSRF();

    $user = Session\getAuthenticatedUser();
    $parameters = getArrayParameters($_POST, ['petId', 'comment']);

    if (!$user) Router\errorUnauthorized("No user is logged in.");
    if ($parameters == null) Router\errorBadRequest("The required parameters were not received.");
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        try {
            addComment($parameters['petId'], $user['id'], $parameters['comment']);
            responseJSON(array("value" => true, "post" => getLastPost($parameters['petId'])));
        } catch(Exception $e) {
            Router\errorBadRequest();
        }
    } else Router\errorBadRequest();
}

?>