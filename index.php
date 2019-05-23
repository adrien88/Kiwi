
<?php

$mtime = microtime(1);

ini_set('display_errors','1');

spl_autoload_register(function ($class) {
    include 'includes/' . $class . '.class.php';
});


// Creer une page par defaut
$obj = new pageBuilder();
$obj -> html5(['dir'=>'ltr', 'lang'=>'fr-FR','charset'=>'utf8','title'=>'Warrior','css'=>['test.css']],'');

// Créer le titre
$title = new htmlObj('h1',['title'=>'Waouh'],['Boilley.info']);
$title -> set_class(['col-12']);

// Description de titre
$desc = new htmlObj('p',[],['Bienvenue sur mon site.']);
$desc -> set_class(['col-12']);

// Add header
$obj -> elemBtBuilder([
        'tagname'=>'header', 'archi'=> $obj->getArchi(1,0),
        'elemnts' => [$title, $desc]
    ]);

// Description de titre
$p[] = new htmlObj('p',[],['Cette page à été générée par PHP suivant une architecture objet.']);
$p[] = new htmlObj('p',[],['J\'espère que vous la trouverez à votre goût.']);
$article = new htmlObj('article',['id'=>'article'],$p);
$article -> set_class(['col-12','article']);

// Add header
$obj -> elemBtBuilder([
        'tagname'=>'main', 'archi'=> $obj->getArchi(1,0),
        'elemnts' => [$article]
    ]);

// Créer le titre de pied de page
$title = new htmlObj('h2',['id'=>'footer-title'],['boilley adrien © 2019']);
$title -> set_class(['col-12']);

// Description du contenu de pied de page
$a1 = new htmlObj('p',[],['Mentions légales.']);
$a1 -> set_class(['col-lg-4']);
$a2 = new htmlObj('p',[],['Contactez moi.']);
$a2 -> set_class(['col-lg-4']);
$a3 = new htmlObj('p',[],['Autres données']);
$a3 -> set_class(['col-lg-4']);

// Add header
$obj -> elemBtBuilder([
        'tagname'=>'footer', 'archi'=> $obj->getArchi(1,0),
        'elemnts' => [$title, $a1, $a2, $a3]
    ]);

echo $obj->render();


// // Add main
// $title = new htmlObj('h2',['id'=>'title'],['Coucou les gens.']);
// $Desc = new htmlObj('p',['id'=>'content'],['Vous allez biens ?']);


// echo '<pre>'.print_r($obj->PAGE,1).'</pre>';

// echo $page ->html();
// afficher la page



// vendredi matin 9h CCH conseil habita 1C rue frenel

echo '- execution : '.((microtime(1) - $mtime)/1000).' .ms<sup>-1</sup>';
