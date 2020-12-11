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
    $stmt = Database::db()->prepare("SELECT Pet.id, userId, Pet.name, birthdate, description, datePosted, location, PetColor.name as color, PetSize.name as size, PetSpecie.name as specie, PetRace.name as race
                                        FROM (((Pet JOIN PetColor on(Pet.color = PetColor.id)) JOIN PetSize ON(Pet.size = PetSize.id)) LEFT JOIN PetSpecie ON(Pet.specie = PetSpecie.id)) LEFT JOIN PetRace ON(Pet.race = PetRace.id)
                                        ORDER BY datePosted DESC");
    $stmt->execute();
    return $stmt;
}

function getSpecies() {
    $stmt = Database::db()->prepare("SELECT * FROM PetSpecie ORDER BY name");
    $stmt->execute();
    return $stmt;
}


?>
