<?php


// import traits
include 'controller/includes/EPDO/DBHandler.trait.php';
include 'controller/includes/EPDO/TableHandler.trait.php';


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
    final public function selectBase(string $basename = null)
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
    final public function selectTable(string $tablename = null)
    {
        if (($this->issetTableInstance($tablename)) !== null) {
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
     *   @return array
     */
    public function connect(array $params = []) {
        return DBHandler::connectDB($params);
    }

    /**
     *   Unset PDO
     *
     *   @param string dbname
     *   @return array
     */
    public function unconnect(string $dbname) {
        return DBHandler::unconnectDB($this->selectBase($dbname));
    }



}
