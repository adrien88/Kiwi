<?php

class pageBuilder {

    // use cache
    private $cache=null;

    public function __construct(array $opts = []){
        $this->loadCache($opts['cache']);
    }

    public function loadCache(array $opts){
        $cache=new cache(
            $opts['path'], ['DIR'=>$opts['DIR'],'TIMEOUT'=>$opts['TIMEOUT']]
        );
        if (($content = $cache->load()) !== false){
            echo $content;
            exit;
        }
    }

    /**  __________________________________________________________________________________
    *   Create HTML 5 Page with using optionnaly Bootstrap 4
    *   @param opts:array,content:mixed
    *   @return void
    */
    public static function html5(array $opts = null, $content=''){

        $head_content= [];
        $classBootstrap = '';
        $subBootst = '';
        $foot_javascript = [];

        // set head element
        $head_content[] = new htmlObj('title',[],['content'=> $opts['title'] ?? 'New page' ]);
        $head_content[] = new htmlObj('meta',[],['charset'=> $opts['charset'] ?? 'utf8' ]);
        $head_content[] = new htmlObj('meta',[
            'name'      =>  "viewport",
            'content'   =>  "width=device-width, initial-scale=1, shrink-to-fit=no"
        ],[]);

        // Get bootsrap librairies 4
        if(isset($opts['bootstrap'])) {
            $classBt = $opts['bootstrap'];
            $subBootst = 'row';

            $head_content[] = new htmlObj('link',[
                'rel'=>"stylesheet",
                'href'=>"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css",
                'integrity'=>"sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm",
                'crossorigin'=>"anonymous"
            ],[]);
            $foot_javascript[] = new htmlObj('script',[
                'src'=>"https://code.jquery.com/jquery-3.2.1.slim.min.js",
                'integrity'=>"sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN",
                'crossorigin'=>"anonymous"
            ],[]);
            $foot_javascript[] = new htmlObj('script',[
                'src'=>"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js",
                'integrity'=>"sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q",
                'crossorigin'=>"anonymous"
            ],[]);
            $foot_javascript[] = new htmlObj('script',[
                'src'=>"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js",
                'integrity'=>"sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl",
                'crossorigin'=>"anonymous"
            ],[]);
        }

        // Get CSS Files from list
        if (isset($opts['css']) && is_array($opts['css'])){
            foreach ($opts['css'] as $file) {
                $head_content[] = new htmlObj(
                    'link', [
                        'rel'  =>   'stylesheet',
                        'type '=>   'text/css',
                        'href' =>   $file
                    ], []
                );
            }
        }

        //  Get JS Files from list
        if (isset($opts['js']) && is_array($opts['js'])){
            foreach ($opts['js'] as $file) {
                $foot_javascript[] = new htmlObj(
                    'script', [
                        'type' =>   'application/javascript',
                        'src'  =>   $file
                    ], []
                );
            }
        }

        ## ASSEMBLY HTML HEAD
        $head = new htmlObj('head',[],$head_content);

        ## ASSEMBLY HTML HEADER
        $header = null;
        $header = self::headerify($opts['header'],$classBt)->html();
        # ASSEMBLY AUTO FOOTER
        $footer = null;
        $footer = self::footerify($opts['footer'],$classBt)->html();

        ## ASSEMBLY HTML BODY
        $body = new htmlObj('body',[],[$header,$content,$footer,$foot_javascript]);
        $html = new htmlObj(
            'html',
            ['lang'=> $opts['lang'] ?? 'en-EN','dir'=> $opts['dir'] ?? 'ltr'],
            [$head,$body]
        );

        # return page object
        return new htmlObj('!DOCTYPE html',[],[$html]);
    }


    /**  __________________________________________________________________________________
    *   Return HTML formated string from header
    *   @param opts:array
    *   @return footer:object
    */
    public function headerify(array $opts = [], $classBootstrap = '') : object
    {
        # ASSEMBLY AUTO HEADER
        if(isset($opts)) {
            if(isset($opts['title'])) {
                $title = new htmlObj('h1',[],[$opts['title']]);
            }
            if(isset($opts['description'])) {
                $desc = new htmlObj('p',[],[$opts['description']]);
            }
            $indiv = new htmlObj('div',['class'=>'inheader '.$classBootstrap],[$title,$desc]);
            $indiv = new htmlObj('div',['class'=>'inheader row'],[$indiv]);
            return new htmlObj('header',['class'=>'header '.$classBootstrap],[$indiv]);
        }
    }

    /**  __________________________________________________________________________________
    *   Return HTML formated string from header
    *   @param opts:array
    *   @return section:object
    */
    public function mainify(array $opts = [], $classBootstrap = '') : object
    {
        $stackDiv = [];
        if(isset($opts)) {
            foreach($opts as $obj){
                $stackDiv[] = new htmlObj('section',['class'=>'inmain row'],[$indiv]);
            }
        }
        return new htmlObj('main',['class'=>'main '.$classBootstrap],$stackDiv);
    }

    /**  __________________________________________________________________________________
    *   Return HTML formated string from footer
    *   @param opts:array
    *   @return footer:object
    */
    public function footerify(array $opts = [], $classBootstrap = '') : object
    {
        if(isset($opts)) {
            if(isset($opts['title'])) {
                $title = new htmlObj('h2',[],[$opts['title']]);
                $title = new htmlObj('div',['class'=>'infooter '.$classBootstrap],[$title]);
            }
            if(isset($opts['description'])) {
                $desc = $opts['description'];
                if(is_array($desc)){
                    $desc = htmlObj::recursive($desc);
                } elseif (is_object($desc)) {
                    $desc = $desc->html();
                }
                $desc = new htmlObj('div',['class'=>'subfooter '.$classBootstrap],[$desc]);
            }
            $indiv = new htmlObj('div',['class'=>'subfooter row'],[$title,$desc]);
            return new htmlObj('footer',['class'=>'footer '.$classBootstrap],[$indiv]);
        }
    }



}
