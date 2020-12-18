<?php 

require_once(dirname(__FILE__)."/action.php");
require_once(dirname(__FILE__)."/../api/user.php");

$parameters = initAction(['username', 'password']);

if (API\login($parameters['username'], $parameters['password'])) {
    $_SESSION['username'] = $parameters['username'];
    // no need for success message
    //Session\setMessage(Session\success(), 'User logged in successfully.');
    Router\sendTo(getRootURL());
} else {
    Session\setMessage(Session\error(), 'Wrong username or password.');
    Router\sendBack();
}

?>

