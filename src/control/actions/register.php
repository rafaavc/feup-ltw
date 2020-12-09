<?php 

require_once(dirname(__FILE__)."/action.php");

$parameters = initAction(['name', 'username', 'password', 'birthdate', 'mail', 'description']);

if (API\register($parameters['name'], $parameters['username'], $parameters['password'], $parameters['birthdate'], $parameters['mail'], $parameters['description'])) {
    $_SESSION['username'] = $parameters['username'];
    // no need for success message
    //Session\setMessage(Session\success(), 'User logged in successfully.');
    Router\sendTo(getRootURL());
} else {
   //todo 
}
?>

