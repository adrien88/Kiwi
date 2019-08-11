
<?php

// namespace kiwi\;

ini_set('display_errors','1');

spl_autoload_register(function ($class) {
    include 'includes/' . $class . '.class.php';
});

include 'unit_test/AUTO.php';
