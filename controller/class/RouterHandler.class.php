<?php

/**
*   Routes are actualy saved on a file.
*   This manager is used to save or load a route
*/
class RouterHandler {

    /**
    *  Get
    * @param : url getted
    * @return : targeted file or class
    */
    final public static function get($url){
        $DATA = parse_ini_file('routes.ini');

        if (isset($DATA[$url])) {
            return $DATA[$url];
        }
        return false;
    }

    /**
    * Set
    * @param : array [ url => 'alias' ]
    * @return : bool
    */
    final public static function set($url,$alias)
    {
        if (
            preg_match('#([a-z0-9-_.]{3,}/?)+#i',$url) &&
            preg_match('#([a-z0-9-_.]{3,}/?)+#i',$alias) &&
            (file_exists($alias) || class_exists($alias))
        ){
            // extract saved routes
            $DATA = parse_ini_file('routes.ini');

            // remove url if no alias
            if ( empty($alias) ) {
                unset($DATA[$url]);
            }

            // or merge new url
            else {
                $DATA = array_merge($DATA,$opts);
            }

            // saving routes
            $str='';
            foreach ( $DATA as $url => $alias ){
                $str.= "$url = '$alias'\n";
            }
            file_put_contents('routes.ini',$str);
            return true;
        }
        return false;
    }
}
