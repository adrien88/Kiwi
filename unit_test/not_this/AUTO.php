<?php

    $totalTime = 0;

    echo '<h2>Execution des tests unitaires automatisés</h2>';

    foreach (glob(__DIR__.'/*.php') as $filename) {

        $filename = basename($filename);

        if ($filename != basename(__FILE__)){
            $stime = microtime(1);

            include $filename;

            $etime = microtime(1);
            $mTime = round(($etime - $stime)*1000, 4);

            echo 'Execution : '.$filename.' : en : '.$mTime.'.ms<sup>-1</sup><hr>';
            $totalTime += $mTime;
        }
    }

    echo '<h3>Execution de tous les tests en : '.$totalTime.' .ms<sup>-1</sup></h3><hr><br>';
