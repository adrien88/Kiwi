<?php


# import traits
include __DIR__.'/EPDO/DBHandler.trait.php';
include __DIR__.'/EPDO/TableHandler.trait.php';

class EPDO {

    # use traits
    use DBHandler, TableHandler;

    # DataBase name used
    public $currentDB = '';
    public $currentTable = '';


    /** __________________________________________________________________________________
    *   Create Object EPDO
    *   @param params:array,tablename:string
    *   @return EPDO:object
    */
    public function __construct(array $params = [], string $tablename = "")
    {
        // create an instance and get in
        DBHandler::connectDB($params);
        $this->selectBase($params['name']);
        $this->selectTable($tablename);
    }

    /** __________________________________________________________________________________
    *   select a base by name
    *   @param base_name:string
    *   @return base_name:string
    */
    final public function selectBase(string $basename = null) : string
    {
        if (($this->issetBaseInstance($basename)) !== null) {
            $this->currentDB = $basename;
        }
        return $this->currentDB;
    }

    /** __________________________________________________________________________________
    *   select a table by name
    *   @param table_name:string
    *   @return table_name:string
    */
    final public function selectTable(string $tablename = null) : string
    {
        if (($this->issetTableInstance($tablename)) !== null) {
            $this->currentTable = $tablename;
        }
        return $this->currentTable;
    }


    public function query(string $req)
    {
        try {
            ## get db && get table(+regex)
            $db = DBHandler::getInstance($this->currentDB);

            ## create hendler
            $handler = new QueryHandler();

            ## apply query
            $data = DBHandler::getInstance($this->getBaseName())->query();

            ## return result
        }
        catch (ERROR $e){
            ## error handling
        }
    }

    public function add()
    {
        try {
            ## get db && get table(+regex)
            ## create hendler
            ## apply query
            ## return result
        }
        catch (ERROR $e){
            ## error handling
        }
    }


    public function edit()
    {
        try {
            ## get db && get table(+regex)
            ## create hendler
            ## apply query
            ## return result
        }
        catch (ERROR $e){
            ## error handling
        }
    }

    public function delete()
    {
        try {
            ## get db && get table(+regex)
            ## create hendler
            ## apply query
            ## return result
        }
        catch (ERROR $e){
            ## error handling
        }
    }

    public function truncate()
    {
        ## get db && get table(+regex)
        $req = 'TRUNCATE TABLE '.$this->currentTable;
    }

    public function drop()
    {
        ## get db && get table(+regex)
        $req = 'DROP TABLE '.$this->currentTable;

    }


}
