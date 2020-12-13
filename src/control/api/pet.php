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

function getUserPetsAdpotionProposals($userId) {
	$stmt = Database::db()->prepare("SELECT * FROM ProposedToAdopt JOIN Pet ON(id = petId) WHERE Pet.userId = ?");
	$stmt->execute(array($userId));
	return $stmt;
}

function getUserPetsComments($userId) {
	$stmt = Database::db()->prepare("SELECT * FROM Post JOIN Pet ON(Pet.id = petId) WHERE Pet.userId = ? ORDER BY postDate DESC");
	$stmt->execute(array($userId));
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

function updatePetColor($petId, $petColor) {
	$stmt = Database::db()->prepare("SELECT * FROM PetColor WHERE name = ?");
	$stmt->execute(array($petColor));
	$color = $stmt->fetch()['id'];
	$stmt = Database::db()->prepare("UPDATE Pet SET color = ? WHERE id = ?");
	$result['value'] = $stmt->execute(array($color, $petId));
	return $result;
}

function updatePetSpecies($petId, $petSpecie) {
	$stmt = Database::db()->prepare("SELECT * FROM PetSpecie WHERE name = ?");
	$stmt->execute(array($petSpecie));
	$species = $stmt->fetch()['id'];
	$stmt = Database::db()->prepare("UPDATE Pet SET specie = ? WHERE id = ?");
	$result['value'] = $stmt->execute(array($species, $petId));
	return $result;
}

function updatePetRace($petId, $petRace) {
	$stmt = Database::db()->prepare("SELECT * FROM PetRace WHERE name = ?");
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

function handlePetUpdateRequest() {
	$method = $_SERVER['REQUEST_METHOD'];

	if ($method == 'POST' && isset($_POST['field']) && isset($_POST['value']) && isset($_POST['petId'])){
		$field = $_POST['field'];
		$value = $_POST['value'];
		$petId = $_POST['petId'];

		if ($field == 'name') echo json_encode(updatePetName($petId, $value));
		else if ($field == 'species') echo json_encode(updatePetSpecies($petId, $value));
		else if ($field == 'race') echo json_encode(updatePetRace($petId, $value));
		else if ($field == 'color') echo json_encode(updatePetColor($petId, $value));
		else if ($field == 'description') echo json_encode(updatePetDescription($petId, $value));
		else if ($field == 'location') echo json_encode(updatePetLocation($petId, $value));
	}
}

handlePetUpdateRequest();
