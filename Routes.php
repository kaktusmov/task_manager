<?php

namespace app;

use app\controllers\MainController;

class Routes {
    private static $routes = [
        '' => MainController::class.'.index',
        'create-task' => MainController::class.'.createTask',
        'tasks-list' => MainController::class.'.viewTasks',
        'login' => MainController::class.'.login',
        'logout' => MainController::class.'.logout',
        'task' => MainController::class.'.edit'
    ];

    public static function run()
    {
        $route = explode('?', $_SERVER['REQUEST_URI']);
        $route = explode("/", $route[0]);
        $route = $route[count($route)-1];
        if (isset(self::$routes[$route])) {
            $path = explode('.',self::$routes[$route]);
            $class = new $path[0]();
            $class->{$path[1]}();
        }
        else
            echo 'PAGE NOT FOUND';
    }
}

