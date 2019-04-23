<?php

class objTable extends ObjData {

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
        if(
            isset($tablename)
        ){
            $this->tablename=$tablename;
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

    }

    /** __________________________________________________________________________________
    *
    *   load () : bool
    *   Load table from a file
    *   @param void
    *   @param bool
    */
    public function load(){
    ;
    }

}
