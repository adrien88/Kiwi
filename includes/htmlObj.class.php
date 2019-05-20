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

    public static function html5(array $opts = null,$content=''){
        // set head
        $head_content[] = new htmlObj('title',[],['content'=> $opts['title'] ?? 'New page' ]);
        $head_content[] = new htmlObj('meta',[],['charset'=> $opts['charset'] ?? 'utf8' ]);
        if (isset($opts['css']) && is_array($opts['css'])){
            foreach ($opts['css'] as $file) {
                $head_content[] = new htmlObj('link',[
                    'rel'=>'stylesheet',
                    'type'=>'text/css',
                    'href'=>$file],[]);
            }
        }
        if (isset($opts['js']) && is_array($opts['js'])){
            foreach ($opts['js'] as $file) {
                $head_content[] = new htmlObj('script',[
                    'type'=>'application/javascript',
                    'src'=>$file],[]);
            }
        }
        $head = new htmlObj('head',[],$head_content);
        $body = new htmlObj('body',[],$content);
        $html = new htmlObj(
            'html',
            ['lang'=> $opt['lang'] ?? 'en-EN','dir'=> $opt['dir'] ?? 'ltr'],
            [$head,$body]
        );
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
