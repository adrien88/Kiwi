<?php

/**
* Cauze i woul'd like to kill the SQL creators.
* That it's sumplified data storage writed in PHP.
* Yes, it's using filesystem.
*/


class LocalDada {

    private $basename;
    private static $Instance;

    public function __construct($basename){
        $this->basename($basename);
    }

    final public static function localInstance($basename){
        if(self::$Instance == null) {
            self::$Instance = new LocalDada($basename);
        }
        return self::$Instance;
    }

    public function basename($basename = null){
        if(
            (file_exists($basename) and is_readable($basename.'/.htaccess'))
            or (mkdir($basename) and file_put_contents($basename.'/.htaccess','Require all denied'))
        ){
            $this->basename=$basename;
        }
        else {
            print('Error : localData can\'t use or create <b>'.$basename.'</b> folder.<br>');
        }
        return $this->basename;
    }
}







class TableData extends ObjData {

    // tablename_data_file
    private $tablename = null;
    // tablename_statistics_file
    private $tablestats = null;
    // tablename_cache_file
    private $tablecache = null;

    // table stats
    private static $stats = null;
    // table cache
    private static $cache = null;

    public function __construct($tablename){
        $this->tablename($tablename);
    }

    /** __________________________________________________________________________________
    *
    *   tablename ('tablename') : string
    *   setter tablename : can build path if don't exists
    *   @param tablename:string
    *   @return tablename:string
    */
    public function tablename($tablename = null){
        $tablename .= '.src.bz';
        if(
            (file_exists($tablename) and is_readable($tablename))
            or (file_put_contents($filename,''))
        ){
            $this->tablename=$tablename;
        }
        else {
            print('Error : localData can\'t use or create <b>'.$tablename.'</b> folder.<br>');
        }
        return $this->tablename;
    }

    /** __________________________________________________________________________________
    *
    *   save () : bool
    *   Save table to a file
    *   @param void
    *   @param bool
    */
    public function save(){
        $data = serialize($this->get());
        return file_put_contents($this->tablename,gzencode($data));
    }

    /** __________________________________________________________________________________
    *
    *   load () : bool
    *   Load table from a file
    *   @param void
    *   @param bool
    */
    public function load(){
        $filename = $this->tablename();
        if(file_exists($filename)){
            $data = file_get_contents($filename);
            $data = unserialize(gzdecode($data));
            $this->merge($data);
            return true;
        }
        return false;
    }

}









/**
* DATA class
* @author : boilley adrien
*/

class ObjData {

    private $DATA;

    final public function construct(){}

    /** __________________________________________________________________________________
    *
    *   get (key) : mixed
    *   Can return a value if exist, else return false;
    *   @param key:mixed
    *   @param value:mixed (depend of dataype)
    */
    final public function get($key = null){
        if (isset($key) and isset($this->DATA[$key])) {
            $data = unserialize($this->DATA[$key]);
            return isset($data[1]) ? $data : $data[0];
        }
        elseif (!isset($key)) {
            return $this->DATA;
        }
        else {
            return false;
        }
    }

    /** __________________________________________________________________________________
    *
    *   set (key = null, value = null) : bool
    *   if setted key and value : set key if not exists else recplace value
    *   if setted key only : delete table key
    *   else : nothing
    *   @param key:string,int
    *   @param value:mixed (depend of dataype)
    */
    final public function set($key = null,$value = null){
        if (isset($value)) {
            $value = (!is_array($value)) ? [0 => $value] : $value;
            if(isset($key)){
                $this->DATA[$key]=$value;
            } else {
                $this->DATA[]=$value;
            }
            return true;
        }
        else{
            unset($this->DATA[$key]);
            return true;
        }
    }
    /** __________________________________________________________________________________
    *
    *   merge (array) : bool
    *   merge array to data
    *   @param array
    *   @return success:true
    */
    final public function merge(array $array = null){
        $this->DATA = array_merge($this->DATA, $array);
        return true;
    }

    /** __________________________________________________________________________________
    *   mathematic summ ()
    *   summ of all values
    *   @param start:int/void
    *   @param end:int/void
    *   @return value:int
    */
    final public summ($start = null,$end = null){
        $sum = 0;
        $loop = 0;
        foreach($this->DATA as $val){
            if(
                (isset($start) and $loop < $start)
                or (isset($end) and $loop >= $end)
            ){
                continue;
            }
            $sum += $val;
            $lopp++;
        }
        return $sum;
    }

    /** __________________________________________________________________________________
    *   mathematic average ()
    *   average of all values
    *   @param start:int/void
    *   @param end:int/void
    *   @return value:int
    */
    final public average($start = null,$end = null){
        $nbr = count($this->DATA);
        $sum = $this -> summ($start,$end);
        return $sum/$nbr;
    }

    /** __________________________________________________________________________________
    *   drop all content
    *   @param void
    */
    final public function drop(){
        $this->DATA = [];
        return true;
    }

}
