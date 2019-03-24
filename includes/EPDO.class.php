<?php

/**
 *  ePDO : extandedPDO
 *  To change config PDO : please, edit the config.ini file at root of website.
 *  Methods allow you to all basics functions
 */
class EPDO {

    // PDO
    private $PDO = null;
    public static $instance = null;

    private $tablename = '';
    private $tableStruct = '';

    /**
    * Construct :
    * @param void
    * @return success:object:EPDO
    * @return error:false
    *
    */

    public function __construct($params = null){

        // Load congig
        $DB = $params ?? Config::load('config.ini')['database'];

        // Init DB connect
        try {
            $req = 'mysql:dbname='.$DB['name'].';host='.$DB['host'].';';
            $options = [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".$DB['charset']
            ];
            $this->PDO = new PDO ($req,$DB['login'],$DB['passwd'],$options);
            unset($DB);
        }
        catch (PDOException $e){
            die('Database connection error : '.$e);
        }
    }

    /**
    * Create instance to static using
    * @param void
    * @return success:new_instance
    *
    */
    public static function getInstance()
    {
        if(is_null(self::$instance)){
            self::$instance = new EPDO(null);
        }
        return self::$instance;
    }

    /**
    * Associate to a table
    * @param tablename:string
    * @return tablename:string
    *
    */
    public function getTable($tablename = null)
    {
        if(isset($tablename)){
            $this->tablename = $tablename;
        }
        return $this->tablename;
    }

    /**
    * Associate to a table
    * @param tablename:string
    * @return succes:table_structure:array
    * COLUMN_NAME,COLLATION_NAME,COLUMN_TYPE,COLUMN_KEY
    */
    public function getStruct($table = null)
    {
        $req = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_NAME = \''.$this->getTable($table).'\'
        ORDER BY ORDINAL_POSITION;';
        $stat = $this->PDO->query($req);
        $out = [];
        $i = 0;
        foreach($stat->fetchAll() as $col){
            if(!is_float($i/2)){
                $out[] = $col;
            }
            $i++;
        }
        return $out;
    }

    /**
    * Insert data into table
    * @param : string tablename; array : data []
    * @return success:true
    * @return error:(string)errormessage
    *
    */
    public function insert(array $data,$table = null) {
        // formater la requête
        $prepreq = implode(',',array_keys($data));
        $prepval = ':'.implode(',:',array_keys($data));

        // Sand
        $req = "INSERT INTO ".$this->getTable($table)." ($prepreq) VALUES ($prepval)";
        $stat = $this->PDO->prepare($req);
        $stat->execute($data);
        if(
            ($error = $stat->errorInfo()[2]) &&
            !empty($error)
        ) {
            return $error;
        }
        else {
            return true;
        }
    }


    

    /**
    * Insert data into table
    * @param : string tablename; array : data []
    * @return success:true
    * @return error:(string)errormessage
    *
    */
    final public function update(array $data = [], array $cond = [],$table = null) {

        $sdata = [];

        // write request
        $req = 'UPDATE '.$this->getTable($table).' SET ';
        foreach ($data as $key => $value) {
            $req .= ''.$key.' = :'.$key.', ';
        }
        $req = substr($req,0,-2);

        $req.=' WHERE ';
        foreach ($cond as $key => $value) {
            $req .= ''.$key.' = '.$this->PDO->quote($value).' AND ';
        }
        $req = substr($req,0,-4).';';

        echo $req.'<br>';

        // Sand
        $stat = $this->PDO->prepare($req);
        $stat->execute($data);
        if(
            ($error = $stat->errorInfo()[2]) &&
            !empty($error)
        ){
            return $error;
        }
        else {
            return true;
        }
    }


    /**
    * Insert data into table
    * @param : string tablename; array : data []
    * @return success:array
    * @return error:(string)errormessage
    *
    */
    final public function query($req,$FETCH = null) {
        $stat = $this->PDO->prepare($req);
        $stat->execute();
        if(
            ($error = $stat->errorInfo()[2])
            && !empty($error)
        ){
            throw new Exception($error, 1);
            return false;
        }
        else {
            $data = $stat->fetchAll($FETCH);
            return isset($data[1]) ? $data : $data[0];
        }
    }


    /**
    * Insert data into table
    * @param : string tablename; array : data []
    * @return success:array
    * @return error:(string)errormessage
    *
    */
    final public function delete(array $cond,$table = null) {
        $req = 'DELETE FROM '.$this->getTable($table).' WHERE ';
        foreach ($cond as $key => $value) {
            $req .= ''.$key.' = :'.$key.' AND ';
        }
        $req = substr($req,0,-4).';';
        $stat = $this->PDO->prepare($req);
        $stat->execute($cond);
        if(
            ($error = $stat->errorInfo()[2])
            && !empty($error)
        ){
            return $error;
        }
        else {
            return true;
        }
    }


}
