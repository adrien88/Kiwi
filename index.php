
<?php

ini_set('display_errors','1');

spl_autoload_register(function ($class) {
    include 'includes/' . $class . '.class.php';
});
// include 'unit_test/EPDO.php';

// Definir une entête
$header = new htmlObj('header',[],[
        new htmlObj('h1',[],['The Warrior ! ']),
        new htmlObj('p',[],['Coucou les gens']),
    ]);

// Definir un contenu
$article = new htmlObj('article',[],[
            'Voici mon super article.<br> Please : visit my site : ',
            new htmlObj('a', ['href'=>'https://boilley.info'], ['boilley.info']),
        ]);
// Creer une page par defaut
$obj = htmlObj::html5([
    'title'=>'Warrior',
    'charset'=>'utf8',
    'css'=>['test.css'],
    ],
    [$header,$article]);
// afficher la page
echo $obj->html();
