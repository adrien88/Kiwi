
<?php

spl_autoload_register(function ($class) {
    include 'includes/controler/' . $class . '.class.php';
});

$ePDO = new ePDO();

print_r($ePDO->Insert('pages',[
    'id_owner'=>1,
    'url'=>'exemple',
    'title'=>'exemple',
    'description'=>'exemple',
    'content'=>'exemple',
    'publication'=>time(),
    'thumbnail'=>'',
    'keywords'=>'',
]));


print_r($ePDO->Select('SELECT title FROM pages'));
