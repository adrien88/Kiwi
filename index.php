
<?php

ini_set('display_errors','1');

spl_autoload_register(function ($class) {
    include 'includes/' . $class . '.class.php';
});


include 'unit_test/EPDO.php';

// Config::save('test.ini',
//     [
//         'test'=>0,
//         'chose'=>false,
//         'truc'=>"/truc/machin.php",
//         'table'=>
//         [
//             "truc2"=> 'heu',
//             "truc3"=> 'heu',
//             "sadisme"=> [
//                 'test'=>true
//             ]
//         ]
//     ]);
//
// echo '<br><br><pre>'.print_r(Config::load('test.ini'),1).'</pre>';
//
