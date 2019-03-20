
<?php

ini_set('display_errors','1');

spl_autoload_register(function ($class) {
    include 'includes/' . $class . '.class.php';
});


// $ePDO = new ePDO();
// ePDO::connect();

print_r(EPDO::getInstance()->Insert('pages',[
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








// class boolean {
//     final public static function true(){
//         if(true){
//             return true;
//         }
//     }
//     final public static function false(){
//         if(false){
//             return false;
//         }
//     }
// }
//
//
// class kamikaze {
//     public function __construct(){
//         $this->__destruct();
//     }
//     public function __destruct(){
//         return 0;
//     }
// }
