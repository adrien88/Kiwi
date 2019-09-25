<?php
// namespace EPDO\TableHandler;

trait TableHandler {

    // Current Table
    private static $TABLES = [];
    /**
    * TABLES [
    *   tablename => [
    *       struct => table Structure
    *       regex => [ colname => regex ]
    *   ]
    */

    /**
    *
    *   select a table object (if selectable or selected)
    *   @param table_name:string
    *   @return success:table:object
    *   @return error:false
    */
    final public function getTable($dbname = null, $table = null)
    {
        if (self::ifTableExists($dbname, $table) and !issetTableInstance($table)) {
            self::$TABLES = self::getStruct($dbname, $table);
        }
        return self::$TABLES[$table];
    }

    /**
    *   test if table instance exists
    *
    *   @param table_name:string
    *   @return bool
    */
    final public function issetTableInstance(string $tablename) : bool
    {
        if(isset(self::$TABLES[$tablename])) {
            return true;
        }
        else {
            return false;
        }
    }



    /**
    *   get table structure
    *
    *   @param colunmsname:string
    *   @return success:array
    *   @return error:false
    */
    final public function getStruct($dbname, $table, $fieldname = null)
    {
        $req = "SHOW COLUMNS FROM ".$table;
        $list = self::$PDO[$dbname]->query($req);
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
