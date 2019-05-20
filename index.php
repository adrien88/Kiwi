
<?php

ini_set('display_errors','1');

spl_autoload_register(function ($class) {
    include 'includes/' . $class . '.class.php';
});


// Definir un contenu
$article = new htmlObj('article',[
        'class'=>'container'
    ],[
        'Voici mon super article.<br> Please : visit my site : ',
        new htmlObj('a', ['href'=>'https://boilley.info'], ['boilley.info']),
    ]);


// Creer une page par defaut
$obj = pageBuilder::html5(['dir'=>'ltr', 'lang'=>'fr-FR','charset'=>'utf8','bootstrap'=>'container','title'=>'Warrior',
'css'=>['test.css'],
'header'=>['title'=>'Warrior','description'=>'graaaaaa'],
'footer'=>['title'=>'Boilley Adrien © 2019 GPL V.3','description'=>'I\'m the best !']],[$article]);

// afficher la page
echo $obj->html();
