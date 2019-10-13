<?php


// // 
include __DIR__.'/EPDO/Tables.class.php';

// // import traits
include __DIR__.'/EPDO/DBHandler.trait.php';
include __DIR__.'/EPDO/TableHandler.trait.php';

class EPDO {

     // use imported traits
    use DBHandler, TableHandler;

    // DataBase name used
    public $currentDB = '';
    public $currentTable = '';
    
    /**
     *   Create Object EPDO
     *
     *   @param params:array,tablename:string
     *   @return EPDO:object
     */
    public function __construct(array $params = [])
    {
        // create an instance and get in
        DBHandler::connectDB($params);
    }

    /**
     *   Select a base by name
     *
     *   @param base_name:string
     *   @return base_name:string
     */
    final public function selectBase(string $basename = '')
    {
        if ((DBHandler::issetBaseInstance($basename)) != null) {
            $this->currentDB = $basename;
        }
        return $this->currentDB;
    }


    /**
     *   Select a table by name
     *
     *   @param table_name:string
     *   @return table_name:string
     */
    final public function selectTable(string $dbname = '', string $tablename = '')
    {
        if ((DBHandler::ifTableExists(self::selectBase($dbname), $tablename)) !== null) {
            $this->currentTable = $tablename;
        }
        return $this->currentTable;
    }
  

    
    /**
     *   Function to call table list
     *
     *   @param string dbname
     *   @return array
     */
    final public function getTableList(string $dbname = null) 
    {
        if (isset($dbname)) {
            if (DBHandler::issetBaseInstance($dbname)) {
                return DBHandler::getStaticTableList($dbname);
            }
        }
        else {
            $dbname = $this->selectBase();
            if (DBHandler::issetBaseInstance($dbname)) {
                return DBHandler::getStaticTableList($dbname);
            }
        }
    }

    /**
     *   Create a PDO to database
     *
     *   @param string dbname
     *   @return 
     */
    public function connect(array $params = []) {
        return DBHandler::connectDB($params);
    }

    /**
     *   Unset PDO
     *
     *   @param string dbname
     *   @return void
     */
    public function unconnect(string $dbname) {
        DBHandler::unconnectDB($this->selectBase($dbname));
    }


    /**
     *  laod tables defauts elemnts 
     *  
     *  @param
     *  @return 
     * 
     */
    public function loadTable(array $params = []) {
        echo TableHandler::createTable(self::selectBase(), 'pages_metas');
    }


}
