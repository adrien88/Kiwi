<?php



trait TableHandler {

    // Tables instances
    public static $TABLES = [];

    /*
    *   TABLES [
    *       tablename => [
    *           cols => [
    *               name => [ type(varchar) ; maxlenght(int) ; regex(varchar) ]
    *           ]
    *           stack => [int => command]
    *       ]
    *   ]
    */

    /**
     *  Create Table Object if not exists yet
     * 
     *  @param basename:string,tablename:string
     *  @return bool
     */
    public final static function createTable(string $basename, string $newtablename) 
    {
        // if table unstance
        if (!self::issetTableInstance($newtablename)) {
            self::$TABLES[$newtablename] = new Tables($basename, $newtablename);
            if (is_object(self::$TABLES[$newtablename])) {
                echo ' - -> create table ok<br>';
                return true;
            } 
        }
        return false;
    }

    /**
     *  delete Table from Handler
     */
    public final static function dropTable(string $tablename = '') 
    {
        if (self::issetTableInstance($newtablename)) {
            unset(self::$TABLES[$newtablename]);
        }
    }

    /**
     *   select a database object (if selectable or selected)
     *
     *   @param 
     *   @return
     *   @return 
     */
    final public static function issetTableInstance(string $tablename = null)
    {
        if (isset($tablename) && isset(self::$TABLES[$tablename])) {
            return true;
        }
        else {
            return false;
        }
    }


    /**
     *   select a database object (if selectable or selected)
     *
     */
    final public static function getTableInstance(string $tablename = null)
    {
        if (self::issetTableInstance($tablename)) {
            return self::$TABLES[$tablename];
        } 
        else {
            return false;
        }
    }




    /**
     *  MOVING NEXT METHODS to table object Later
     *  preparing request handler first
     * 
     */


    /**
    *   load (or re-load) a tablee structure
    *
    */
    final public function loadTableStruct(string $dbname, string $tablename, string $fieldname = null)
    {
        $data = [];
        $req = "SHOW COLUMNS FROM ".$tablename;
        $list = self::$PDO[$dbname]->query($req);
        if (!is_bool($list)) {
            $data = $list->fetchAll();
            unset($list);

            foreach($data as $colnum => $colData) {
                $data[$colnum]['Lenght'] = preg_replace('#.*\(([0-9]+)\)$#','$1',$colData['Type']);
                $data[$colnum]['Type'] = preg_replace('#(.*)\([0-9]+\)$#','$1',$colData['Type']);
            
                if (isset($fieldname) && $colData['Field'] == $fieldname) {
                    $data[$colnum];
                    break;
                }
                
            }
            return $data;
        }
    }

    /**
    *   data checking complete missing data.
    *
    *   @param table_structure:array,data:array,tablename:string
    *   @return data:array
    */
    final public function checkStruct(array $struct, array $data, array $escape = ['ID'])
    {
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
        return $data;
    }
}
