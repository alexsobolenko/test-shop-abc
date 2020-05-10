<?php

namespace App;

/**
 * Class Router
 * @package App
 */
class Router
{
    private const DEFAULT_CONTROLLER = "HomeController";
    private const DEFAULT_ACTION = "index";

    public function start(): void
    {

        $uri = substr($_SERVER["REQUEST_URI"], 1);
        $routes = explode("/", $uri);
        $controllerName = self::DEFAULT_CONTROLLER;
        $actionName = self::DEFAULT_ACTION;

        if ("" !== $routes[0]) {
            $controllerName = sprintf("%sController", ucfirst($routes[0]));
        }

        if (!empty($routes[1])) {
            $actionName = $routes[1];
        }

        try {
            $controllerClass = sprintf("\\App\\%s", $controllerName);
            $controller = new $controllerClass;
            $controller->$actionName();
        } catch (\Exception $e) {
            header("Location: /error");
        }
    }
}
