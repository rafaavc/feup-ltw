<?php

class Router {
    public static function handle() {
        $routes = json_decode(file_get_contents('controller/routes.json'));
        echo print_r($routes);
        $value = 'add-pet';
        echo print_r($routes->pets->$value);
        echo property_exists($routes, 'test') ? 'hey' : 'ho';
        $route = isset($_GET['route']) ? $_GET['route'] : 'index';
        if ($route === null) {
            // render index
        } else {
            // deal with route
        }
        echo print_r(explode('/', $route));
    }

    private static function getPageFilePath() {

    }
}

?>


