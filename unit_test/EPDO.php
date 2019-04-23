<?php

echo '<br>Tests unitaires EPDO : <br><pre>';

$BDD = new EPDO2();

if ($BDD->insert([
    'id_owner'=>1,
    'url'=>'exempledgdgsg',
    'title'=>'exemple n°1qgqgqdfg',
    'description'=>'exemplqqgfgqsfge',
    'content'=>'exemple',
    'publication'=>time(),
    'thumbnail'=>'',
    'keywords'=>'',
])){
    echo '<br><b>Insert success.</b><br>';
}

echo '<br><b>Content by url and title :<b> ';
print_r($BDD->query('SELECT url,title FROM pages'));

if ($BDD->update(['title'=>'exemple n°4','url'=>'exemple2'],['url'=>'exemple'])){
    echo '<br><b>Update success.</b><br>';
}

echo '<br><b>Content by url and title :<b> ';
print_r($BDD->query('SELECT url,title FROM pages'));


if ($BDD->delete(['url'=>'exemple'])){
    echo '<br><b>Delete success.</b><br>';
}

echo '<br><b>Content by url and title :<b> ';
print_r($BDD->query('SELECT url,title FROM pages'));

echo '</pre><br>';
