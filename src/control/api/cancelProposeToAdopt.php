<?php

include_once(dirname(__FILE__). '/../session.php');
include_once(dirname(__FILE__). '/../api/user.php');
include_once(dirname(__FILE__). '/../db.php');
include_once(dirname(__FILE__). '/../api/pet.php');

$petId = $_POST['petId'];
$userId = Session\getAuthenticatedUser()['id'];


$db = Database::instance()->db();
$stmt = $db->prepare('DELETE FROM ProposedToAdopt WHERE userId = ? AND petId = ?');
$stmt->execute(array($userId, $petId));


$pta = API\getProposedToAdopt($userId, $petId);
echo json_encode($pta);

?>