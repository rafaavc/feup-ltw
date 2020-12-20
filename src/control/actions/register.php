<?php 

require_once(dirname(__FILE__)."/action.php");
require_once(dirname(__FILE__)."/../api/user.php");
require_once(dirname(__FILE__)."/../file_upload.php");

$parameters = initAction(['name', 'username', 'password', 'birthdate', 'mail', 'description']);
echo var_dump($_FILES);
if (!isset($_FILES['profilePhoto']) || $_FILES['profilePhoto']['tmp_name'] == '') {
    Router\errorBadRequest("You didn't give a profile image.");
}
if (!preg_match("/^([a-zA-Z0-9]+(_|\.))*[a-zA-Z0-9]+@([a-zA-Z0-9]+\.)*[a-zA-Z]+$/", $parameters['mail'])) {
    Router\errorBadRequest("You didn't give a correct email.");
}
if (!preg_match("/^[a-zA-Z0-9]+((_|\.)[a-zA-Z0-9]+)*$/", $parameters['username'])) {
    Router\errorBadRequest("You didn't give a valid username.");
}
if (strtotime($parameters['birthdate']) > getYearsAgo(18) || strtotime($parameters['birthdate']) < getYearsAgo(100)) {
    Router\errorBadRequest("You didn't give a correct birthdate.");
}
if (strlen($parameters['password']) < 8) {
    Router\errorBadRequest("You didn't give a large enough password.");
}


$profilePhoto = $_FILES['profilePhoto'];
$tmpPath = $_FILES['profilePhoto']['tmp_name'];
if (!isJPGImage($tmpPath)) {
    Router\errorBadRequest("You didn't give a jpg image.");
}
try {
    $userId = API\register($parameters['name'], $parameters['username'], $parameters['password'], $parameters['birthdate'], strtolower($parameters['mail']), $parameters['description']);
} catch(Exception $e) {
    Router\errorBadRequest();
}
$realPath = getDocRoot()."/images/user_profile_pictures/".$userId.".jpg";
move_uploaded_file($tmpPath, $realPath);


if ($userId != false) {
    $_SESSION['username'] = $parameters['username'];
    Router\sendTo(getRootURL());
} else {
    Router\errorBadRequest();
}
?>

