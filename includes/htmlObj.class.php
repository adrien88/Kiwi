<?php


class htmlObj {

    private static $html5;
    private $tagname;
    private $standalone = false;
    private $props = [];
    private $content = [];

    /**
    *   To create a new HTML Tag !
    *   Tagname is resquired !
    *   You can use any attributes with array ['href' => 'truc.html',]
    *   You can add any contents with array ['Some texte',$other_object]
    *   @param tagname:string,tag_attributes:array,tag_content:array
    *   @return html_tag
    */
    public function __construct(string $tagname, array $props = [], array $content = [])
    {
        $standaLonetag = ['wbr','base','input','img','br','hr','link','meta'];
        if (in_array($tagname,$standaLonetag)){
            $this->standalone = true;
        }
        $this->tagname = $tagname;
        $this->set_props($props);
        $this->set_content($content);
    }

    /**
    *   Set attributes to tag
    *   @param tag_attributes:array
    *   @return void
    */
    public function set_props(array $data = []) : void
    {
        $this->props=array_merge($this->props,$data);
    }

    /**
    *   Set contents to tag
    *   @param tag_content:array
    *   @return void
    */
    public function set_content(array $data = []) : void
    {
        $this->content=array_merge($this->content,$data);
    }

    public static function html5(array $opts = null, $content=''){
        // set head
        $foot_javascript = [];
        $head_content[] = new htmlObj('title',[],['content'=> $opts['title'] ?? 'New page' ]);
        $head_content[] = new htmlObj('meta',[],['charset'=> $opts['charset'] ?? 'utf8' ]);

        // Get bootsrap librairies 4
        if(isset($opts['bootstrap'])) {
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

        // Get CSS Files
        if (isset($opts['css']) && is_array($opts['css'])){
            foreach ($opts['css'] as $file) {
                $head_content[] = new htmlObj('link',[
                    'rel'=>'stylesheet',
                    'type'=>'text/css',
                    'href'=>$file],[]);
            }
        }

        //  Get JS Files
        if (isset($opts['js']) && is_array($opts['js'])){
            foreach ($opts['js'] as $file) {
                $head_content[] = new htmlObj('script',[
                    'type'=>'application/javascript',
                    'src'=>$file],[]);
            }
        }

        ## ASSEMBLY HTML HEAD
        $head = new htmlObj('head',[],$head_content);


        ## ASSEMBLY HTML BODY
        $body = new htmlObj('body',[],[$content,$foot_javascript]);
        $html = new htmlObj(
            'html',
            ['lang'=> $opt['lang'] ?? 'en-EN','dir'=> $opt['dir'] ?? 'ltr'],
            [$head,$body]
        );

        # return page object
        return new htmlObj('!DOCTYPE html',[],[$html]);
    }


    public function html() : string
    {
        // open tag
        $str="\n<$this->tagname";
        // browse propoperties
        foreach($this->props as $key => $value){

            // concat html properties
            if(is_int($key)){
                $str.=' '.$value.' ';
            } elseif ($key != 'content' ){
                $str.=' '.$key.'="'.$value.'" ';
            }
        }

        // if tag content (one or any) other tag
        // recusive callbak on inner object or array containing
        if (isset($this->content) && !empty($this->content)){
            $content = '';
            foreach($this->content as $obj){
                if (is_object($obj)) {
                    $content .= $obj->html();
                }

                // ??????????????????????????????????????????????????????????
                // NB ça vaudrait le coup de faire une fonction récursive ?

                elseif (is_array($obj)){
                    foreach($obj as $subobj){
                        $content .= $subobj->html();
                    }
                }
                else {
                    $content .= $obj;
                }
            }
            $this->content = $content;
        }

        // close the tag
        if($this->standalone == true){
            if (isset($this->content) && !empty($this->content)){
                $str.=' content="'.$this->content.'" />';
            } else {
                $str.="/>\n";
            }
        }
        else {
            $str.= '>';
            if (isset($this->content) && !empty($this->content)){
                $str.=$this->content;
            }
            // close double tag execpting Doctype
            if (!preg_match('#!DOCTYPE#i',$this->tagname)){
                $str.='</'.$this->tagname.">";
            }
        }
        // return html formated string
        return $str;
    }

}
