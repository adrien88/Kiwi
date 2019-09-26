<?php

trait DBHandler {

    // PDO instances
    private static $PDO = [];

    /*
    * PDO [
    *   dbname => [
    *       dbo => PDO object
    *       tableList => []
    *       TABLES => table Object
    *       ]
    *   ]
    */

    /**
     *   Create instance in static PDO
     *
     *   @param array:['name','host','login','passwd','charset']
     *   @return void
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
                self::$PDO[$DB['name']]['TABLES'] = self::tableList($DB['name'],'TABLE_NAME');
                self::$PDO[$DB['name']]['type'] = $DB['type'];

                // print_r(self::$PDO[$DB['name']]['TABLES']);

                // unset connecting params
                unset($DB);
            }
            catch (PDOException $e){
                // print error
                var_dump('Database connection error : '.$e);
                // unset PDO
                unset(self::$PDO[$DB['name']]);
            }
        }
    }

    /**
     *   select a database object (if selectable or selected)

     *
     *   @param database:string
     *   @return success:database:object
     *   @return error:false
     */
    final public static function issetBaseInstance(string $database = null)
    {
        if (isset($database) && isset(self::$PDO[$database]['dbo'])) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     *   select a database object (if selectable or selected)
     *
     *   @param database:string
     *   @return success:database:object
     *   @return error:false
     */
    final public static function getBaseInstance(string $database = null)
    {
        if (isset($database) && self::issetBaseInstance($database)) {
            return self::$PDO[$database]['dbo'];
        } 
        else {
            return false;
        }
    }

    /**
     *   unconnectDB
     *
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


    /**
     *   return a table list
     *
     *   @param string tablename
     *   @return array (list of tables names)
     */
    final public static function tableList(string $dbname, string $elem = '*') : array
    {
        $req = "SELECT $elem FROM INFORMATION_SCHEMA.TABLES
            WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='$dbname';";

        $PDO = self::getBaseInstance($dbname);
        
        if ($PDO !== false) {
            $data = $PDO->query($req);
            if (!is_bool($data)){  
                $list = [];
                foreach($data->fetchAll() as $subarray){
                    $list[] = array_pop($subarray);
                }
                return $list;
            }
        }
        return [];
    }


    /**
     *   test if table exists
     *
     *   @param 
     *   @return 
     */
    final public function ifTableExists(string $tablename, string $dbname) : bool
    {
        if(array_key_exists($tablename, self::$PDO[$dbname]['TABLES'])){
            return true;
        }
        else {
            return false;
        }
    }


    /**
     *   Function to call table list from class
     *
     *   @param string dbname
     *   @return array
     */
    final public static function getStaticTableList(string $dbname = '') 
    {
        if (isset(self::$PDO[$dbname]['TABLES'])) {
            return self::$PDO[$dbname]['TABLES'];
        }
        return [];
    }
    


    /**
     *  
     *
     *   @param table_name:string
     *   @return table_name:
     */
    final public function loadTable(string $dbname = null, string $tablename = null)
    {       
        self::getBaseInstance($dbname);

        if (self::ifTableExists($tablename)) {
            self::getBaseInstance('');
        }

    }

}
