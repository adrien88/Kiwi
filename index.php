
<?php

// namespace kiwi\;

$mtime = microtime(1);

ini_set('display_errors','1');

spl_autoload_register(function ($class) {
    include 'includes/' . $class . '.class.php';
});

include 'unit_test/EPDO.php';
// include 'unit_test/PageBuilder.php';

echo '- execution : '.((microtime(1) - $mtime)/1000).' .ms<sup>-1</sup>';
