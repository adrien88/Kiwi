    <?php


    echo '<br>Tests unitaires EPDO : <br><pre>';

    $DB = Config::load('config.ini')['database'];
    $BDD = new EPDO($DB);
    echo 'test : '.$BDD->getBaseName('kiwi').'<br>';


    // asort($array);
    // print_r($array);

    // $texte = 'kiwiraisinbannanegdgsgsgsgqrtretayayatye';
    // $tl = strlen($texte);
    // $hex = substr(str_shuffle(md5(base64_encode($texte))),0,16);
    // $th = strlen("$hex");
    //
    // echo '<br>test : <br>- '.$texte.' : '.$tl.' <br>- '.$hex.' : '.$th.'<br><br>';
