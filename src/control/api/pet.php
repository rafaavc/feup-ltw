<?php

namespace API;
use Database;

function getPet($petId)
{
	$db = Database::instance()->db();
	$stmt = $db->prepare('SELECT Pet.id, userId, Pet.name, birthdate, description, datePosted, location, PetColor.name as color, PetSize.name as size, PetSpecie.name as specie, PetRace.name as race
							FROM (((Pet JOIN PetColor on(Pet.color = PetColor.id)) JOIN PetSize ON(Pet.size = PetSize.id)) LEFT JOIN PetSpecie ON(Pet.specie = PetSpecie.id)) LEFT JOIN PetRace ON(Pet.race = PetRace.id)
							WHERE Pet.id = ?');
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
