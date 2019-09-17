<?php

/**
 *   Router class
 *   Use it outter instance like#  Router::auto( [$aprams] );
 *   
 *
 */

class Router {

    /**
     *  auto ( string $url)
     * 
     *  @param string '/truc/exemple.html',      
     *  @return void
     */
    public static function auto($landing = '/home.html')
    {

        // get route function of structure
        // if can't : redirect user to landing page
        if (
            ($url = array_key_first($_GET)) === null
        ){
            header('location:'.$defaultPath);
            exit;
        } 
        else {
            $alias = RouterHandler::get($url);
            if (file_exists($alias)) {
                include $alias;
            }
        }
    }

    /** 
     *   routeByClass
     *   
     *   @param string url
     *   @return void
     */
    public function routeByClass ($url)
    {
        $class = array_shift($url);
        $method = array_shift($url);
        $args = $url;

        if (class_exists($class) && !array_key_exists($class, $forbidden)) {

            if (method_exists($InstanceAuto, $method)) {
                $args = $args ?? [];
                $InstanceAuto::$method($args);
            }
            
            else {
                echo 'Please give me a method to call like METHOD=theMethodName!';   
                exit;
            }

        } 
        else {
            echo 'Please give me a class to instance like CLASS=MyClassName !\n';   
            exit;
        }
    }


}
