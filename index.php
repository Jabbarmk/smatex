<?php
session_start();

require_once 'config/config.php';
require_once 'app/core/Database.php';
require_once 'app/core/Model.php';
require_once 'app/core/Controller.php';
require_once 'app/core/Router.php';

// Simple autoloader for models and controllers if needed
spl_autoload_register(function ($class_name) {
    if (file_exists('app/models/' . $class_name . '.php')) {
        require_once 'app/models/' . $class_name . '.php';
    } elseif (file_exists('app/controllers/' . $class_name . '.php')) {
        require_once 'app/controllers/' . $class_name . '.php';
    }
});

$app = new Router();
