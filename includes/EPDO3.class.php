<?php

/**
 *  ePDO : extandedPDO
 *  To change config PDO : please, edit the config.ini file at root of website.
 *  Methods allow you to all basics functions
 */
class EPDO3 {

    // PDO instance
    private $PDO = [];

    // basename locking
    private $dbname = '';

    // table locking
    private $tablename = '';

    // get table elements
    private $tableStruct = '';


    /** __________________________________________________________________________________
    *   Construct :
    *   @param void
    *   @return success:object:EPDO
    *   @return error:false
    *
    */
    public function __construct($params = null){
        $this->connectDB($params);
    }

    /** __________________________________________________________________________________
    *   Create instance to static using
    *   @param void
    *   @return void
    */
    final public function connectDB($params = null)
    {
        // Load congig
        $DB = $params ?? Config::load('config.ini')['database'];

        if (!isset($this->PDO[$DB['name']])) {

            // Init DB connect
            try {
                $req = 'mysql:dbname='.$DB['name'].';host='.$DB['host'].';';
                $options = [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".$DB['charset']
                ];
                // instnce PDO in static var
                $this->PDO[$DB['name']] = new PDO ($req,$DB['login'],$DB['passwd'],$options);
                $this->dbname = $DB['name'];

                unset($DB);
            }
            catch (PDOException $e){
                // print error
                exit('Database connection error : '.$e);
            }
        }
    }

    /** __________________________________________________________________________________
    *   Associate a database
    *   @param database:string
    *   @return database:string
    */
    final public function getDB($database = null)
    {
        if(isset($this->PDO[$database])){
            $this->database = $database;
        }
        return $this->PDO[$database];
    }

    /** __________________________________________________________________________________
    *   Associate a table
    *   @param tablename:string
    *   @return tablename:string
    */
    final public function getTable($tablename = null)
    {
        if(isset($tablename)){
            $this->tablename = $tablename;
        }
        return $this->tablename;
    }

    /** __________________________________________________________________________________
    *   query a psecific patter data into table
    *   @param : string query
    *   @return success:array
    *   @return error:false:throw:error_message
    */
    // final public function search(array $pattern, $cols = '*') {
    //     $str = '';
    //     foreach($pattern as $colname => $patt){
    //         $str .= $colname.' LIKE '.$patt.' AND';
    //     }
    //     $req = 'SELECT '.$cols.' WHERE '.$str.';';
    //     return $this->query($req);
    // }


    /** __________________________________________________________________________________
    *   get table list
    *   @param : tablename
    *   @return success:array (basic array list of tables names)
    *   @return  error:false
    */
    final public function tableList() {
        $dbname = $this->dbname;
        $req = "SELECT TABLE_NAME  FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='".$dbname."';";
        $list = $this->getDB()->query($req);
        return $list->fetchAll();
    }

    /** __________________________________________________________________________________
    *   test if table exists
    *   @param : tablename
    *   @return success:true
    *   @return error:false
    */
    final public function ifTableExists($tablename) {
        $list = $this->tableList();
        if(is_array($list) && in_array($tablename,$list[0])){
            return true;
        }
        else {
            return false;
        }
    }

    /** __________________________________________________________________________________
    *   get table structure
    *   @param : colunms name
    *   @return success:array
    *   @return error:false
    */
    final public static function getStruct($fieldname = null) {
        $req = "SHOW COLUMNS FROM ".self::getTable();
        $list = $this->getDB()->query($req);
        if (!is_bool($list)) {
            $data = $list->fetchAll();
            unset($list);

            foreach($data as $colnum => $colData) {
                $data[$colnum]['Lenght'] = preg_replace('#.*\(([0-9]+)\)$#','$1',$colData['Type']);
                $data[$colnum]['Type'] = preg_replace('#(.*)\([0-9]+\)$#','$1',$colData['Type']);

                if (isset($fieldname)) {
                    if ($colData['Field'] == $fieldname) {
                        return $data[$colnum];
                    }
                }
            }
            return $data;
        } else {
            return false;
        }
    }


    /** __________________________________________________________________________________
    *   DATA manipulation methods
    *
    *   query ( request(string), [Fetch_Method_Const] ) : mixed
    *
    *   insert( data(array), [tablename(string)] ) : bool
    *
    *   update( data(array), condition(array), [tablename(string)] ) : bool
    *
    *   delete( condition(array), [tablename(string)] ) : bool
    *
    */


    /** __________________________________________________________________________________
    *   query data into table
    *   @param : string query
    *   @return success:array
    *   @return error:false:throw:error_message
    *
    */
    final public static function query($req, $FETCH = null) {
        $stat = $this->getDB()->prepare($req);
        $stat->execute();
        if(
            ($error = $stat->errorInfo()[2])
            && !empty($error)
        ){
            unset($stat);
            return $error;
        }
        else {
            if (!is_bool($stat)){
                $data = $stat->fetchAll($FETCH);
                unset($stat);
                if(!empty($data)){
                    if(count($data) > 1 ){
                        return $data;
                    }
                    else {
                        return $data[0];
                    }
                }
            }
            else{
                unset($stat);
                return false;
            }
        }
    }


    /** __________________________________________________________________________________
    *   Insert data into table
    *   @param : string tablename; array : data []
    *   @return success:true
    *   @return error:(string)errormessage
    *
    */
    final public static function insert(array $data,$table = null) {
        // formater la requête
        $prepreq = implode(',',array_keys($data));
        $prepval = ':'.implode(',:',array_keys($data));

        // Sand
        $req = "INSERT INTO ".self::getTable($table)." ($prepreq) VALUES ($prepval);";
        $stat = $this->getDB()->prepare($req);
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

    /** __________________________________________________________________________________
    *   Update data into table
    *   @param : string tablename; array : data []
    *   @return success:true
    *   @return error:(string)errormessage
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

        // Sand
        $stat = $this->getDB()->prepare($req);
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


    /** __________________________________________________________________________________
    *   Delete data from table
    *   @param : string tablename; array : data []
    *   @return success:bool
    *   @return error:(string)errormessage
    *
    */
    final public static function delete(array $cond,$table = null) {
        $req = 'DELETE FROM '.self::getTable($table).' WHERE ';
        foreach ($cond as $key => $value) {
            $req .= ''.$key.' = :'.$key.' AND ';
        }
        $req = substr($req,0,-4).';';
        $stat = $this->getDB()->prepare($req);
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
