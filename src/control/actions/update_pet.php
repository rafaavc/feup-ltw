<?php

require_once(dirname(__FILE__) . "/action.php");
require_once(dirname(__FILE__) . "/../api/pet.php");
require_once(dirname(__FILE__) . "/../api/user.php");
require_once(dirname(__FILE__) . "/../file_upload.php");

$parameters = initAction(['petId', 'location', 'description', 'profilePhoto']);

if (isset($_POST['removePhotos'])) {
	$parameters['removePhotos'] = $_POST['removePhotos'];
}
if (isset($_POST['name'])){
	$parameters['name'] = $_POST['name'];
}

API\updatePet($parameters['petId'], $parameters['name'], $parameters['location'], $parameters['description']); // update pet

echo var_dump($_FILES);
echo var_dump($parameters);

for ($i = 0; $i < sizeof($_FILES['photos']['name']); $i++) {
	$tmpPath = $_FILES['photos']['tmp_name'][$i];
	if ($tmpPath == "") continue;
    if (!isJPGImage($tmpPath)) {
        Router\errorBadRequest("Invalid files were sent to the server.");
    }
	$photoId = API\addPetPhoto($parameters['petId']);
	$originalPath = getDocRoot()."/images/petPictures/" . $photoId . ".jpg";
	move_uploaded_file($tmpPath, $originalPath);

	if ($_FILES['photos']['name'][$i] == $parameters['profilePhoto']) {
		copy($originalPath, getDocRoot()."/images/petProfilePictures/" . $parameters['petId'] . ".jpg");
	}
}

if ($parameters != '' && is_numeric($parameters['profilePhoto'])) {
	copy(getDocRoot()."/images/petPictures/".$parameters['profilePhoto'].".jpg", getDocRoot()."/images/petProfilePictures/" . $parameters['petId'] . ".jpg");
}

if (isset($parameters['removePhotos'])) {
	for ($i = 0; $i < sizeof($parameters['removePhotos']); $i++) {
		API\removePetPhoto($parameters['removePhotos'][$i]);
		unlink(getDocRoot() . '/images/petPictures/' . $parameters['removePhotos'][$i] . '.jpg');
	}
}

//Router\sendTo(getRootUrl() . "/pet/" . $parameters['petId']);
