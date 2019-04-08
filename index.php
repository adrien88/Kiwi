
<?php

ini_set('display_errors','1');

spl_autoload_register(function ($class) {
    include 'includes/' . $class . '.class.php';
});

echo '<br>Tests unitaires : <br><pre>';

$BDD = new EPDO2();

echo $BDD->insert([
    'id_owner'=>1,
    'url'=>'exemple',
    'title'=>'exemple n°1',
    'description'=>'exemple',
    'content'=>'exemple',
    'publication'=>time(),
    'thumbnail'=>'',
    'keywords'=>'',
],'pages');

echo $BDD->update(['title'=>'exemple n°4','url'=>'exemple2'],['url'=>'exemple']).'<br>';
echo $BDD->delete(['url'=>'exemple']).'<br>';

print_r($BDD->query('SELECT url,title FROM pages'));




echo '</pre><br>/pre';

// associate
// EPDO::getInstance()->getTable('pages');
//
// print_r(EPDO::do()->insert([
//     'id_owner'=>1,
//     'url'=>'exemple',
//     'title'=>'exemple n°1',
//     'description'=>'exemple',
//     'content'=>'exemple',
//     'publication'=>time(),
//     'thumbnail'=>'',
//     'keywords'=>'',
// ]));
//
// echo '<br>';
// print_r(EPDO::do()->delete(['url'=>'exemple2']));
// echo '<br>';
// print_r(EPDO::do()->query('SELECT title FROM pages WHERE url=\'exemple\''));
// echo '<br>';
// print_r(EPDO::do()->update(['url'=>'exemple2','title'=>'exemple n°2'],['url'=>'exemple']));
// echo '<br>';
// print_r(EPDO::do()->query('SELECT title FROM pages WHERE url=\'exemple2\''));
// echo '<br><pre>';
// print_r(EPDO::do()->getStruct());
// echo '</pre>';


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
//
//
