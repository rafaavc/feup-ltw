<?php

require_once(dirname(__FILE__) . "/action.php");
require_once(dirname(__FILE__) . "/../api/pet.php");
require_once(dirname(__FILE__) . "/../api/user.php");
require_once(dirname(__FILE__) . "/../file_upload.php");

$parameters = initAction(['petId', 'name', 'location', 'description', 'profilePhoto']);

// these two parameters are optional
if (isset($_POST['removePhotos'])) {
	$parameters['removePhotos'] = $_POST['removePhotos'];
}

if (!preg_match('/^[a-zA-Z]+( [a-zA-Z]+)*$/', $parameters['location'])) {
    Router\errorBadRequest("The pet's location is not valid.");
}

try {
	API\updatePet($parameters['petId'], $parameters['name'], $parameters['location'], $parameters['description']); // update pet
} catch(Exception $e) {
    Router\errorBadRequest();
}

for ($i = 0; $i < sizeof($_FILES['photos']['name']); $i++) {
	$tmpPath = $_FILES['photos']['tmp_name'][$i];
	if ($tmpPath == "") continue;
    if (!isJPGImage($tmpPath)) {
        Router\errorBadRequest("Invalid files were sent to the server.");
    }
	$photoId = API\addPetPhoto($parameters['petId']);
	$originalPath = getDocRoot()."/images/pet_pictures/" . $photoId . ".jpg";
	move_uploaded_file($tmpPath, $originalPath);

	// the new profile photo is a photo that was just uploaded
	if ($_FILES['photos']['name'][$i] == $parameters['profilePhoto']) {
		copy($originalPath, getDocRoot()."/images/pet_profile_pictures/" . $parameters['petId'] . ".jpg");
	}
}

// the new profile photo is a photo that already existed
if ($parameters['profilePhoto'] != '' && is_numeric($parameters['profilePhoto'])) {
	copy(getDocRoot()."/images/pet_pictures/".$parameters['profilePhoto'].".jpg", getDocRoot()."/images/pet_profile_pictures/" . $parameters['petId'] . ".jpg");
}

if (isset($parameters['removePhotos'])) {
	for ($i = 0; $i < sizeof($parameters['removePhotos']); $i++) {
		API\removePetPhoto($parameters['removePhotos'][$i]);
		unlink(getDocRoot() . '/images/pet_pictures/' . $parameters['removePhotos'][$i] . '.jpg');
	}
}

Router\sendTo(getRootUrl() . "/pet/" . $parameters['petId']);
