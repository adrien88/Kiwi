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
    public static function load($filename)
    {
        return parse_ini_file($filename,1);
    }


    /**
    * save ini :
    * @param string:filename;array:config
    * @return bool
    */

    public static function save($filename,array $array = [])
    {
        /**
        * Recursive function to stringify array
        * @param array
        * @return string
        */
        function following($array,$iter,$ckey=null)
        {
            $str='';
            foreach ( $array as $key => $value ) {

                // escapes key chars
                $key = str_replace('\'','\\\'',$key);

                // if is array : use function recusivly
                if (is_array($value)) {
                    $iter++;
                    if(isset($ckey)){
                        $str .= "[$ckey][$key]\n";
                    }
                    else {
                        $str .= "[$key]\n";
                    }
                    $str .= "\t".following($value,$iter,$ckey)."\n";
                }

                // else stringify
                else {
                    if (is_string($value)) {
                        // escapes values chars
                        $value = str_replace('\'','\\\'',$value);
                        $str .= "$key = '$value'\n";
                    }
                    else if (is_bool($value)) {
                        if ($value === true){
                            $str .= "$key = true\n";
                        }
                        else {
                            $str .= "$key = false\n";
                        }
                    }
                    else {
                        $str .= "$key = $value\n";
                    }
                }
            }
            return $str;
        }

        // config ini file file filled with ini variables
        return file_put_contents( $filename, following($array,0));
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
