<?php


    # import traits
    include __DIR__.'/DBHandler.trait.php';
    include __DIR__.'/TableHandler.trait.php';

class EPDO {

    # use traits
    use DBHandler, TableHandler;

    # DataBase name used
    public $currentDB = '';

    /** __________________________________________________________________________________
    *   Create Object EPDO
    *   @param params:array,tablename:string
    *   @return EPDO:object
    *
    */
    public function __construct(array $params = [], string $tablename = "")
    {
        // create an instance and get in
        DBHandler::connectDB($params);
    }

    /** __________________________________________________________________________________
    *   select a table name
    *   @param base_name:string
    *   @return base_name:string
    */
    final public function getBaseName(string $basename = null) : string
    {
        if ((DBHandler::issetInstance($basename)) !== null) {
            $this->currentDB = $basename;
        }
        return $this->currentDB;
    }

    public function query() {
        try {
            ## get db && get table(+regex)
            $db = DBHandler::getInstance($this->currentDB);

            ## create hendler
            ## apply query
            ## return result
        }
        catch (ERROR $e){
            ## error handling
        }
    }

    public function add() {
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


    public function edit() {
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


    public function delete() {
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


}
