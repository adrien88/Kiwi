<?php

    $totalTime = 0;

    foreach (glob(__DIR__.'/*.php') as $filename) {

        $filename = basename($filename);

        if ($filename != basename(__FILE__)){

            $stime = microtime(1);

            include $filename;

            $etime = microtime(1);
            $mTime = (($etime - $stime));

            echo '- execution : '.$mTime.'.μs<sup>-1</sup><hr>';
        }

        $totalTime += $mTime;
    }

    echo 'Execution de tous les tests en : '.$totalTime.' .μs<sup>-1</sup><hr>';
