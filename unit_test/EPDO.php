<?php

echo '<br>Tests unitaires : <br><pre>';

$BDD = new EPDO2();


if ($BDD->insert([
    'id_owner'=>1,
    'url'=>'exemple',
    'title'=>'exemple n°1',
    'description'=>'exemple',
    'content'=>'exemple',
    'publication'=>time(),
    'thumbnail'=>'',
    'keywords'=>'',
])){
    echo '<br><b>Insert success.</b><br>';
}

if ($BDD->update(['title'=>'exemple n°4','url'=>'exemple2'],['url'=>'exemple'])){
    echo '<br><b>Update success.</b><br>';
}

if ($BDD->delete(['url'=>'exemple'])){
    echo '<br><b>Delete success.</b><br>';
}

echo '<br><b>Content :<b> ';
print_r($BDD->query('SELECT url,title FROM pages'));

echo '</pre><br>';
