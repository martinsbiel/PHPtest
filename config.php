<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'cep');
define('DB_USER', 'root');
define('DB_PASS', 'root');

// autoloader setup
function autoLoader($class){
    $path = 'classes/' . $class;
    if(file_exists($path . '.php'))
        require_once $path . '.php';
}

spl_autoload_register('autoLoader');