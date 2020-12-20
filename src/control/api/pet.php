<?php

namespace API;

use Database;
use Router;

$petRaceSpecieQuery = "(
	SELECT Pet.id, userId as ownerId, Pet.name, birthdate, description, datePosted, location, PetColor.name as color, PetSize.name as size, PetSpecie.name as specie, null as race, archived FROM
		(
			(
				(Pet JOIN PetColor on(Pet.color = PetColor.id))
				JOIN PetSize ON(Pet.size = PetSize.id)
			)
			JOIN PetSpecie ON(Pet.specie = PetSpecie.id)
		)
	UNION
		SELECT Pet.id, userId as ownerId, Pet.name, birthdate, description, datePosted, location, PetColor.name as color, PetSize.name as size, PetSpecie.name as specie, PetRace.name as race, archived FROM
		(
			(
				(
					(Pet JOIN PetColor on(Pet.color = PetColor.id))
					JOIN PetSize ON(Pet.size = PetSize.id)
				)
				JOIN PetRace ON(Pet.race = PetRace.id)
			)
			JOIN PetSpecie ON(PetRace.specieId = PetSpecie.id)
		)
	)";

$GLOBALS['petQuery'] = "
		(SELECT id, ownerId as userId, name, birthdate, description, datePosted, location, color, size, specie, race, 'adopted' as state 
		FROM ".$petRaceSpecieQuery." JOIN Adopted ON(id = petId)
		UNION
		SELECT id, ownerId as userId, name, birthdate, description, datePosted, location, color, size, specie, race, 'archived' as state 
		FROM ".$petRaceSpecieQuery." WHERE archived = 1
		UNION
		SELECT id, ownerId as userId, name, birthdate, description, datePosted, location, color, size, specie, race, 'ready' as state 
		FROM ".$petRaceSpecieQuery." WHERE archived != 1 AND 
			id NOT IN (SELECT id
			FROM ".$petRaceSpecieQuery." JOIN Adopted ON(id = petId)))
	";

function getPet($petId)
{
	$db = Database::instance()->db();
	$stmt = $db->prepare("SELECT * FROM " . $GLOBALS['petQuery'] . " WHERE id = ?");
	$stmt->execute(array($petId));
	return $stmt->fetch();
}

function getPetPhotos($petId)
{
	$db = Database::instance()->db();
	$stmt = $db->prepare('SELECT photoId FROM PetPhoto WHERE petId=?');
	$stmt->execute(array($petId));
	return $stmt->fetchAll();
}

function getPosts($petId)
{
	$db = Database::instance()->db();
	$stmt = $db->prepare('SELECT * FROM Post WHERE petId=?');
	$stmt->execute(array($petId));
	return $stmt->fetchAll();
}

