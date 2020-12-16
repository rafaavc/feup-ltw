<?php

namespace API;
use Database;
use Router;
use Session;

require_once(dirname(__FILE__)."/user.php");

function makeAdoptionRequest($pet, $adopter) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('INSERT INTO ProposedToAdopt(userId, petId) VALUES(?, ?)');
    $stmt->execute(array($adopter, $pet));
}

function cancelAdoptionRequest($pet, $adopter) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('DELETE FROM ProposedToAdopt WHERE userId = ? AND petId = ?');
    $stmt->execute(array($adopter, $pet));
}


function handleAdoptionRequest($method, $pet) {
    $adopter = Session\getAuthenticatedUser()['id'];
    if ($adopter == false) {
        Router\errorUnauthorized();
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

function acceptAdoptionRequest($pet, $adopter) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('INSERT INTO Adopted(userId, petId) VALUES(?, ?)');
    $stmt->execute(array($adopter, $pet));
    cancelAdoptionRequest($pet, $adopter);
}

function handleAdoptionReply($method, $pet, $adopter) {
    $owner = Session\getAuthenticatedUser()['id'];
    if ($owner == false || !ownsPet($owner, $pet)) {
        Router\errorUnauthorized();
    }
    if ($method == "POST" || $method == "PUT") {
        echo "hey";
        acceptAdoptionRequest($pet, $adopter);
    } else if ($method == "DELETE") {
        cancelAdoptionRequest($pet, $adopter);
    } else {
        Router\errorBadRequest();
    }
}


if (Router\isAPIRequest(__FILE__)) {
    $pet = $GLOBALS['pet'];
    $adopter = Router\getPostParameter('adopter');

    $method = $_SERVER['REQUEST_METHOD'];

    if ($pet != null && $adopter != null) {
        handleAdoptionReply($method, $pet, $adopter);
    } else if ($pet != null) {
        handleAdoptionRequest($method, $pet);
    } else {
        Router\errorBadRequest();
    }
}

?>

