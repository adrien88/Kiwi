    <?php

    echo '<br>Tests unitaires EPDO : <br><pre>';

    $BDD = new EPDO2();

    echo "pages exists ? bool : >>> ".$BDD->ifTableExists('pages');

    $BDD->getTable('pages');

    // $rand = rand(1000,9999);
    // if ($BDD->insert([
    //     'id_owner'=>1,
    //     'url'=>'exemple n'.$rand,
    //     'title'=>'exemple n°'.$rand
    // ])){
    //     echo '<br><b>Insert success.</b><br>';
    // }

    echo '<br><b>Content by url and title :<b> ';
    print_r($BDD->query('SELECT url,title FROM pages'));

    print_r($BDD->getStruct('url'));



    // if ($BDD->update(['title'=>'exemple n°4','url'=>'exemple2'],['url'=>'exemple'])){
    //     echo '<br><b>Update success.</b><br>';
    // }
    // echo '<br><b>Content by url and title :<b> ';
    // print_r($BDD->query('SELECT url,title FROM pages'));
    //
    // if ($BDD->delete(['url'=>'exemple'])){
    //     echo '<br><b>Delete success.</b><br>';
    // }
    //
    // echo '<br><b>Content by url and title :<b> ';
    // print_r($BDD->query('SELECT url,title FROM pages'));

    echo '</pre><br>';
