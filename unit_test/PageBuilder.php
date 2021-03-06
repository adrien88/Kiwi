<?php

// Creer une page par defaut
$obj = new PageBuilder();
$obj -> html5(['dir'=>'ltr', 'lang'=>'fr-FR','charset'=>'utf8','title'=>'Warrior','css'=>['test.css']],'');

// Créer le titre
$title = new HtmlObj('h1',['title'=>'Waouh'],['Boilley.info']);
$title -> set_class(['col-12']);

// Description de titre
$desc = new HtmlObj('p',[],['Bienvenue sur mon site.']);
$desc -> set_class(['col-12']);

// Add header
$obj -> elemBtBuilder([
        'tagname'=>'header', 'archi'=> $obj->getArchi(1,0),
        'elemnts' => [$title, $desc]
    ]);

// Description de titre
$p[] = new HtmlObj('p',[],['Cette page à été générée par PHP suivant une architecture objet.']);
$p[] = new HtmlObj('p',[],['J\'espère que vous la trouverez à votre goût.']);
$article = new HtmlObj('article',['id'=>'article'],$p);
$article -> set_class(['col-12','article']);

// Add header
$obj -> elemBtBuilder([
        'tagname'=>'main', 'archi'=> $obj->getArchi(1,0),
        'elemnts' => [$article]
    ]);

// Créer le titre de pied de page
$title = new HtmlObj('h2',['id'=>'footer-title'],['boilley adrien © 2019']);
$title -> set_class(['col-12']);

// Description du contenu de pied de page
$a1 = new HtmlObj('p',[],['Mentions légales.']);
$a1 -> set_class(['col-lg-4']);
$a2 = new HtmlObj('p',[],['Contactez moi.']);
$a2 -> set_class(['col-lg-4']);
$a3 = new HtmlObj('p',[],['Autres données']);
$a3 -> set_class(['col-lg-4']);

// Add header
$obj -> elemBtBuilder([
        'tagname'=>'footer', 'archi'=> $obj->getArchi(1,0),
        'elemnts' => [$title, $a1, $a2, $a3]
    ]);

echo $obj->render();
