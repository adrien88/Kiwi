<?php


class htmlObj {

    private static $html5;
    private $tagname;
    private $standalone = false;
    private $props = [];
    private $content = [];




    /**  __________________________________________________________________________________
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

    /**  __________________________________________________________________________________
    *   Set attributes to tag
    *   @param tag_attributes:array
    *   @return void
    */
    public function set_props(array $data = []) : void
    {
        $this->props=array_merge($this->props,$data);
    }

    /** __________________________________________________________________________________
    *   Set contents to tag
    *   @param tag_content:array
    *   @return void
    */
    public function set_content(array $data = []) : void
    {
        $this->content=array_merge($this->content,$data);
    }


    /**  __________________________________________________________________________________
    *   Return HTML formated string from object data
    *   @param void
    *   @return string
    */
    public function html() : string
    {
        // open tag
        $str="\n<$this->tagname";
        // browse propoperties
        foreach($this->props as $key => $value){

            // concat html properties
            if(is_int($key)){
                $str.=' '.$value.' ';
            } else {
                $str.=' '.$key.'="'.$value.'" ';
            }
        }

        // if tag content (one or any) other tag
        // recusive callbak on inner object or array containing
        if (isset($this->content) && !empty($this->content)){
            $this->content = $this->recursive([$this->content]);
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
                $str.='</'.$this->tagname.">\n";
            }
        }
        // return html formated string
        return $str;
    }

    /**  __________________________________________________________________________________
    *   recursive function used in html() method previously defined
    */
    private static function recursive(array $datacontent) : string
    {
        $content ='';
        foreach($datacontent as $obj){
            if (is_object($obj)) {
                $content .= $obj->html();
            }
            elseif (is_array($obj)){
                $content .= self::recursive($obj);
            }
            else {
                $content .= $obj;
            }
        }
        return $content;
    }

}
