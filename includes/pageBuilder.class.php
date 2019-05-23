<?php

class pageBuilder {


    public $PAGE = [];
    public $doctype = '';

    public function __construct(array $opts = []){

    }

    /**  __________________________________________________________________________________
    *   Create HTML 5 Page with using optionnaly Bootstrap 4
    *   @param opts:array,content:mixed
    *   @return void
    */
    public function html5(array $opts = null, $content=''){

        $this->doctype  = '<!DOCTYPE html>';
        $head_content= [];
        $foot_javascript = [];

        // set head element
        $head_content[] = new htmlObj('title',[],['content'=> $opts['title'] ?? 'New page' ]);
        $head_content[] = new htmlObj('meta',[],['charset'=> $opts['charset'] ?? 'utf8' ]);
        $head_content[] = new htmlObj('meta',[
            'name'      =>  "viewport",
            'content'   =>  "width=device-width, initial-scale=1, shrink-to-fit=no"
        ],[]);

        // // views/bootstrap4
        $head_content[] = new htmlObj('link',[
            'rel'=>"stylesheet",
            'href'=>"views/bootstrap4/bootstrap.min.css",
        ],[]);
        $foot_javascript[] = new htmlObj('script',[
            'src'=>"views/bootstrap4/jquery-3.2.1.slim.min.js",
        ],[]);
        $foot_javascript[] = new htmlObj('script',[
            'src'=>"views/bootstrap4/popper.min.js",
        ],[]);
        $foot_javascript[] = new htmlObj('script',[
            'src'=>"views/bootstrap4/bootstrap.min.js",
        ],[]);


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

        // Get OpenGraph and TwitterCard
        // array['SocialNet'][] = ['property'=>'og:title', 'content' => 'open graphcontent']
        if (isset($opts['SocialNet']) && is_array($opts['SocialNet'])){
            foreach ($opts['SocialNet'] as $meta) {
                $head_content[] = new htmlObj(
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
                $foot_javascript[] = new htmlObj(
                    'script', [
                        // 'type' =>   'application/javascript',
                        'src'  =>   $file
                    ], []
                );
            }
        }

        ## ASSEMBLY HTML HEAD
        $head = new htmlObj('head',[],$head_content);

        ## ASSEMBLY HTML BODY
        $body = new htmlObj('body',['id'=>'body'],$foot_javascript);

        $this->PAGE = new htmlObj(
            'html',
            ['lang'=> $opts['lang'] ?? 'en-EN','dir'=> $opts['dir'] ?? 'ltr'],
            [$head,$body]
        );
    }

    // echo $PAGE->tagname; //html
    // echo $PAGE->content[0]->tagname; //head
    // echo $PAGE->content[1]->tagname; //body
    // echo $PAGE->content[1]->content[0]->tagname; //header
    // echo $PAGE->content[1]->content[1]->tagname; //main
    // echo $PAGE->content[1]->content[2]->tagname; //footer
    // echo $PAGE->content[1]->content[3]->tagname; //nav

    public function addObject(array $content, bool $top = false)
    {
        $header = $this->elemBtBuilder(
            ['archi' => self::getArchi(1,0),
             'elemnts' => $content,
            ]
        );
        $this->PAGE->content[1]->set_content([$header],$top);
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
    *
    */
    public function elemBtBuilder(array $opts = [])
    {
        if(isset($opts['archi'])){
            $stackDiv = [];
            if(isset($opts)) {
                foreach($opts['elemnts'] as $obj){
                    $stackDiv[] = new htmlObj('div',['class'=>$opts['archi'][2]],[$obj]);
                }
            }
            $inrow = new htmlObj('div',['class'=>'row'],$stackDiv);
            $indiv = new htmlObj('div',['class'=>$opts['archi'][1]],[$inrow]);
            $inrow = new htmlObj('div',['class'=>'row'],[$indiv]);
            return new htmlObj('section',['class'=>$opts['archi'][0]],[$inrow]);
        }
    }


    public function render() : string
    {
        
        return $this->doctype.$this->PAGE->getHtml();
    }






}
