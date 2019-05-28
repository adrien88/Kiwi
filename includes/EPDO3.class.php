<?php

/**
 *
 *  ePDO : extandedPDO
 *  To change config PDO : please, edit the config.ini file at root of website.
 *  Methods allow you to all basics functions
 */
class EPDO3 {

    // PDO instances
    private static $PDO = [];
    /**
    * PDO [
    *   dbname => [
    *       dbo => PDO object
    *       tableList => []
    *       ]
    *   ]
    */

    // current DB name
    private $dbname = '';


    // Current Table
    private $TABLES = [];
    /**
    * TABLES [
    *   tablename => [
    *       struct => table Structure
    *       regex => [ colname => regex ]
    *   ]
    */

    // current table name
    private $tablename = '';

    /** __________________________________________________________________________________
    *   Construct :
    *   @param void
    *   @return success:object:EPDO
    *   @return error:false
    */
    public function __construct($params = null)
    {
        if(isset($params)){
            $this->connectDB($params);
        }
    }

    /** __________________________________________________________________________________
    *   Create instance in static PDO
    *   @param array:['name','host','login','passwd','charset']
    *   @return void
    *
    */
    final public function connectDB($DB)
    {
        if (!isset(self::$PDO[$DB['name']])) {

            // Init DB connect
            try {
                $req = $DB['type'].':dbname='.$DB['name'].';host='.$DB['host'].';';
                $options = [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".$DB['charset']
                ];
                // instnce PDO in var
                self::$PDO[$DB['name']]['dbo'] = new PDO ($req,$DB['login'],$DB['passwd'],$options);
                $this->dbname = $DB['name'];
                self::$PDO[$DB['name']]['tables'] = $this->tableList();

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
    final public function getDBname(string $database = null) : string
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
    final public function getDB(string $database = null)
    {
        if(isset($database) && isset(self::$PDO[$database]['dbo'])){
            $this->dbname = $database;
            return self::$PDO[$database]['dbo'];
        }
        elseif (isset(self::$PDO[$this->dbname]['dbo'])) {
            return self::$PDO[$this->dbname]['dbo'];
        }
        else {
            return false;
        }
    }

    /** __________________________________________________________________________________
    *   unconnectDB
    *   @param database:string
    *   @return bool
    */
    final public function unconnectDB($database = null) : bool
    {
        if (isset(self::$PDO[$database])) {
            if ($this->dbname == $database){
                $this->dbname = null;
            }
            self::$PDO[$database] = null;
            return true;
        }
        return false;
    }

    /** __________________________________________________________________________________
    *   TABLE manipulation methods
    *
    *
    *
    *
    *
    *
    */

    /** __________________________________________________________________________________
    *   select a table name
    *   @param table_name:string
    *   @return table_name:string
    */
    final public function getTableName($tablename = null) : string
    {
        if(isset($tablename)){
            $this->tablename = $tablename;
            $this->TABLES[$tablename] = '';
        }
        return $this->tablename;
    }

    /** __________________________________________________________________________________
    *   select a table object (if selectable or selected)
    *   @param table_name:string
    *   @return success:table:object
    *   @return error:false
    */

    final public function getTable($table = null) : array
    {
        if (isset($table) && in_array($table, self::$PDO[$this->dbname]['tables'])) {
            $this->tablename = $table;
            return $this->TABLE[$table];
        }
        elseif (isset($this->TABLE[$this->tablename])) {

        }
        return $this->TABLE[$this->tablename];
    }





    /** __________________________________________________________________________________
    *   get table list
    *   @param : tablename
    *   @return success:array (basic array list of tables names)
    *   @return  error:false
    */
    final public function tableList($dbname = null)
    {
        $dbname = $this->getDBname($dbname);
        $req = "SELECT TABLE_NAME  FROM INFORMATION_SCHEMA.TABLES
            WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='".$dbname."';";
        $list = $this->getDB()->query($req);
        $tab = [];
        foreach($list->fetchAll() as $col){
            $tab[]=$col['TABLE_NAME'];
        }
        return $tab;
    }

    /** __________________________________________________________________________________
    *   test if table exists
    *   @param : tablename
    *   @return success:true
    *   @return error:false
    */
    final public function ifTableExists(string $tablename,string $dbname = null)
    {
        $list = self::$PDO[$DB['name']][$this->getDBname($dbname)]['tables'];
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
    final public function getStruct($fieldname = null)
    {
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
    *   Regex manipulation methods
    *
    *   defaultRegex( void ) : void
    *       apply defaut regex to current table
    *
    *   execRegex ( array data , allowSpec ) : bool
    *       execute regex on data by colname to current table
    *       allowSpec : if true : allow using words listed in method
    *       return array of results
    *
    *   setRegex ( array regex [colname]=>regex ) : bool
    *       create or update a regex
    *
    *   delRegex (array data [colname_1, colname_2, etc.]) : true
    *       delete a regex
    *
    *   dropRegex ( void ) : true
    *       delete all regex
    */

    /** __________________________________________________________________________________
    *   import default model regex
    *   @param void
    *   @return void
    */
    final public function defaultRegex() : void
    {
        $this->TABLES[$this->tablename]['regex'] = [
            'login' => '#([a-z0-9-_.]){2,}#i',
            'email' => '#([a-z0-9-_.]{2,})@([a-z0-9-_.]{2,})\.([a-z0-9-_.]{2,})#i',
            'passwd' => '#([0-9a-z-_.*=+,:;!#$£€\[\]\(\){}\'"])#i',
            'lastname' => '#(([a-z-]){2,} ?)+#i',
            'surname' => '#(([a-z-]){2,} ?)+#i',
            'phone' => '#(([0-9]){2,3}[/. -]{1}){3,}#i',
            'ipv4'  => '#([0-9]{3}\.){1,}\.([0-9]{3})#',
        ];
    }


    /** __________________________________________________________________________________
    *   apply regex on data and return array with [colname] => 1 or 0.
    *   allowspecs : allowing specifics words.
    *   @param array:data[colname]=>regex,bool:allowSpec
    *   @return array[colname]=>bool.
    */
    final public function execRegex(array $data = [],bool $allowSpec = false) : bool
    {
        $test = [];
        $forbbiden = '#select|insert|update|delete|truncate|drop#i';
        foreach($this->TABLES[$this->tablename]['regex'] as $colname => $regex){
            if(
                ( isset($data[$colname]) && !preg_match($regex,$data[$colname]) )
                or ( $allowSpec === false && preg_match($forbbiden, $data[$colname]) )
            ){
                $test[$colname]=false;
            } else {
                $test[$colname]=true;
            }
        }
        return $test;
    }


    /** __________________________________________________________________________________
    *   add or edit a regex rule
    *   @param array[col_1=>'regex_1',etc]
    *   @return true
    */
    final public function setRegex(array $regex = []) : bool
    {
        $current = $this->TABLES[$this->dbname]['regex'];
        $this->TABLES[$this->tablename]['regex'] = array_merge($curent, $regex);
        return true;
    }

    /** __________________________________________________________________________________
    *   delete a regex rule
    *   @param array[col_1,col_2,etc]
    *   @return true
    */
    final public function delRegex(array $regex = []) : bool
    {
        foreach($regex as $regexname){
            unset($this->TABLES[$this->tablename]['regex'][$regexname]);
        }
        return true;
    }

    /** __________________________________________________________________________________
    *   drop all regex
    *   @param void
    *   @return true
    */
    final public function dropRegex() : bool
    {
        $this->TABLES[$this->tablename]['regex']=[];
        return true;
    }


    /** __________________________________________________________________________________
    *   DATA manipulation methods
    *
    *   query ( request(string), [Fetch_Method_Const] ) : mixed
    *
    *   insert ( data(array), [tablename(string)] ) : bool
    *
    *   update ( data(array), condition(array), [tablename(string)] ) : bool
    *
    *   delete ( condition(array), [tablename(string)] ) : bool
    *
    */


    /** __________________________________________________________________________________
    *   data checking complete missing data.
    *   @param data:array,tablename:string
    *   @return data:array
    */
    final public function checkStruct(array $data, array $escape = ['ID'], $tablename = null)
    {
        print_r($data);

        // $this->getTableName($tablename);
        $struct = $this->getStruct();

        foreach ($struct as $col) {
            if (
                !in_array($col['Field'],$escape) &&
                !array_key_exists($col['Field'], $data)
            ){
                echo 'champs à ajouter  :'.$col['Field'].'<br>';
                switch($col['Type']){
                    case 'varchar';
                    case 'text';
                        $data[$col['Field']] = '';
                    break;
                    case 'timestamp';
                        $data[$col['Field']] = time();
                    break;
                    default;
                        $data[$col['Field']] = 0;
                    break;
                }
            }
        }
        // $data = array_merge($struct,$data);

        print_r($data);
        return $data;
    }

    /** __________________________________________________________________________________
    *   send query request into table
    *   @param : string query
    *   @return success:array
    *   @return error:false:throw:error_message
    */
    final public function query($req, $FETCH = null)
    {
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
    *
    */
    final public function search(array $pattern, $cols = '*')
    {
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
    final public function insert(array $data,$table = null)
    {

        $data = $this->checkStruct($data);

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
    final public function update(array $data = [], array $cond = [],$table = null)
    {

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
    final public function delete(array $cond,$table = null)
    {
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
