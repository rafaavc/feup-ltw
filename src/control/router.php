<?php

namespace Router;

function handle() {
    $req; 
    if (php_sapi_name() == 'cli-server') {   // php cli-server
        $req = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $req = $req != "/" ? substr($req, 1, strlen($req)-1) : "index";

        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$req)) { // serves the file
            return false;
        }
    } else {   // apache
        $req = isset($_GET['req']) ? $_GET['req'] : "index";
    }

    hitTarget(findTarget($req));
    return true;
}

function findTarget($req) {
    $routes = json_decode(file_get_contents('control/routes.json'));

    if (property_exists($routes, $req) && !(strpos($req, ":") !== false)) {
        return $routes->$req;
    }

    $reqParts = explode("/", $req);
    $inputVars = null;
    $target = null;

    foreach($routes as $route => $currentTarget) {
        $routeParts = explode("/", $route);

        if (sizeof($reqParts) === sizeof($routeParts)) {   // same number of parts
            $inputVars = array();
            $foundMatch = true;

            foreach($routeParts as $i => $part) {
                if ($part[0] === ':') {    // if found input variale
                    $inputVars[substr($part, 1, strlen($part)-1)] = $reqParts[$i];   // store it
                } else if ($part !== $reqParts[$i]) {   // if the parts don't match
                    $foundMatch = false;
                    break;
                }
            }

            if ($foundMatch) {
                $target = $currentTarget;
                break;
            }
        }
    }

    if ($target === null) error404();   // if no match is found, 404 ERROR

    foreach($inputVars as $key => $value) {   // stores the input vars in the globals
        $GLOBALS[$key] = $value;
    }

    return $target;
}

function hitTarget($target) {
    if (property_exists($target, 'js')) {
        $GLOBALS['js'] = $target->js;
    }
    hitDestination($target->destination);
}

function hitDestination($destination) {
    require_once($destination);
}

function getPostParameter($name) {
    return isset($_POST[$name]) ? $_POST[$name] : null;
}

function getPostParameters($names) {
    $res = array();
    foreach ($names as $name) {
        $value = getPostParameter($name);
        if ($value == null) return null;
        $res[$name] = $value;
    }
    return $res;
}

function error404() {
    http_response_code(404);
    hitDestination("pages/404.php");
    exit();
}

function sendTo($location) {
    header("Location: ".$location);
    exit();
}

function sendBack() {
    if (!isset($_SERVER['HTTP_REFERER'])) error404();
    sendTo($_SERVER['HTTP_REFERER']);
    exit();
}



?>


