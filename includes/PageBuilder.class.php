<?php

// namespace kiwi\pageBuilder;

class PageBuilder {


    public $PAGE = [];
    public $ft_script = [];
    public $HTML5 = [];
    public $doctype = '';

    public function __construct(array $opts = []){

    }

    /**  __________________________________________________________________________________
    *   Create HTML 5 Page with using optionnaly Bootstrap 4
    *   @param opts:array,content:mixed
    *   @return void
    */
    public function html5(array $opts = null, $content=''){
        $this->doctype  = "<!DOCTYPE html>
        <!--
            \tBOILLEY Adrien © 2019 GPL 3.0
            \tThis website was powered by Kiwi Framework
            \tMore about kiwi at : https://github.com/adrien88/Kiwi
        -->\n ";
        $head_content= [];

        // set head element
        $head_content[] = new HtmlObj('title',[],['content'=> $opts['title'] ?? 'New page' ]);
        $head_content[] = new HtmlObj('meta',[],['charset'=> $opts['charset'] ?? 'utf8' ]);
        $head_content[] = new HtmlObj('meta',[
            'name'      =>  "viewport",
            'content'   =>  "width=device-width, initial-scale=1, shrink-to-fit=no"
        ],[]);

        // // views/bootstrap4
        $head_content[] = new HtmlObj('link',[
            'rel'=>"stylesheet",
            'href'=>"views/bootstrap4/bootstrap.min.css",
        ],[]);
        // $this->ft_script[] = new HtmlObj('script',[
        //     'src'=>"views/bootstrap4/jquery-3.2.1.slim.min.js",
        // ],[]);
        // $this->ft_script[] = new HtmlObj('script',[
        //     'src'=>"views/bootstrap4/popper.min.js",
        // ],[]);
        // $this->ft_script[] = new HtmlObj('script',[
        //     'src'=>"views/bootstrap4/bootstrap.min.js",
        // ],[]);


        // Get CSS Files from list
        if (isset($opts['css']) && is_array($opts['css'])){
            foreach ($opts['css'] as $file) {
                $head_content[] = new HtmlObj(
                    'link', [
                        'rel'  =>   'stylesheet',
                        'type '=>   'text/css',
                        'href' =>   $file
                    ], []
                );
            }
        }

        // Get OpenGraph and TwitterCard
        // array['SocialNet'][] = ['property'=>'og:title', 'content' => 'open graphcontent']
        if (isset($opts['SocialNet']) && is_array($opts['SocialNet'])){
            foreach ($opts['SocialNet'] as $meta) {
                $head_content[] = new HtmlObj(
                    'meta', [
                        'property'  =>  $meta['property'],
                        'content'=>   $meta['content'],
                    ], []
                );
            }
        }

        //  Get JS Files from list
        if (isset($opts['js']) && is_array($opts['js'])){
            foreach ($opts['js'] as $file) {
                $foot_javascript[] = new HtmlObj(
                    'script', [
                        // 'type' =>   'application/javascript',
                        'src'  =>   $file
                    ], []
                );
            }
        }

        ## ASSEMBLY HTML HEAD
        $head = new HtmlObj('head',[],$head_content);

        ## ASSEMBLY HTML BODY
        $body = new HtmlObj('body',['id'=>'body'],[]);

        $this->PAGE = new HtmlObj(
            'html',
            ['lang'=> $opts['lang'] ?? 'en-EN','dir'=> $opts['dir'] ?? 'ltr'],
            [$head,$body]
        );
    }

    /**
    *   Choose the BootStrap structure
    *   @param structuretype:int,nbrcolumns:int
    *   @return success:array
    *   @return error:false
    */
    public static function getArchi(int $struct = 0, int $cols = 0){

        //             'container' (row)  'sub container' (row)        [bgpage (bgsection (bgcols) ) ]
        $a_struct[] = ['container-fluid', 'container-fluid',];      // [ (#(.............)#) ]
        $a_struct[] = ['container-fluid', 'container',];            // [ (######(...)######) ]
        $a_struct[] = ['container',       'container',];            // [      (#(...)#)      ]

        //                                                             nbr cols
        $a_cols[] = '';                                             // no col determined
        $a_cols[] = 'col-12';                                       // one column
        $a_cols[] = 'col-xs-12 col-sm-6';                           // two columns
        $a_cols[] = 'col-xs-12 col-sm-6 col-lg-4';                  // tree columns
        $a_cols[] = 'col-xs-12 col-sm-6 col-md-4 col-lg-3';         // four coulumns
        $a_cols[] = 'col-xs-12 col-sm-6 col-md-4 col-lg-2';         // six coulumns (seriously?)

        if (
            $struct >= 0 && $struct <= (count($a_struct)-1) &&
            $cols   >= 0 && $cols   <= (count($a_cols)-1)
        ){
            // return array like ['container-fluid', 'container', 'col-xs-12 col-sm-6']
            return array_merge($a_struct[$struct], [$a_cols[$cols]]);
        }
    }

    /**
    * building with bootstrap
    *   @param opts:array:[elemnts,archi]
    */
    public function elemBtBuilder(array $opts = [])
    {
        if(isset($opts['archi']) && isset($opts['tagname'])){
            $stackDiv = [];
            if(isset($opts['elemnts'])) {
                foreach($opts['elemnts'] as $obj){
                    // if (!in_array('bt',$obj->class)){
                    if (!empty($opts['archi'][2])) {
                        $stackDiv[] = new HtmlObj('div',['class'=>$opts['archi'][2]],[$obj]);
                    } else {
                        // unset($obj->class['bt']);
                        $stackDiv[] = $obj;
                    }
                }
            }
            $inrow = new HtmlObj('div',['class'=>'row'],$stackDiv);
            $indiv = new HtmlObj('div',['class'=>$opts['archi'][1]],[$inrow]);
            $inrow = new HtmlObj('div',['class'=>'row'],[$indiv]);
            $this->PAGE->content[1]->set_content(
                [new HtmlObj($opts['tagname'],['class'=>$opts['archi'][0]],[$inrow])]
            );
        }
    }


    /**
    *   Return a standard HTML page
    *   @param void
    *   @return string:pageHtml
    */
    public function render() : string
    {
        // adding footer scripts
        $this->PAGE->content[1]->set_content($this->ft_script);
        // return a serialized standard HTML page
        return $this->doctype.$this->PAGE->getHtml();
    }






}
