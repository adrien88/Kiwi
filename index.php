
<?php

spl_autoload_register(function ($class) {
    include 'includes/' . $class . '.class.php';
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
echo '<br>';
echo '<br>';
print_r( $ePDO->Update('pages', ['url'=>'exemple3'], ['url'=>'exemple']) );
echo '<br>';


print_r($ePDO->Select('SELECT title FROM pages'));
