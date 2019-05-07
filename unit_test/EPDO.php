    <?php

    echo '<br>Tests unitaires EPDO 3 : <br><pre>';


    $DB = Config::load('config.ini')['database'];
    $BDD = new EPDO3($DB);
    print_r('dbname : <b>'.$BDD->getDBname().'</b><br>');
    print_r($BDD->tableList());
    $BDD -> getTableName('pages');
    print_r($BDD->getStruct('url'));
    print_r($BDD->query('SELECT * FROM pages'));

    echo '</pre><br>';
