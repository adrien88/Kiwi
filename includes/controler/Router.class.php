<?php

/**
* Router class
* Use it outter instance like : Router::auto();
* It's work by aliasing : /my/url will ve associated to a controler file OR a
*
*/
class Router {

    public static function auto( $targetype )
    {
        // get route
        $URL = self::coreGetRoute(['module','function','args']);

        switch ( $targetype )
        {
            case CLASSNAME:
                if (class_exists($URL['module'])){
                    $URL['module']::auto();
                }
            break;
            default:
                if (file_exists('controler/'.$URL['module'].'.php')){
                    include 'controler/'.$URL['module'].'.php';
                }
            break;
        }
    }

    /**
    * if isset $_GET
    * Return route array from $_GET[0]
    *
    * @param : data structure array[]
    * ex : ( ['page','function', 'args'] )
    *
    * @return : structured path
    * ex for : 'foo/bar/camel/case' will return :
    * array
    *   ['docroot'] = ./../../../
    *   ['page'] = 'foo'
    *   ['function'] = 'bar'
    *   ['args'] = [ 'camel' , 'case' ]
    */

    public static function coreGetRoute( $struct=['module','funct','args'] )
    {
        if (isset($_GET) && !empty($_GET)) {

            //  get path in array $parts
            $out=[];
            $out['path']=array_keys($_GET)[0];
            $parts=explode('/',$out['path']);

            // create docroot ressource
            // to create an HTML back link easier
            $out['docRoot']='./';
            for ($i=1; $i <= count($parts); $i++) {
                $out['docRoot'].='../';
            }

            //
            $keyargs = array_shift($struct);
            foreach ($struct as $elem){
                $out[$elem] = array_shift($parts);
            }
            $out[$keyargs]=$parts;

            // $out['page']=str_replace('_','.',array_shift($parts));
            // $out['obj']=array_shift($parts);
            // $out=array_merge($out,$parts);
            // RouterManager::get($url);

            return $out;
        }
        return false;
    }
}

/**
* This manager is used to save or load a route
*/
class RouterManager {

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
    * @param : array [ url => '', name => '', => 'alias' ]
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