function getLastPost($petId)
{
	$db = Database::instance()->db();
	$stmt = $db->prepare('SELECT Post.petId as petId, Post.userId as userId, User.name as userName, username as userUsername, Post.description as description, postDate, answerToPostId
							 FROM Post JOIN User ON User.id = Post.userId WHERE petId=? ORDER BY postDate DESC LIMIT 1');
	$stmt->execute(array($petId));

	$post = $stmt->fetchAll()[0];
	if ($post == false) return false;
	$splittedName = explode(' ', $post['userName']);
	$post['shortUserName'] = sizeof($splittedName) > 1 ? $splittedName[0] . " " . $splittedName[sizeof($splittedName) - 1] : $post['userName'];
	return $post;
}

function getPets()
{
	$stmt = Database::db()->prepare("SELECT * FROM " . $GLOBALS['petQuery'] . " ORDER BY datePosted");
	$stmt->execute();
	return $stmt;
}

function getUserPets($userId)
{
	$stmt = Database::db()->prepare("SELECT * FROM " . $GLOBALS['petQuery'] . " where userId = ?");
	$stmt->execute(array($userId));
	return $stmt;
}

function getPetOpenAdoptionProposals($petId){
	$stmt = Database::db()->prepare('SELECT User.name as fullName, User.username as username, User.id as userId, Pet.name as petName, Pet.id as petId FROM ProposedToAdopt JOIN Pet ON (Pet.id = ProposedToAdopt.petId) JOIN User ON(ProposedToAdopt.userId = User.id) WHERE Pet.id = ?');
	$stmt->execute(array($petId));
	return $stmt->fetchAll();
}

function getPetRejectedProposals($petId){
	$stmt = Database::db()->prepare('SELECT User.name as fullName, User.username as username, User.id as userId, Pet.name as petName, Pet.id as petId FROM RejectedProposal JOIN Pet ON (Pet.id = RejectedProposal.petId) JOIN User ON(RejectedProposal.userId = User.id) WHERE Pet.id = ?');
	$stmt->execute(array($petId));
	return $stmt->fetchAll();
}

function getUserPetsOpenAdoptionProposals($userId)
{
	$stmt = Database::db()->prepare("SELECT User.name as propUserName, User.username as propUserUsername, User.id as propUserId, Pet.name as petName, Pet.id as petId FROM ProposedToAdopt JOIN Pet ON(Pet.id = ProposedToAdopt.petId) JOIN User ON(ProposedToAdopt.userId = User.id) WHERE Pet.userId = ?");
	$stmt->execute(array($userId));
	return $stmt;
}

function getUserPetsComments($userId)
{
	$stmt = Database::db()->prepare("SELECT Post.id as postId, Pet.name as petName, Pet.id as petId, User.name as creatorName, User.username as creatorUsername, User.id as creatorId, Post.description as content, postDate FROM (Post JOIN Pet ON(Pet.id = petId)) JOIN User ON(User.id = Post.userId) WHERE Pet.userId = ? AND User.id != ? ORDER BY postDate DESC");
	$stmt->execute(array($userId, $userId));
	return $stmt;
}

function getSpecies()
{
	$stmt = Database::db()->prepare("SELECT * FROM PetSpecie ORDER BY name");
	$stmt->execute();
	return $stmt;
}

function getAdopted($petId)
{
	$stmt = Database::db()->prepare("SELECT * FROM Adopted JOIN User on (User.id = userId) WHERE petId = ?");
	$stmt->execute(array($petId));
	return $stmt->fetch();
}

function updatePet($petId, $name, $location, $description)
{
	$stmt = Database::db()->prepare("UPDATE Pet SET name = :name, location = :location, description = :description WHERE Id = :petId");
	$name = $name == '' ? null : $name;
	$stmt->bindParam(':name', $name);
	$stmt->bindParam(':location', $location);
	$stmt->bindParam(':description', $description);
	$stmt->bindParam(':petId', $petId);
	$stmt->execute();
	return Database::db()->lastInsertId();
}

function addSpecie($name)
{
	$stmt = Database::db()->prepare("INSERT INTO PetSpecie(name) VALUES(?)");
	$stmt->execute(array($name));
	return Database::db()->lastInsertId();
}

function getSpecie($specie)
{
	$stmt = Database::db()->prepare("SELECT * FROM PetSpecie WHERE name = ?");
	$stmt->execute(array($specie));
	return $stmt->fetch();
}

function getSizes()
{
	$stmt = Database::db()->prepare("SELECT * FROM PetSize ORDER BY name");
	$stmt->execute();
	return $stmt;
}

function addSize($name)
{
	$stmt = Database::db()->prepare("INSERT INTO PetSize(name) VALUES(?)");
	$stmt->execute(array($name));
	return Database::db()->lastInsertId();
}

function getColors()
{
	$stmt = Database::db()->prepare("SELECT * FROM PetColor ORDER BY name");
	$stmt->execute();
	return $stmt;
}

function addColor($name)
{
	$stmt = Database::db()->prepare("INSERT INTO PetColor(name) VALUES(?)");
	$stmt->execute(array($name));
	return Database::db()->lastInsertId();
}

function getSpeciesRaces($specieId)
{
	$stmt = Database::db()->prepare("SELECT * FROM PetRace WHERE specieId = ? ORDER BY name");
	$stmt->execute(array($specieId));
	return $stmt;
}

function addSpecieRace($specieId, $raceName) {
	$stmt = Database::db()->prepare("INSERT INTO PetRace(specieId, name) VALUES(?, ?)");
	$stmt->execute(array($specieId, $raceName));
	return Database::db()->lastInsertId();
}

function addPetPhoto($petId) {
	$stmt = Database::db()->prepare("INSERT INTO PetPhoto(petId) VALUES(?)");
	$stmt->execute(array($petId));
	return Database::db()->lastInsertId();
}

function handleIndexTilesRequest() {
	responseJSON(array('pets' => getArrayFromSTMT(getPets(), $GLOBALS['size'])));
}

function addPet($userId, $name, $birthdate, $specie, $race, $size, $color, $location, $description) {
	$stmt = Database::db()->prepare("INSERT INTO Pet(userId, name, birthdate, specie, race, size, color, location, description, datePosted, archived) VALUES(:userId, :name, :birthdate, :specie, :race, :size, :color, :location, :description, :datePosted, 0)");
	$stmt->bindParam(':userId', $userId);
	$stmt->bindParam(':name', $name);
	$stmt->bindParam(':birthdate', $birthdate);
	$stmt->bindParam(':specie', $specie);
	$stmt->bindParam(':race', $race);
	$stmt->bindParam(':size', $size);
	$stmt->bindParam(':color', $color);
	$stmt->bindParam(':location', $location);
	$stmt->bindParam(':description', $description);
	$datePosted = date('Y-m-d H:i:s');
	$stmt->bindParam(':datePosted', $datePosted);
	$stmt->execute();
	return Database::db()->lastInsertId();
}

function handleSpeciesRequest()
{
	$what = $GLOBALS['what'];
	$arg1 = $GLOBALS['arg1'];

	if ($what == 'races') {    // already comes verifyed as GET
		responseJSON(array('races' => getArrayFromSTMT(getSpeciesRaces($arg1), true)));
	} else {
		Router\errorBadRequest();
	}
}

function removePetPhoto($photoId)
{
	$stmt = Database::db()->prepare("DELETE FROM PetPhoto WHERE photoId = ?");
	$stmt->execute(array($photoId));
	return $stmt;
}

function addPetToList($petId, $listId){
	$stmt = Database::db()->prepare("INSERT INTO ListPet VALUES (?, ?)");
	$stmt->execute(array($listId, $petId));
	$result['value'] = $stmt != false;
	return $result;
}

function getPetName($petId) {
	$pet = getPet($petId);
	return $pet['name'] == null || $pet['name'] == '' ? $pet['size']." ".$pet['color']." ".$pet['specie'] : $pet['name'];
}


if (Router\isAPIRequest(__FILE__)) {
	$method = $_SERVER['REQUEST_METHOD'];
	try {
		if ($method == "GET") {
			if (getArrayParameters($GLOBALS, ['what', 'arg1']) != null) handleSpeciesRequest();
			else if (getArrayParameter($GLOBALS, 'size') != null) handleIndexTilesRequest();
		} else if ($method == "POST" || $method == "PUT") {
			verifyCSRF();
			if (getArrayParameters($_POST, ['petId', 'listId']) != null) {
				responseJSON(addPetToList($_POST['petId'], $_POST['listId']));
			}
		} else {
			Router\errorBadRequest();
		}
	} catch(Exception $e) {
		Router\errorBadRequest();
	}
}

?>