<?php

spl_autoload_register(function ($class) {

    $baseDir = dirname(__DIR__) . '/';

    // convert namespace → folder path
    $relativeClass = str_replace('\\', '/', $class);

    $file = $baseDir . $relativeClass . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});