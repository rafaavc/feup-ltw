<?php

namespace API;
use Database;

function getPet($petId)
{
	$db = Database::instance()->db();
	$stmt = $db->prepare('SELECT *
							FROM (
									SELECT Pet.id, userId, Pet.name, birthdate, description, datePosted, location, PetColor.name as color, PetSize.name as size, PetSpecie.name as specie, null as race FROM
									(
										(
											(Pet JOIN PetColor on(Pet.color = PetColor.id))
											JOIN PetSize ON(Pet.size = PetSize.id)
										)
										JOIN PetSpecie ON(Pet.specie = PetSpecie.id)
									)
								UNION
									SELECT Pet.id, userId, Pet.name, birthdate, description, datePosted, location, PetColor.name as color, PetSize.name as size, PetSpecie.name as specie, PetRace.name as race FROM
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
								)
							WHERE id = ?');
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

function getLastPost($petId){
	$db = Database::instance()->db();
	$stmt = $db->prepare('SELECT Post.petId as petId, Post.userId as userId, User.name as userName, Post.description as description, postDate, answerToPostId
							 FROM Post JOIN User ON User.id = Post.userId WHERE petId=? ORDER BY postDate DESC LIMIT 1');
	$stmt->execute(array($petId));

	$post = $stmt->fetchAll()[0];
    if ($post == false) return false;
    $splittedName = explode(' ', $post['userName']);
    $post['shortUserName'] = sizeof($splittedName) > 1 ? $splittedName[0]." ".$splittedName[sizeof($splittedName)-1] : $post['userName'];
	return $post;
}

function getPets() {
    $stmt = Database::db()->prepare("SELECT *
		FROM (
				SELECT Pet.id, userId, Pet.name, birthdate, description, datePosted, location, PetColor.name as color, PetSize.name as size, PetSpecie.name as specie, null as race FROM
				(
					(
						(Pet JOIN PetColor on(Pet.color = PetColor.id))
						JOIN PetSize ON(Pet.size = PetSize.id)
					)
					JOIN PetSpecie ON(Pet.specie = PetSpecie.id)
				)
			UNION
				SELECT Pet.id, userId, Pet.name, birthdate, description, datePosted, location, PetColor.name as color, PetSize.name as size, PetSpecie.name as specie, PetRace.name as race FROM
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
			) ORDER BY datePosted");
    $stmt->execute();
    return $stmt;
}

function getUserPets($userId) {
    $stmt = Database::db()->prepare("SELECT *
		FROM (
				SELECT Pet.id, userId, Pet.name, birthdate, description, datePosted, location, PetColor.name as color, PetSize.name as size, PetSpecie.name as specie, null as race FROM
				(
					(
						(Pet JOIN PetColor on(Pet.color = PetColor.id))
						JOIN PetSize ON(Pet.size = PetSize.id)
					)
					JOIN PetSpecie ON(Pet.specie = PetSpecie.id)
				)
			UNION
				SELECT Pet.id, userId, Pet.name, birthdate, description, datePosted, location, PetColor.name as color, PetSize.name as size, PetSpecie.name as specie, PetRace.name as race FROM
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
			) where userId = ?");
	$stmt->execute(array($userId));
    return $stmt;
}

function getUserPetsOpenAdoptionProposals($userId) {
	$stmt = Database::db()->prepare("SELECT User.name as propUserName, User.username as propUserUsername, User.id as propUserId, Pet.name as petName, Pet.id as petId FROM ProposedToAdopt JOIN Pet ON(Pet.id = ProposedToAdopt.petId) JOIN User ON(ProposedToAdopt.userId = User.id) WHERE Pet.userId = ?");
	$stmt->execute(array($userId));
	return $stmt;
}

function getUserPetsComments($userId) {
	$stmt = Database::db()->prepare("SELECT Post.id as postId, Pet.name as petName, Pet.id as petId, User.name as creatorName, User.username as creatorUsername, User.id as creatorId, Post.description as content, postDate FROM (Post JOIN Pet ON(Pet.id = petId)) JOIN User ON(User.id = Post.userId) WHERE Pet.userId = ? AND User.id != ? ORDER BY postDate DESC");
	$stmt->execute(array($userId, $userId));
	return $stmt;
}

function getSpecies() {
	$stmt = Database::db()->prepare("SELECT * FROM PetSpecie ORDER BY name");
    $stmt->execute();
    return $stmt;
}

function getAdopted($petId) {
	$stmt = Database::db()->prepare("SELECT * FROM Adopted JOIN User on (User.id = userId) WHERE petId = ?");
	$stmt->execute(array($petId));
	return $stmt->fetch();
}


function updatePetName($petId, $petName) {
	$stmt = Database::db()->prepare("UPDATE Pet SET name = ? WHERE id = ?");
	$result['value'] = $stmt->execute(array($petName, $petId));
	return $result;
}

function updatePetBirthdate($petId, $petBirthdate){
	$stmt = Database::db()->prepare("UPDATE Pet SET birthdate = DATE(?) WHERE id = ?");
	$result['value'] = $stmt->execute(array($petBirthdate, $petId));
	return $result;
}

function updatePetNameAge($petId, $petName, $petBirthdate) {
	$result['value'] = updatePetName($petId, $petName) && updatePetBirthdate($petId, $petBirthdate);
	return $result;
}

function updatePetColor($petId, $petColor) {
	$stmt = Database::db()->prepare("SELECT * FROM PetColor WHERE name = ?");
	$stmt->execute(array($petColor));
	if ($stmt->fetch() == false) {
		addColor($petColor);
	}
	$stmt->execute(array($petColor));
	$color = $stmt->fetch()['id'];
	$stmt = Database::db()->prepare("UPDATE Pet SET color = ? WHERE id = ?");
	$result['value'] = $stmt->execute(array($color, $petId));
	return $result;
}

function updatePetSpecies($petId, $petSpecies) {
	$stmt = Database::db()->prepare("SELECT * FROM PetSpecie WHERE name = ?");
	$stmt->execute(array($petSpecies));
	if ($stmt->fetch() == false) {
		addSpecie($petSpecies);
	}
	$stmt->execute(array($petSpecies));
	$species = $stmt->fetch()['id'];
	$stmt = Database::db()->prepare("UPDATE Pet SET specie = ? WHERE id = ?");
	$result['value'] = $stmt->execute(array($species, $petId));
	return $result;
}

function updatePetRace($petId, $petRace, $petSpecies) {
	$stmt = Database::db()->prepare("SELECT * FROM PetRace WHERE name = ?");
	$stmt->execute(array($petRace));
	if ($stmt->fetch() == false){
		$tempstmt = Database::db()->prepare("SELECT * FROM PetSpecie WHERE name = ?");
		$tempstmt->execute(array($petSpecies));
		addSpecieRace($tempstmt->fetch()['id'], $petRace);
	}
	$stmt->execute(array($petRace));
	$race = $stmt->fetch()['id'];
	$stmt = Database::db()->prepare("UPDATE Pet SET race = ? WHERE id = ?");
	$result['value'] = $stmt->execute(array($race, $petId));
	return $result;
}

function updatePetDescription($petId, $petDescription) {
	$stmt = Database::db()->prepare("UPDATE Pet SET description = ? WHERE id = ?");
	$result['value'] = $stmt->execute(array($petDescription, $petId));
	return $result;
}

function updatePetLocation($petId, $petLocation) {
	$stmt = Database::db()->prepare("UPDATE Pet SET location = ? WHERE id = ?");
	$result['value'] = $stmt->execute(array($petLocation, $petId));
	return $result;
}

function updatePetColorSpeciesRaceLocation($petId, $color, $species, $race, $location){
	$result['value'] = updatePetColor($petId, $color);
	if ($species != '')
		$result['value'] = $result['value'] /*&& updatePetSpecies($petId, $species)*/;
	if ($race != '')
		$result['value'] = $result['value'] && updatePetRace($petId, $race, $species);
	$result['value'] = $result['value'] && updatePetLocation($petId, $location);
	return $result;
}

function handlePetUpdateRequest() {
	$method = $_SERVER['REQUEST_METHOD'];

	if ($method == 'POST' && isset($_POST['field']) && isset($_POST['petId'])){
		$field = $_POST['field'];
		if (isset($_POST['value'])) {
			$value = $_POST['value'];
			if (isset($_POST['birthdate']))
				$birthdate = $_POST['birthdate'];
		}
		else if (isset($_POST['color']) && isset($_POST['species']) && isset($_POST['race']) && isset($_POST['location'])){
			$color = $_POST['color'];
			$species = $_POST['species'];
			$race = $_POST['race'];
			$location = $_POST['location'];
		} else {
			return;
		}
		$petId = $_POST['petId'];

		if ($field == 'nameAge') echo json_encode(updatePetNameAge($petId, $value, $birthdate));
		else if ($field == 'description') echo json_encode(updatePetDescription($petId, $value));
		else if ($field == 'colorSpeciesRaceLocation') echo json_encode(updatePetColorSpeciesRaceLocation($petId, $color, $species, $race, $location));
		else {
			http_response_code(400); // BAD REQUEST
			exit();
		}
	}
}

function addSpecie($name) {
	$stmt = Database::db()->prepare("INSERT INTO PetSpecie(name) VALUES(?)");
	$stmt->execute(array($name));
	return Database::db()->lastInsertId();
}

function getSizes() {
	$stmt = Database::db()->prepare("SELECT * FROM PetSize ORDER BY name");
    $stmt->execute();
    return $stmt;
}

function addSize($name) {
	$stmt = Database::db()->prepare("INSERT INTO PetSize(name) VALUES(?)");
	$stmt->execute(array($name));
	return Database::db()->lastInsertId();
}

function getColors() {
	$stmt = Database::db()->prepare("SELECT * FROM PetColor ORDER BY name");
    $stmt->execute();
    return $stmt;
}

function addColor($name) {
	$stmt = Database::db()->prepare("INSERT INTO PetColor(name) VALUES(?)");
	$stmt->execute(array($name));
	return Database::db()->lastInsertId();
}

function getSpeciesRaces($specieId) {
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

function addPet($userId, $name, $birthdate, $specie, $race, $size, $color, $location, $description) {
	$stmt = Database::db()->prepare("INSERT INTO Pet(userId, name, birthdate, specie, race, size, color, location, description, datePosted) VALUES(:userId, :name, :birthdate, :specie, :race, :size, :color, :location, :description, :datePosted)");
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

function handleSpeciesRequest() {
	$what = $GLOBALS['what'];
    $arg1 = $GLOBALS['arg1'];

    $method = $_SERVER['REQUEST_METHOD'];
    if ($what == 'races' && $method == 'GET') {
		responseJSON(array('races' => getArrayFromSTMT(getSpeciesRaces($arg1), true)));

    } /*else if ($type == 'mail' && $method == 'GET') {

		responseJSON(array('value' => emailExists($value)));

    }*/ else {
		http_response_code(400); // BAD REQUEST
        exit();
    }
}

if (isset($GLOBALS['what']) && isset($GLOBALS['arg1'])) {
	handleSpeciesRequest();
}
if (isset($_POST['field']) && isset($_POST['petId']))
	handlePetUpdateRequest();
