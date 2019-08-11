<?php

/* User must be logged */
if(!is_logged()){
    header('location:./');
    exit;
} else {

    // Creer une page par defaut
    $obj = new PageBuilder();
    $obj -> html5(['dir'=>'ltr', 'lang'=>'fr-FR','charset'=>'utf8','title'=>'Dasboard','css'=>['test.css']],'');

    // Créer la navbar
    $navbar = new HtmlObj('nav',['id'=>'navbar'],[]);
    $navbar -> set_class(['col-12']);

    // Créer les onglets
    $tabsbar = new HtmlObj('nav',['id'=>'tabsbar'],[]);
    $tabsbar -> set_class(['col-12']);

    // Créer la frame
    $iframe = new HtmlObj('iframe',['src'=>''],[]);
    $iframe -> set_class(['col-12']);

    // Add header
    $obj -> elemBtBuilder([
            'tagname'=>'header', 'archi'=>$obj->getArchi(1,0),
            'elemnts' => [$navbar]
        ]);

    // Add header
    $obj -> elemBtBuilder([
            'tagname'=>'main', 'archi'=> $obj->getArchi(1,0),
            'elemnts' => [$tabsbar, $iframe]
        ]);

    echo $obj->render();



}
