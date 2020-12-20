<?php 

require_once(dirname(__FILE__)."/action.php");
require_once(dirname(__FILE__)."/../api/pet.php");
require_once(dirname(__FILE__)."/../api/user.php");
require_once(dirname(__FILE__)."/../file_upload.php");

$parameters = initAction(['name', 'birthdate', 'location', 'description', 'specie', 'race', 'size', 'color', 'profilePhoto']);


function validateSelectParam($param, $required, $parameters) {
    if ($required && $parameters[$param] == -1) {
        Session\setMessage(Session\error(), "You didn't select a ".$param.".");
        Router\sendBack();
    }
    if (!is_numeric($parameters[$param])) {
        if (!preg_match('/^[a-zA-Z]+( [a-zA-Z]+)*$/', $parameters[$param])) {
            Session\setMessage(Session\error(), "Invalid new ".$param." name.");
            Router\sendBack();
        }
    }
}

if (strtotime($parameters['birthdate']) < getYearsAgo(20) || strtotime($parameters['birthdate']) > strtotime(date("Y-m-d"))) {
    Router\errorBadRequest("The pet's birthdate is not valid.");
}

if (!preg_match('/^[a-zA-Z]+( [a-zA-Z]+)*$/', $parameters['location'])) {
    Router\errorBadRequest("The pet's location is not valid.");
}

// validation
validateSelectParam('specie', true, $parameters);
validateSelectParam('color', true, $parameters);
validateSelectParam('size', true, $parameters);
validateSelectParam('race', false, $parameters);

// adding selects
if (!is_numeric($parameters['specie'])) {
    $parameters['specie'] = API\addSpecie($parameters['specie']);
}
if (!is_numeric($parameters['color'])) {
    $parameters['color'] = API\addColor($parameters['color']);
}
if (!is_numeric($parameters['size'])) {
    $parameters['size'] = API\addSize($parameters['size']);
}
if (!is_numeric($parameters['race'])) {
    $parameters['race'] = API\addSpecieRace($parameters['specie'], $parameters['race']);
}

if ($parameters['race'] == -1) {
    $parameters['race'] = null;
} else {
    $parameters['specie'] = null; // specie is already in the race
}

// checks if all files sent are valid
$foundProfilePhoto = false;
for($i = 0; $i < sizeof($_FILES['photos']['name']); $i++) {
    $tmpPath = $_FILES['photos']['tmp_name'][$i];
    $name = $_FILES['photos']['name'][$i];
    if ($tmpPath == "") continue;
    if (!isJPGImage($tmpPath)) {
        Router\errorBadRequest("Invalid files were sent to the server.");
    }
    if ($name == $parameters['profilePhoto']) {
        $foundProfilePhoto = true;
    }
}
if (!$foundProfilePhoto) Router\errorBadRequest("The profile photo was not sent to the server.");

try {
    $petId = API\addPet(Session\getAuthenticatedUser()['id'], $parameters['name'], $parameters['birthdate'],$parameters['specie'],$parameters['race'],$parameters['size'], $parameters['color'], $parameters['location'], $parameters['description']); // create pet
} catch(Exception $e) {
    Router\errorBadRequest();
}

for($i = 0; $i < sizeof($_FILES['photos']['name']); $i++) {
    $tmpPath = $_FILES['photos']['tmp_name'][$i];
    if ($tmpPath == "") continue;
    $photoId = API\addPetPhoto($petId);
    $originalPath = getDocRoot()."/images/pet_pictures/".$photoId.".jpg";
    move_uploaded_file($tmpPath, $originalPath);

    if ($_FILES['photos']['name'][$i] == $parameters['profilePhoto']) {
        copy($originalPath, getDocRoot()."/images/pet_profile_pictures/".$petId.".jpg");
    }
}

Router\sendTo(getRootUrl()."/pets");

?>

