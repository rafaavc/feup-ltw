<?php

namespace API;

use Database;
use Router;
use Session;
use Exception;

require_once(dirname(__FILE__) . "/user.php");

function makeAdoptionRequest($pet, $adopter)
{
    $db = Database::instance()->db();
    $stmt = $db->prepare('INSERT INTO ProposedToAdopt(userId, petId) VALUES(?, ?)');
    $stmt->execute(array($adopter, $pet));
}

function deleteAdoptionRequest($pet, $adopter)
{
    $db = Database::instance()->db();
    $stmt = $db->prepare('DELETE FROM ProposedToAdopt WHERE userId = ? AND petId = ?');
    $stmt->execute(array($adopter, $pet));
    $stmt = $db->prepare('INSERT INTO RejectedProposal VALUES (?, ?)');
    $stmt->execute(array($adopter, $pet));
}

function cancelAdoptionRequest($pet, $adopter)
{
    $db = Database::instance()->db();
    $stmt = $db->prepare('DELETE FROM ProposedToAdopt WHERE userId = ? AND petId = ?');
    $stmt->execute(array($adopter, $pet));
}


function handleAdoptionRequest($method, $pet)
{
    $adopter = Session\getAuthenticatedUser()['id'];
    $petObj = getPet($pet);
    if ($adopter == false) {
        Router\errorUnauthorized();
    } else if ($petObj == false) {
        Router\errorBadRequest();
    } else if (ownsPet($adopter, $pet) || $petObj['state'] == 'adopted') {
        Router\errorForbidden();
    }
    

    if ($method == "POST" || $method == "PUT") {
        makeAdoptionRequest($pet, $adopter);
    } else if ($method == "DELETE") {
        cancelAdoptionRequest($pet, $adopter);
    } else {
        Router\errorBadRequest();
    }

    $pta = getProposedToAdopt($adopter, $pet);
    echo json_encode($pta);
}

function acceptAdoptionRequest($pet, $adopter)
{
    $db = Database::instance()->db();
    if (!getProposedToAdopt($adopter, $pet)) {
        throw new Exception('Adopter user hasn\'t proposed to adopt pet');
    }
    try {
        $stmt = $db->prepare('INSERT INTO Adopted(userId, petId) VALUES(?, ?)');
        $stmt->execute(array($adopter, $pet));
    } catch (Exception $e) {
        throw new Exception('Pet was already adopted, or ids given are nonexistant.');
    }
    cancelAdoptionRequest($pet, $adopter);
}

function handleAdoptionReply($method, $pet, $adopter)
{
    $owner = Session\getAuthenticatedUser()['id'];
    if (!$owner) Router\errorUnauthorized();

    if ($method == "POST" || $method == "PUT") {
        try {
            acceptAdoptionRequest($pet, $adopter);
        } catch (Exception $e) {
            responseJSON(array("value" => false, "error" => $e->getMessage()));
            Router\errorBadRequest();
        }
        responseJSON(array("value" => true));
    } else if ($method == "DELETE") {
        try {
            deleteAdoptionRequest($pet, $adopter);
        } catch (Exception $e) {
            responseJSON(array("value" => false, "error" => $e->getMessage()));
            Router\errorBadRequest();
        }
        responseJSON(array("value" => true));
    } else {
        Router\errorBadRequest();
    }
}


if (Router\isAPIRequest(__FILE__)) {
    $pet = $GLOBALS['pet'];
    $adopterPost = getArrayParameter($_POST, 'adopter');
    $adopterGet = getArrayParameter($GLOBALS, 'adopter');
    $adopter = $adopterPost == null ? $adopterGet : $adopterPost;

    $method = $_SERVER['REQUEST_METHOD'];

    if ($pet != null && $adopter != null) {
        handleAdoptionReply($method, $pet, $adopter);
    } else if ($pet != null) {
        handleAdoptionRequest($method, $pet);
    } else {
        Router\errorBadRequest();
    }
}
