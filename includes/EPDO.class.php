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
    * Insert data into table
    * @param : string tablename; array : data []
    * @return success:true
    * @return error:(string)errormessage
    *
    */
    public function Insert($table, array $data) {
        // formater la requête
        $prepreq = implode(',',array_keys($data));
        $prepval = ':'.implode(',:',array_keys($data));

        // Sand
        $req = "INSERT INTO $table ($prepreq) VALUES ($prepval)";
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
    final public function Update($table, array $data = [], array $cond = []) {

        $sdata = [];

        // write request
        $req = 'UPDATE '.$table.' SET ';
        foreach ($data as $key => $value) {
            $req .= ''.$key.' = :'.$key.', ';
            // $sdata[$key]=$value;
        }
        $req = substr($req,0,-2);

        $req.=' WHERE ';
        foreach ($cond as $key => $value) {
            $req .= ''.$key.' = :'.$key.' AND ';
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
    final public function Select($req,$FETCH = null) {
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
    final public function Delete($table, array $cond) {
        $req = 'DELETE FROM '.$table.' WHERE ';
        foreach ($cond as $key => $value) {
            $req .= ''.$key.' = ? AND ';
        }
        $req = substr($req,0,-4).';';
        $stat = $this->PDO->prepare($req);
        $stat->execute($data);
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
