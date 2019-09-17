<?php

/**
 *   Routes are actualy saved on a file.
 *   This manager is used to save or load a route
 */
class RouterHandler {

     /**
     * Get
     *
     * @param : url getted
     * @return : targeted file or class
     */
    final public static function get($url){
        $DATA = parse_ini_file('controller/config/routes.ini');
        if (isset($DATA[$url])) {
            return $DATA[$url];
        }
        return null;
    }

    /**
     * Set
     *
     * @param : url string, alias : string
     * @return : bool
     */
    final public static function set($url,$alias)
    {
        if (
            preg_match('#([a-z0-9-_.]{3,}/?)+#i',$url) &&
            preg_match('#([a-z0-9-_.]{3,}/?)+#i',$alias) &&
            file_exists($alias)
        ){
            // extract saved routes
            $DATA = parse_ini_file('routes.ini');
            $DATA[$url] = $alias;

            // saving routes
            $str='';
            foreach ( $DATA as $url => $alias ){
                $str.= "$url = '$alias'\n";
            }
            file_put_contents('controller/config/routes.ini',$str);
            return true;
        }
        return false;
    }
}
