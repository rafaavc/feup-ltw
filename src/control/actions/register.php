<?php 

require_once(dirname(__FILE__)."/action.php");
require_once(dirname(__FILE__)."/../api/user.php");

$parameters = initAction(['name', 'username', 'password', 'birthdate', 'mail', 'description']);

if (API\register($parameters['name'], $parameters['username'], $parameters['password'], $parameters['birthdate'], $parameters['mail'], $parameters['description'])) {
    $_SESSION['username'] = $parameters['username'];
    Router\sendTo(getRootURL());
} else {
   //todo 
}
?>

