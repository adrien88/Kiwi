<?php

$array = [
    'sitetitle' => "Portfolio",
    'sitedescription' => "Portfolio personnel",
    'landing' => 'accueil.php',
    'database_1' => [
        'host' => 'localhost',
        'name' => 'kiwi'
    ],
    'database_2' => [
        'host' => 'localhost',
        'name' => 'lemon'
    ],
];

if ( Config::save('test.ini', $array)){
    print_r(Config::load('test.ini'));
}

unlink('test.ini');
