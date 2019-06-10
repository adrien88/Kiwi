<?php

class Config {

    public $CONFIG;
    public $FILENAME;
    public $AS;

    /**
    * Create CONFIG object
    * @param string:filename,bool:autosave
    * @return object
    */
    public function __construct($filename){
        if (file_exists($filename)) {
            $this->CONFIG = $this->load($filename);
            $this->autosave(true);
        }
        else{
            $this->autosave(false);
        }
        $this->FILENAME = $filename;
    }

    /**
    * enable or disable autosave
    * @param bool:make_statut
    * @return bool:statut
    */
    public static function autosave(bool $bool=null)
    {
        if (isset($bool)){
            $this->AS = $bool;
        }
        return $this->AS;
    }

    /**
    * filename setter
    * @param string:make_statut
    * @return string:statut
    */
    public static function set($filename=null)
    {
        if (isset($filename)){
            $this->FILENAME = $filename;
        }
        return $this->FILENAME;
    }


    /**
    * load ini :
    * @param string:filename;array:config
    * @return array:bool(false)
    */
    final public static function load($filename)
    {
        return parse_ini_file($filename,1);
    }


    /**
    * save ini :
    * @param string:filename;array:config
    * @return bool
    */
    final public static function save($filename,array $array = [])
    {
        $string = '';
        ##! asort will send sub array at last
        asort($array);
        foreach ( $array as $key => $value ) {
            $string .= "\n";
            if(is_array($value)) {

                $string .= "\n[$key]";

                foreach ( $value as $subkey => $subvalue ) {
                    $subkey = is_int($subkey) ? "'$subkey'" : $subkey;
                    $subvalue = is_array($subvalue) ? serialize($subvalue) : $subvalue;
                    $string .= "\n\t $subkey = $subvalue ";
                }
            }
            else {
                $string .= "$key = $value ";
            }
        }
        return file_put_contents($filename, $string);
    }

    /**
    * Autosave content at object desctruct
    */
    public function __destruct(){
        if ($this->AS == 1) {
            return $this->save($this->FILENAME,$this->CONFIG);
        }
    }
}
