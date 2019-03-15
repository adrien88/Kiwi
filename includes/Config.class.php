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
    * save ini :
    * @param string:filename;array:config
    * @return array:bool(false)
    */
    public static function autosave($bool=null)
    {
        if (isset($bool) and is_bool($bool)){
            $this->AS = $bool;
            return true;
        }
        else {
            return $this->AS;
        }
    }


    /**
    * save ini :
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
        function following( $array )
        {
            $str='';
            foreach ( $array as $key => $value ) {

                // escapes key chars
                $key = str_replace('\'','\\\'',$key);

                // if is array : use function recusivly
                if (is_array($value)) {
                    $str .= "[$key]\n";
                    $str .= "\t".following($value)."\n";
                }

                // else stringify
                else {
                    if (is_string($value)) {
                        // escapes values chars
                        $value = str_replace('\'','\\\'',$value);
                        $str .= "$key = '$value'\n";
                    }
                    else {
                        $str .= "$key = $value\n";
                    }
                }
            }
            return $str;
        }

        // config ini file file filled with ini variables
        return file_put_contents( $filename, following($array) );
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
