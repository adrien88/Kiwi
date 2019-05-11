    <?php

    echo '<br>Tests unitaires EPDO 3 : <br><pre>';


    $DB = Config::load('config.ini')['database'];
    $BDD = new EPDO3($DB);

    print_r('<br>dbname : <b>'.$BDD->getDBname().'</b><br>');
    print_r($BDD->tableList());

    print_r('<br>table : <b>pages</b><br>');
    $BDD -> getTableName('pages');
    print_r($BDD->getStruct());

    print_r('<br>table : <b>select * from pages</b><br>');
    print_r($BDD->query('SELECT * FROM pages'));

    print_r('<br>table : <b>insert</b><br>');

    $DB = [ 'UID' => 1, 'url' => 'exemple', 'title' => 'exemple', 'description' => ''  ];
    print_r($BDD->insert($DB));

    echo '</pre><br>';
