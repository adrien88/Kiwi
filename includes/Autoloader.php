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
        include 'includes/' . $classname . '.class.php';
    }
}
