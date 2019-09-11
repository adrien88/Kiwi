<?php

/**
* DATA class, used as mother class
* @author : boilley adrien
*/

class ObjData {

    /**
    *   Data stored
    */
    private $DATA;


    public function construct(){}

    /** __________________________________________________________________________________
    *   get (key) : mixed
    *   Can return a value if exist, else return false;
    *   @param key:mixed
    *   @param value:mixed (depend of dataype)
    */
    public function get($key = null)
    {
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
    *   if setted key and value : set key if not exists else replace value
    *   if setted key only : delete table key
    *   else : nothing
    *   @param key:string,int
    *   @param value:mixed (depend of dataype)
    */
    public function set($key = null,$value = null) : void
    {
        if (isset($value)) {
            $value = (!is_array($value)) ? [0 => $value] : $value;
            if(isset($key)){
                $this->DATA[$key]=$value;
            } else {
                $this->DATA[]=$value;
            }
        }
        else{
            unset($this->DATA[$key]);
        }
    }

    /** __________________________________________________________________________________
    *
    *   merge (array) : bool
    *   merge array to data
    *   @param array
    *   @return success:true
    */
    public function merge(array $array = []) : void
    {
        $this->DATA = array_merge($this->DATA, $array);
    }

    /** __________________________________________________________________________________
    *   mathematic summ ()
    *   summ of all values
    *   @param start:int/void
    *   @param end:int/void
    *   @return value:int
    */
    public function summ($start = null,$end = null) : int
    {
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
    public function average($start = null,$end = null) : int
    {
        $nbr = count($this->DATA);
        $sum = $this -> summ($start,$end);
        return $sum/$nbr;
    }

    /** __________________________________________________________________________________
    *   drop all content
    *   @param void
    *   @return void
    */
    public function unset() : void
    {
        $this->DATA = [];
    }

}
