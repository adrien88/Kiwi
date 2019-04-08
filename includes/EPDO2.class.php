<?php

/**
 *  ePDO : extandedPDO
 *  To change config PDO : please, edit the config.ini file at root of website.
 *  Methods allow you to all basics functions
 */
class EPDO2 {

    // PDO instance
    private static $PDO = null;

    // table locking
    private static $tablename = '';
    // get table name
    private static $tableStruct = '';

    /**
    * Construct :
    * @param void
    * @return success:object:EPDO
    * @return error:false
    *
    */
    public function __construct($params = null){
        self::connectDB();
    }

    /**
    * Create instance to static using
    * @param void
    * @return void
    */
    final public static function connectDB()
    {
        if(is_null(self::$PDO)){
            // Load congig
            $DB = $params ?? Config::load('config.ini')['database'];

            // Init DB connect
            try {
                $req = 'mysql:dbname='.$DB['name'].';host='.$DB['host'].';';
                $options = [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".$DB['charset']
                ];
                // instnce PDO in static var
                self::$PDO = new PDO ($req,$DB['login'],$DB['passwd'],$options);
                unset($DB);
            }
            catch (PDOException $e){
                // print error
                exit('Database connection error : '.$e);
            }
        }
    }

    /**
    * Associate to a table
    * @param tablename:string
    * @return tablename:string
    *
    */
    public static function getTable($tablename = null)
    {
        if(isset($tablename)){
            self::$tablename = $tablename;
        }
        return self::$tablename;
    }

    /**
    * Associate to a table
    * @param tablename:string
    * @return succes:table_structure:array
    * COLUMN_NAME,COLLATION_NAME,COLUMN_TYPE,COLUMN_KEY
    */
    public static function getStruct($table = null)
    {
        $req = 'SELECT COLUMN_NAME,COLLATION_NAME,COLUMN_TYPE,COLUMN_KEY FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_NAME = \''.self::getTable($table).'\'
        ORDER BY ORDINAL_POSITION;';
        $stat = self::$PDO->query($req);
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
    * query data into table
    * @param : string query
    * @return success:array
    * @return error:false:throw:error_message
    *
    */
    final public static function query($req, $FETCH = null) {
        $stat = self::$PDO->query($req);

        if(
            ($error = $stat->errorInfo()[2])
            && !empty($error)
        ){
            return $error;
        }
        else {
            if (!is_bool($stat)){
                $data = $stat->fetchAll($FETCH);
                return isset($data[1]) ? $data : $data[0];
            }
            else{
                return false;
            }
        }
    }

    /**
    * Insert data into table
    * @param :
    * @return success:array
    * @return error:(string)errormessage
    *
    */
    final public static function select($what='*',$where=null,$like=null,$orderby=null) {
        $what = is_array($what) ? implode(',', $what) : $what;
        if(isset($where)){

        }
    }


    /**
    * Insert data into table
    * @param : string tablename; array : data []
    * @return success:true
    * @return error:(string)errormessage
    *
    */
    final public static function insert(array $data,$table = null) {
        // formater la requête
        $prepreq = implode(',',array_keys($data));
        $prepval = ':'.implode(',:',array_keys($data));

        // Sand
        $req = "INSERT INTO ".self::getTable($table)." ($prepreq) VALUES ($prepval)";
        $stat = self::$PDO->prepare($req);
        $stat->execute($data);
        if(
            ($error = $stat->errorInfo()[2]) &&
            !empty($error)
        ) {
            return $error."\nreq: ".$req."\n\n";
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
    final static public function update(array $data = [], array $cond = [],$table = null) {

        $sdata = [];

        // write request
        $req = 'UPDATE '.self::getTable($table).' SET ';
        foreach ($data as $key => $value) {
            $req .= ''.$key.' = :'.$key.', ';
        }
        $req = substr($req,0,-2);

        $req.=' WHERE ';
        foreach ($cond as $key => $value) {
            $req .= ''.$key.' = '.self::$PDO->quote($value).' AND ';
        }
        $req = substr($req,0,-4).';';

        echo $req.'<br>';

        // Sand
        $stat = self::$PDO->prepare($req);
        $stat->execute($data);
        if(
            ($error = $stat->errorInfo()[2]) &&
            !empty($error)
        ){
            return $error."\nreq: ".$req."\n\n";
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
    final public static function delete(array $cond,$table = null) {
        $req = 'DELETE FROM '.self::getTable($table).' WHERE ';
        foreach ($cond as $key => $value) {
            $req .= ''.$key.' = :'.$key.' AND ';
        }
        $req = substr($req,0,-4).';';
        $stat = self::$PDO->prepare($req);
        $stat->execute($cond);
        if(
            ($error = $stat->errorInfo()[2])
            && !empty($error)
        ){
            return $error."\nreq: ".$req."\n\n";
        }
        else {
            return true;
        }
    }


}
