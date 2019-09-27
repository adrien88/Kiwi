<?php
// namespace EPDO\TableHandler;

trait TableHandler {

    

    // PDO instances
    public static $TABLES = [];

    /*
    * TABLES [
    *   tablename => [
    *       
    *       ]
    *   ]
    */






    /**
    *   load (or re-load) a tablee structure
    *
    *   @param colunmsname:string
    *   @return success:array
    *   @return error:false
    */
    final public function loadTableStruct(string $dbname, string $tablename, $fieldname = null, $return = false)
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
        }
        if($return === false){
            self::$DPO[$dbname]['TABLES'][$tablename]['STRUCT'] = $data;
        } else {
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
