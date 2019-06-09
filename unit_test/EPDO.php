    <?php


    echo '<br>Tests unitaires EPDO : <br><pre>';

    $DB = Config::load('config.ini')['database'];
    $BDD = new EPDO($DB);
    echo 'test : '.$BDD->getBaseName('kiwi').'<br>';
