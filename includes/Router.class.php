<?php

/**
*   Router class
*   Use it outter instance like : Router::auto();
*   It's work by aliasing : /my/url will ve associated to a controler file OR a
*
*/
class Router {

    public static function auto(string $defaultPath) : void
    {
        // get route function of structure
        // if can't : redirect user to landing page
        if (
            ($URL = self::coreGetRoute(['module','function','args'])) === false
        ){
            header('location:'.$defaultPath);
            exit;
        }

        // get alias and include controler (or other content)
        $alias = RouterHandler::get($URL['module']);

        if (file_exists('controler/'.$alias)) {
            include 'controler/'.$alias;
        }
        elseif(file_exists('view/cache/'.$alias)) {
            include 'view/cache/'.$alias;
        }
    }

    /** __________________________________________________________________________________
    * if isset $_GET
    * Return route array from $_GET[0]
    *
    * @param array : data structure array[]
    * ex : ( ['page','function', 'args'] )
    *
    * @return array
    * ex for : 'foo/bar/camel/case' will return :
    * array
    *   ['path'] = foo/bar/camel/case
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
            // to create HTML backs links easier
            $out['docRoot']='./';
            for ($i=1; $i <= count($parts); $i++) {
                $out['docRoot'].='../';
            }

            // organise args
            $keyargs = array_pop($struct);
            foreach ($struct as $elem){
                $out[$elem] = array_shift($parts);
            }
            $out[$keyargs]=$parts;

            return $out;
        }
        return false;
    }
}
