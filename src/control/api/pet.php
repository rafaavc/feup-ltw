<?php

namespace API;

use Database;

function getPet($petId)
{
	$db = Database::instance()->db();
	$stmt = $db->prepare('SELECT * FROM Pet WHERE id=?');
	$stmt->execute(array($petId));
	return $stmt->fetch();
}

function getSpecie($specieId)
{
	$db = Database::instance()->db();
	$stmt = $db->prepare('SELECT name FROM PetSpecie WHERE id=?');
	$stmt->execute(array($specieId));
	return $stmt->fetch();
}

function getRace($raceId)
{
	$db = Database::instance()->db();
	$stmt = $db->prepare('SELECT name FROM PetRace WHERE id=?');
	$stmt->execute(array($raceId));
	return $stmt->fetch();
}

function getColor($colorId)
{
	$db = Database::instance()->db();
	$stmt = $db->prepare('SELECT name FROM PetColor WHERE id=?');
	$stmt->execute(array($colorId));
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

?>
