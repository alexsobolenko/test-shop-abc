<?php

namespace App;

class Router
{
    private const DEFAULT_CONTROLLER = "HomeController";
    private const DEFAULT_ACTION = "index";

    /**
     *  start routing
     */
    public function start(): void
    {

        $uri = substr($_SERVER["REQUEST_URI"], 1);
        $routes = explode("/", $uri);

        // default controller and action
        $controllerName = self::DEFAULT_CONTROLLER;
        $actionName = self::DEFAULT_ACTION;

        // get controller name
        if ($routes[0] != '') {
            $controllerName = ucfirst($routes[0])."Controller";
        }

        // get action name
        if (!empty($routes[1])) {
            $actionName = $routes[1];
        }

        try {
            $controllerClass = "\\App\\".$controllerName;
            $controller = new $controllerClass;
            $controller->$actionName();
        } catch (\Exception $e) {
            header("Location: /error");
        }
    }
}
