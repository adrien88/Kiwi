<?php

// namespace kiwi\HtmlObj;

class HtmlObj {

    public $tagname;
    public $props = [];
    public $class = [];
    public $content = [];

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
        $this->tagname = $tagname;
        // $this->set_props(['id'=>$tagname.'#'.rand(1000,2000)]);
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
    public function set_content(array $groupdata = [], bool $top = false) : void
    {
        foreach($groupdata as $data){
            if($top == true){
                array_unshift($this->content,$data);
            }
            else {
                $this->content[] = $data;
            }
        }
    }

    /** __________________________________________________________________________________
    *   Set class to tag
    *   @param class:string
    */
    public function set_class(array $class = []) : void
    {
        $this->class = array_merge($this->class,$class);
    }

    /** __________________________________________________________________________________
    *   Isset class into tag
    *   @param class:string
    */
    public function isset_class(string $class) : bool
    {
        return in_array($class, $this->class);
    }

    /** __________________________________________________________________________________
    *   Unset class from tag
    *   @param class:string
    */
    public function unset_class(string $class) : void
    {
        unset($this->class[$class]);
    }


    /**  __________________________________________________________________________________
    *   Return HTML formated string from object data
    *   @param void
    *   @return string
    */
    public function getHtml() : string
    {

        // open tag
        $str="<$this->tagname";

        if (!empty($this->class)) {
            $str.=' class="'.implode(' ',$this->class).'" ';
        }

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
        $standaLonetag = ['wbr','base','input','img','br','hr','link','meta'];
        if(in_array($this->tagname,$standaLonetag)){
            if (isset($this->content) && !empty($this->content)){
                $str.=" content=\"$this->content\" />\n";
            } else {
                $str.="/>\n";
            }
        }
        else {
            $str.=" >\n";
            if (isset($this->content) && !empty($this->content)){
                $str.=$this->content;
            }
            $str.="</$this->tagname>\n";

        }
        // return html formated string
        return $str;
    }

    /**  __________________________________________________________________________________
    *   recursive function used in getHtml() method previously defined
    *   @param datacontent:array
    *   @return string
    */
    private static function recursive(array $datacontent) : string
    {
        $content ='';
        foreach($datacontent as $obj){
            if (is_object($obj)) {
                $content .= $obj->getHtml();
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
