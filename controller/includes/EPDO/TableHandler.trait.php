<?php
// namespace EPDO\TableHandler;

trait TableHandler {

    // Tables instances
    public static $TABLES = [];

    /*
    * TABLES [
    *   tablename => [
    *       
    *       ]
    *   ]
    */


    /**
     *  Creer la table sur le handler
     */

    public final static function createTable() {
        self::$TABLES[$tablename] =  new Tables(); 
    }

    /**
     *  Supprimer la table du handler
     */
    public final static function dropTable() {

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
     */
    final public static function getTableInstance(string $tablename = null)
    {
        if (isset($database) && self::issetBaseInstance($database)) {
            return self::$PDO[$database]['dbo'];
        } 
        else {
            return false;
        }
    }


    /**
    *   load (or re-load) a tablee structure
    *
    */
    final public function loadTableStruct(string $dbname, string $tablename, $fieldname = null)
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

                if (isset($fieldname)) {
                    if ($colData['Field'] == $fieldname) {
                        $data[$colnum];
                        break;
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
