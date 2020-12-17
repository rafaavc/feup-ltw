<?php 

require_once(dirname(__FILE__)."/action.php");
require_once(dirname(__FILE__)."/../api/pet.php");
require_once(dirname(__FILE__)."/../api/user.php");
require_once(dirname(__FILE__)."/../file_upload.php");

$parameters = initAction(['name', 'birthdate', 'location', 'description', 'specie', 'race', 'size', 'color', 'profilePhoto']);

function validateSelectParam($param, $required) {
    global $parameters;
    if ($required && $parameters[$param] == -1) {
        Session\setMessage(Session\error(), "You didn't select a ".$param.".");
        Router\sendBack();
    }
    if (!is_numeric($parameters[$param])) {
        if (!(preg_match('/^[a-zA-Z ]+$/', $parameters[$param]) === 1)) {
            Session\setMessage(Session\error(), "Invalid new ".$param." name.");
            Router\sendBack();
        }
    }
}
// validation
validateSelectParam('specie', true);
validateSelectParam('color', true);
validateSelectParam('size', true);
validateSelectParam('race', false);

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

$petId = API\addPet(Session\getAuthenticatedUser()['id'], $parameters['name'], $parameters['birthdate'],$parameters['specie'],$parameters['race'],$parameters['size'], $parameters['color'], $parameters['location'], $parameters['description']); // create pet

for($i = 0; $i < sizeof($_FILES['photos']['name']); $i++) {
    $tmpPath = $_FILES['photos']['tmp_name'][$i];
    if ($tmpPath == "") continue;
    if (!isJPGImage($tmpPath)) {
        echo "not valid :(";
        continue;
    }
    $photoId = API\addPetPhoto($petId);
    $originalPath = "../../images/petPictures/".$photoId.".jpg";
    move_uploaded_file($tmpPath, $originalPath);

    if ($_FILES['photos']['name'][$i] == $parameters['profilePhoto']) {
        copy($originalPath, "../../images/petProfilePictures/".$petId.".jpg");
    }
}

Router\sendTo(getRootUrl()."/pets");

?>

