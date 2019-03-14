<?php

/**
 *  ePDO : extandedPDO
 *  To change config PDO : please, edit the config.ini file at root of website.
 *  Methods allow you to all basics functions
 */

class ePDO {

    // PDO
    private $PDO;

    /**
    * Construct :
    * @param void
    * @return success:object:ePDO
    * @return error:false
    *
    */
    public function __construct(){

        // load config
        $DB=Config::load('config.ini')['database'];

        // Init DB connect
        try {
            $req = 'mysql:dbname='.$DB['name'].';host='.$DB['host'].';';
            $options = [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".$DB['charset']
            ];

            // connect
            $this->PDO = new PDO ($req,$DB['login'],$DB['passwd'],$options);
        }
        catch (PDOException $e){
            die('Database connection error : '.$e);
            return false;
        }
        // unset
        unset($DB);
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

        // write request
        $req = 'UPDATE '.$table.' SET (';
        foreach ($data as $key => $value) {
            $req .= ''.$key.' =: '.$value.', ';
        }

        $req.=') WHERE ';
        foreach ($cond as $key => $value) {
            $req .= ''.$key.' = '.$value.' AND ';
        }
        $req = substr($req,0,-4).';';

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
    final public function Delete($table, array $data) {
        $req = 'DELETE FROM '.$table.' WHERE ';
        foreach ($data as $key => $value) {
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
