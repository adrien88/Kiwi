<?php

class minifier{

    /**
    *   Auto mod : using by $_GET method to return minified code
    *
    *   @param void
    *   @return void
    */
    public static function auto() {

        // CSS minifier
        if(
            isset($_GET['CssFile'])
            && !empty($_GET['CssFile'])
            && file_exists($_GET['CssFile'])
        ){
            // get the file content
            $CSS = file_get_contents($_GET['CssFile']);
            // redeclare header HTTP
            header('Content-Type:text/css; charset=utf-8');
            echo self::css($CSS);
            exit;
        }

        // JS minifier
        if(
            isset($_GET['JsFile'])
            && !empty($_GET['JsFile'])
            && file_exists($_GET['JsFile'])
            ){
            // get the file content
            $JS = file_get_contents($_GET['JsFile']);
            // redeclare header HTTP
            header('Content-Type:application/javascript; charset=utf-8');
            echo self::js($JS);
            exit;
        }
    }


    /**
    *   minifier CSS
    *   @param css_code:string
    *   @return css_minified:string
    */
    public static function css($CSS){
        // deletes newlines and tab
        $CSS = preg_replace("#\n|\t#",'',$CSS);
        //  Delete useless spaces
        $CSS = preg_replace("# {2,}#",'',$CSS);
        // delete comments
        $CSS = preg_replace("#/\*[a-z0-9-_. :;/\#\+=]+\*/#i",'',$CSS);
        // Send CSS
        return $CSS;
    }

    /**
    *   minifier JS
    *   @param js_code:string
    *   @return js_minified:string
    */
    public static function js($JS){
        // deletes newlines and tab
        $JS = preg_replace("#\n|\t#",'',$JS);
        //  Delete useless escpaces
        $JS = preg_replace("# {2,}#",'',$JS);
        // delete comments
        $JS = preg_replace("#/\*[a-z0-9-_.=/\\\*\+ ]+\*/#i",'',$JS);
        // Send CSS
        return $JS;
    }

    /**
    *   minifier XML
    *   @param xml_code:string
    *   @return xml_minified:string
    */
    public static function xml($XML){
        // deletes newlines and tab
        $XML = preg_replace("#\n|\t#",'',$XML);
        //  Delete useless escpaces
        $XML = preg_replace("# {2,}#",'',$XML);
        // delete comments
        $XML = preg_replace("#<\!--[a-z0-9-_.=/\\\*\+ ]+-->#i",'',$XML);
        // Send CSS
        return $XML;
    }
}

?>
