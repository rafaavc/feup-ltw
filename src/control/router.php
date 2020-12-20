<?php

namespace Router;
use API;
use Session;

function handle() {
    $req = "";
    if (php_sapi_name() == 'cli-server') {   // php cli-server
        $req = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        //$ext = pathinfo($req, PATHINFO_EXTENSION);
        /*if ($ext != "" && file_exists($_SERVER["SCRIPT_FILENAME"])) {
            $mimeType = "";
            if ($ext == "js") $mimeType = "text/javascript";
            else if ($ext == "jpg" || $ext == "jpeg") $mimeType = "image/jpeg";
            else if ($ext == "css") $mimeType = "text/css";
            else {
                return false;
            }

            /*header("Content-Type: ".$mimeType);
            readfile($_SERVER["SCRIPT_FILENAME"]);
            exit();
        }*/
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

$GLOBALS['API_REQUEST_FILE'] = false;

function isAPIRequest($file) {
    if ($file == null) return $GLOBALS['API_REQUEST_FILE'] != false;
    return str_replace($_SERVER['DOCUMENT_ROOT'], getRootUrl(), $file) == $GLOBALS['API_REQUEST_FILE'];
}

function setAPIRequestFile($file) {
    $GLOBALS['API_REQUEST_FILE'] = getRootUrl().'/'.$file;
}

function findTarget($req) {
    $routes = json_decode(file_get_contents('control/routes.json'));

    $reqParts = explode("/", $req);
    if ($reqParts[0] == "api") $GLOBALS['API_REQUEST_FILE'] = true;

    if (property_exists($routes, $req) && !(strpos($req, ":") !== false)) {
        if ($GLOBALS['API_REQUEST_FILE']) setAPIRequestFile($routes->$req->destination);
        return $routes->$req;
    }

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

    if ($GLOBALS['API_REQUEST_FILE']) setAPIRequestFile($target->destination);

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

function error404() {
    http_response_code(404);
    if (!isAPIRequest(null)) header("Location: ".getRootUrl()."/404");
    exit();
}

function httpError($code, $message, $valueFieldName = "value", $msgFieldName = "error") {
    http_response_code($code);
    Session\setMessage(Session\error(), $code." ".$message);
    if (!isAPIRequest(null)) sendBack();
    else echo json_encode(array($valueFieldName => false, $msgFieldName => $code." ".$message));
    exit();
}

function errorForbidden($msg = null, $valueFieldName = "value", $msgFieldName = "error") {
    httpError(403, $msg == null ? "Forbidden request." : $msg, $valueFieldName, $msgFieldName); 
}

function errorUnauthorized($msg = null, $msgFieldName = "error") {
    httpError(401, $msg == null ? "Unauthorized request." : $msg, $valueFieldName, $msgFieldName);
}

function errorBadRequest($msg = null, $msgFieldName = "error") {
    httpError(400, $msg == null ? "Bad request." : $msg, $valueFieldName, $msgFieldName);
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