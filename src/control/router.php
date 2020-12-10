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
    $inputVars = array();
    $destination = "";
    $foundMatch = false;

    foreach($routes as $route => $target) {
        $routeParts = explode("/", $route);
        $foundMatch = true;

        if (sizeof($reqParts) === sizeof($routeParts)) {   // same number of parts
            $inputVars = array();
            $destination = $target;

            foreach($routeParts as $i => $part) {
                if ($part[0] === ':') {    // if found input variale
                    $inputVars[substr($part, 1, strlen($part)-1)] = $reqParts[$i];   // store it
                } else if ($part !== $reqParts[$i]) {   // if the parts don't match
                    $foundMatch = false;
                    break;
                }
            }

            if ($foundMatch) break;

        } else {
            $foundMatch = false;
        }
    }

    if (!$foundMatch) {   // if no match is found, 404 ERROR
        error404();
    }

    foreach($inputVars as $key => $value) {   // stores the input vars in the globals
        $GLOBALS[$key] = $value;
    }

    return $destination;
}

function hitTarget($target) {
    require_once($target);
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
    hitTarget("pages/404.php");
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


