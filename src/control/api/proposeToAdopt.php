<?php

include_once(dirname(__FILE__). '/../session.php');
include_once(dirname(__FILE__). '/../api/user.php');
include_once(dirname(__FILE__). '/../db.php');
include_once(dirname(__FILE__). '/../api/pet.php');

function proposeToAdopt($petId) {
    $userId = Session\getAuthenticatedUser()['id'];


    $db = Database::instance()->db();
    $stmt = $db->prepare('INSERT INTO ProposedToAdopt(userId, petId) VALUES(?, ?)');
    $stmt->execute(array($userId, $petId));


    $pta = API\getProposedToAdopt($userId, $petId);
    return json_encode($pta);
}

function cancelProposeToAdopt($petId)
{
	$userId = Session\getAuthenticatedUser()['id'];


    $db = Database::instance()->db();
    $stmt = $db->prepare('DELETE FROM ProposedToAdopt WHERE userId = ? AND petId = ?');
    $stmt->execute(array($userId, $petId));


    $pta = API\getProposedToAdopt($userId, $petId);
    return json_encode($pta);
}

function handlePropose(){
	$method = $_SERVER['REQUEST_METHOD'];

    if ($method == 'POST' && isset($_POST['petId']) && isset($_POST['value'])) {
		$petId = $_POST['petId'];
		$value = $_POST['value'];

		if ($value == 'cancel') echo cancelProposeToAdopt($petId);
		if ($value == 'adopt') echo proposeToAdopt($petId);
    }

}

handlePropose();
?>