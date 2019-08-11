<?php

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

    /** __________________________________________________________________________________
    *   data checking complete missing data.
    *   @param table_structure:array,data:array,tablename:string
    *   @return data:array
    */
    final public function checkStruct(array $struct, array $data, array $escape = ['ID']) : array
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
    *   get table structure
    *   @param colunmsname:string
    *   @return success:array
    *   @return error:false
    */
    final public function getStruct($fieldname = null)
    {
        $req = "SHOW COLUMNS FROM ".$this->getTable();
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
}
