
<?php

ini_set('display_errors','1');

spl_autoload_register(function ($class) {
    include 'includes/' . $class . '.class.php';
});
// include 'unit_test/EPDO.php';

// Definir un contenu
$article = new htmlObj('article',[],[
            'Voici mon super article.<br> Please : visit my site : ',
            new htmlObj('a', ['href'=>'https://boilley.info'], ['boilley.info']),
        ]);

// Definir un footer personalisée
$footer = new htmlObj('footer',['id'=>'footer'],[
            new htmlObj('h2', [], ['Boilley Adrien © 2019 GPL V.3']),
            new htmlObj('p', [], ['I\'m the best ']),
        ]);

// Creer une page par defaut
$obj = htmlObj::html5([
    'dir'=>'ltr', 'lang'=>'fr-FR','charset'=>'utf8','bootstrap'=>0,
    'title'=>'Warrior',
    'css'=>['test.css'],
    'header'=>['title'=>'Warrior','description'=>'graaaaaa'],
    'footer'=>['title'=>'Boilley Adrien © 2019 GPL V.3','description'=>'I\'m the best !']
    ],
    [$article,$footer]);

// afficher la page
echo $obj->html();
