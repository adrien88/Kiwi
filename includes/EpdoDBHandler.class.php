<?php

class EpdoDBHandler {


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


    public function __construct(array $params = []) {
        $this->connectDB($params);
    }


    /** __________________________________________________________________________________
    *   Create instance in static PDO
    *   @param array:['name','host','login','passwd','charset']
    *   @return void
    *
    */
    final public function connectDB(array $DB)
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
    final public function getInstance(string $database = null)
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
}
