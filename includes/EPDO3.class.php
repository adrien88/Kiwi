<?php

/**
 *  ePDO : extandedPDO
 *  To change config PDO : please, edit the config.ini file at root of website.
 *  Methods allow you to all basics functions
 */
class EPDO3 {

    // PDO instances
    private static $PDO = [];

    // current DB name
    private $dbname = '';

    // Tables
    private $TABLES = [
        'META' => [
            'STRUCTURE' => [],
            'DATA' => [],
        ]
    ];

    // current table name
    private $tablename = '';

    // current table structure
    private $tableStruct = '';

    // current table data
    private $tabledata = null;


    /** __________________________________________________________________________________
    *   Construct :
    *   @param void
    *   @return success:object:EPDO
    *   @return error:false
    */
    public function __construct($params = null){
        if(isset($params)){
            $this->connectDB($params);
        }
    }

    /** __________________________________________________________________________________
    *   Create instance in static PDO
    *
    *   @param array:['name','host','login','passwd','charset']
    *   @return void
    */
    final public function connectDB($DB)
    {
        if (!isset(self::$PDO[$DB['name']])) {

            // Init DB connect
            try {
                $req = 'mysql:dbname='.$DB['name'].';host='.$DB['host'].';';
                $options = [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".$DB['charset']
                ];
                // instnce PDO in var
                self::$PDO[$DB['name']] = new PDO ($req,$DB['login'],$DB['passwd'],$options);
                $this->dbname = $DB['name'];

                // unset connecting params
                unset($DB);
            }
            catch (PDOException $e){
                // print error
                print('Database connection error : '.$e);
            }
        }
    }

    /** __________________________________________________________________________________
    *   select a database name
    *   @param database_name:string
    *   @return database_name:string
    */
    final public function getDBname($database = null)
    {
        if(isset(self::$PDO[$database])){
            $this->dbname = $database;
        }
        return $this->dbname;
    }

    /** __________________________________________________________________________________
    *   select a database object (if selectable or selected)
    *   @param database:string
    *   @return success:database:object
    *   @return error:false
    */
    final public function getDB($database = null)
    {
        if(isset($database) && isset(self::$PDO[$database])){
            $this->dbname = $database;
            return self::$PDO[$database];
        }
        elseif (isset(self::$PDO[$this->dbname])) {
            return self::$PDO[$this->dbname];
        }
        else {
            return false;
        }
    }


    /** __________________________________________________________________________________
    *   select a table name
    *   @param table_name:string
    *   @return table_name:string
    */
    final public function getTableName($tablename = null)
    {
        if(isset($tablename)){
            $this->tablename = $tablename;
        }
        return $this->tablename;
    }

    /** __________________________________________________________________________________
    *   select a table object (if selectable or selected)
    *   @param table_name:string
    *   @return success:table:object
    *   @return error:false
    */
    final public function getTable($table = null)
    {
        if(isset($table) && isset($this->TABLE[$table])){
            $this->tablename = $table;
            return $this->TABLE[$table];
        }
        elseif (isset($this->TABLE[$this->tablename])) {
            return $this->TABLE[$this->tablename];
        }
        else {
            return false;
        }
    }






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
    final public function getStruct($fieldname = null) {
        $req = "SHOW COLUMNS FROM ".$this->getTableName();
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
    final public function query($req, $FETCH = null) {
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
    *   query a psecific patter data into table
    *   @param : string query
    *   @return success:array
    *   @return error:false:throw:error_message
    */
    final public function search(array $pattern, $cols = '*') {
        $str = '';
        foreach($pattern as $colname => $patt){
            $str .= $colname.' LIKE '.$patt.' AND';
        }
        $req = 'SELECT '.$cols.' WHERE '.$str.';';
        return $this->query($req);
    }




    /** __________________________________________________________________________________
    *   Insert data into table
    *   @param : string tablename; array : data []
    *   @return success:true
    *   @return error:(string)errormessage
    *
    */
    final public function insert(array $data,$table = null) {
        // formater la requête
        $prepreq = implode(',',array_keys($data));
        $prepval = ':'.implode(',:',array_keys($data));

        // Sand
        $req = "INSERT INTO ".$this->getTableName($table)." ($prepreq) VALUES ($prepval);";
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
    final public function update(array $data = [], array $cond = [],$table = null) {

        $sdata = [];
        // write request
        $req = 'UPDATE '.$this->getTableName($table).' SET ';
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
    final public function delete(array $cond,$table = null) {
        $req = 'DELETE FROM '.$this->getTableName($table).' WHERE ';
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
