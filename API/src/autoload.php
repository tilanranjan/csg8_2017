<?php

spl_autoload_register(function($class){
    $modulesPath = __DIR__ . "/modules/{$class}.php";
    $modelsPath = __DIR__ . "/models/{$class}.php";
    $handlerPath = __DIR__ . "/database/Handlers/{$class}.php";

    if(file_exists($modulesPath)){
        require_once $modulesPath;
    } 
    
    if (file_exists($modelsPath)){
        require_once $modelsPath;
    }

    if(file_exists($handlerPath)){
        require_once $handlerPath;
    }
});

?>