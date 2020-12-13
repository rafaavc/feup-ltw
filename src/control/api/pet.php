<?php

namespace API;
use Database;

function getPet($petId)
{
	$db = Database::instance()->db();
	$stmt = $db->prepare("SELECT *
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
			) WHERE id = ?");
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


?>
