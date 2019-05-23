
<?php

ini_set('display_errors','1');

spl_autoload_register(function ($class) {
    include 'includes/' . $class . '.class.php';
});

// Creer une page par defaut
$obj = new pageBuilder();
$obj -> html5(['dir'=>'ltr', 'lang'=>'fr-FR','charset'=>'utf8','title'=>'Warrior','css'=>['test.css']],'');

// Add header
$title = new htmlObj('h1',['id'=>'headerTitle'],['Boilley.info']);
$Desc = new htmlObj('p',['id'=>'headerDesc'],['Bienvenue sur mon site.']);
$obj -> addObject([$title,$Desc]);

// Add main
$title = new htmlObj('h2',['id'=>'title'],['Coucou les gens.']);
$Desc = new htmlObj('p',['id'=>'content'],['Vous allez biens ?']);
$obj -> addObject([$title,$Desc]);


// echo '<pre>'.print_r($obj->PAGE,1).'</pre>';

// echo $page ->html();
// afficher la page
echo $obj->render();



// vendredi matin 9h CCH conseil habita 1C rue frenel
