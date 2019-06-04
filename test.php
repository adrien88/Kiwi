<?php
ini_set('display_errors','1');

// tableau de donénes
$array = [
    'id'=>0, ['year' => '2017', 'title'=>'Truc'],
    'id'=>1, ['year' => '2018', 'title'=>'Muche'],
    'id'=>2, ['year' => '2017', 'title'=>'Chose'],
    'id'=>3, ['year' => '2019', 'title'=>'Machin']
];

// isoler chaque année dans un tableau pour éviter les doublons :
$print = [];
// Parcouris les lignes du tableau
foreach($array as $movie){
    // Ne garder que les sous-tableaux
    if (is_array($movie)){
        // si l'année n'est pas dans le tableau print, l'ajouter.
        if(!in_array($movie['year'], $print)){
            $print[] = $movie['year'];
        }
    }
}

// Trier par ordre croissant (par soucis d'élégants) :
asort($print);

// afficher les années :
foreach ($print as $year) {
    ?>
        <li> Année <a href="?year=<?=$year?>"> <?=$year?> </a></li>
    <?php
}

// affiche le tableau en préformaté : c'est plus facile à lire.
// echo '<pre>'.print_r($print,1).'</pre>';

// séparateur.
echo '<hr>';

// SI l'utilisateur à cliqué sur une année :
if (isset($_GET['year'])) {

    echo "<ul>";

    // Parcouris les lignes du tableau
    foreach($array as $movie){
        // Ne garder que les sous-tableaux
        if (is_array($movie)){
            // si l'année n'est pas dans le tableau print, l'ajouter.
            if ($_GET['year'] == $movie['year']) {
                ?>
                    titre : <?=$movie['title']?> - année <?=$movie['year']?><br>
                <?php
            }
        }
    }
    echo "</ul>";
}
// Sinon : l'inviter à le faire.
else {
    echo "Cliquez sur une année.";
}
