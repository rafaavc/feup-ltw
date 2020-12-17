<?php

require_once(dirname(__FILE__) . "/action.php");
require_once(dirname(__FILE__) . "/../api/pet.php");
require_once(dirname(__FILE__) . "/../api/user.php");

if (isset($_POST['removePhotos']))
	$parameters = initAction(['petId', 'name', 'location', 'description', 'removePhotos']);
else
	$parameters = initAction(['petId', 'name', 'location', 'description']);

$petId = API\updatePet($parameters['petId'], $parameters['name'], $parameters['location'], $parameters['description']); // update pet

for ($i = 0; $i < sizeof($_FILES['photos']['name']); $i++) {
	$tmpPath = $_FILES['photos']['tmp_name'][$i];
	if ($tmpPath == "") continue;
	if (finfo_file(finfo_open(FILEINFO_MIME_TYPE), $tmpPath) != 'image/jpeg') {
		echo "not valid :(";
		continue;
	}
	$photoId = API\addPetPhoto($parameters['petId']);
	$originalPath = "../../images/petPictures/" . $photoId . ".jpg";
	move_uploaded_file($tmpPath, $originalPath);

	print_r($_FILES);
	print_r($parameters);
	if ($_FILES['photos']['name'][$i] == $parameters['profilePhoto']) {
		copy($originalPath, "../../images/petProfilePictures/" . $petId . ".jpg");
	}
}

if (isset($parameters['removePhotos'])) {
	for ($i = 0; $i < sizeof($parameters['removePhotos']); $i++) {
		API\removePetPhoto($parameters['removePhotos'][$i]);
		unlink($_SERVER['DOCUMENT_ROOT'] . '/images/petPictures/' . $parameters['removePhotos'][$i] . '.jpg');
	}
}

Router\sendTo(getRootUrl() . "/pet/" . $parameters['petId']);
