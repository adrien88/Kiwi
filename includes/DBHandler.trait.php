<?php

trait DBHandler {

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



    /** __________________________________________________________________________________
    *   Create instance in static PDO
    *   @param array:['name','host','login','passwd','charset']
    *   @return void
    *
    */
    final public static function connectDB(array $DB)
    {
        if (isset($DB['name']) && !isset(self::$PDO[$DB['name']])) {

            // Init DB connect
            try {
                $req = $DB['type'].':dbname='.$DB['name'].';host='.$DB['host'].';';
                $options = [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".$DB['charset']
                ];

                // instnce PDO in var
                self::$PDO[$DB['name']]['dbo'] = new PDO ($req,$DB['login'],$DB['passwd'],$options);
                self::$PDO[$DB['name']]['tables'] = self::tableList($DB['name'],'TABLE_NAME');
                self::$PDO[$DB['name']]['type'] = $DB['type'];

                // print_r(self::$PDO[$DB['name']]['tables']);

                // unset connecting params
                unset($DB);
            }
            catch (PDOException $e){
                // print error
                print('Database connection error : '.$e);
                // unset PDO
                unset(self::$PDO[$DB['name']]);
            }
        }
    }


    /** __________________________________________________________________________________
    *   select a database object (if selectable or selected)
    *   @param database:string
    *   @return success:database:object
    *   @return error:false
    */
    final public static function issetBaseInstance(string $database = null)
    {
        if(isset($database) && isset(self::$PDO[$database]['dbo'])){
            return true;
        }
        else {
            return false;
        }
    }

    /** __________________________________________________________________________________
    *   select a database object (if selectable or selected)
    *   @param database:string
    *   @return success:database:object
    *   @return error:false
    */
    final public static function getBaseInstance(string $database = null)
    {
        if(isset($database) && isset(self::$PDO[$database]['dbo'])){
            return self::$PDO[$database]['dbo'];
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
    final public static function unconnectDB($database = null) : bool
    {
        if (isset(self::$PDO[$database])) {
            self::$PDO[$database] = null;
            return true;
        }
        return false;
    }

    /** __________________________________________________________________________________
    *   get table list
    *   @param string tablename
    *   @return array (list of tables names)
    */
    final public static function tableList(string $dbname, string $elem = '*') : array
    {
        $req = "SELECT $elem FROM INFORMATION_SCHEMA.TABLES
            WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='$dbname';";

        if (isset(self::$PDO[$dbname])) {

            $list = self::$PDO[$dbname]['dbo']->query($req);
            $tab = [];

            // warning : fetchAll musn't be a boolean !
            if (!is_bool($list)){
                foreach($list->fetchAll() as $col){
                    $tab[] = $col;
                }
            }
        }
        return $tab;
    }

    /** __________________________________________________________________________________
    *   test if table exists
    *   @param : tablename
    *   @return success:true
    *   @return error:false
    */
    final public function ifTableExists(string $tablename, string $dbname) : bool
    {
        if(in_array($tablename, self::$PDO[$dbname]['tables'])){
            return true;
        }
        else {
            return false;
        }
    }

}
