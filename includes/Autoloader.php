<?php

class Autoloader  {

    /**
    *   Add class register
    */
    static function register() : void
    {
        spl_autoload_register(array(__CLASS__,'autoload'));
    }

    /**
    *   Include file
    */
    static function autoload(string $classname) : void
    {
        $classname = str_replace('\\','/',$classname);
        $classpath = 'includes/' . $classname . '.class.php';

        if(file_exists($classpath)){
            require_once $classpath;
        }
    }
}
