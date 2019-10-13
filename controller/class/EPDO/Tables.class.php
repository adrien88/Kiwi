<?php 

include __DIR__.'/QueryHandler.trait.php';
include __DIR__.'/RegexHandler.trait.php';

class Tables {

    use QueryHandler, RegexHandler;
    private $STRUCT;
    private $DATA;

    private $basename; 
    private $tablename; 

    /**
     * build with base name and table name
     * 
     * 
     * 
     */
    public function __construct(string $basename = '', string $tablename = ''){
        
        // 
        if (DBHandler::issetBaseInstance($basename)) {
            $this->basename = $basename;
        } else {
            die('EPDO : this base don\'t exist : '.$basename.'<br>');
        }

        // if table unstance
        if (TableHandler::issetTableInstance($tablename)) {

        }

    }
    
    
    private static function PDO(){
        // get PDO
        // if (is_object()) {
        //     echo ' - -> get base ok<br>';
        // }
        return DBHandler::getBaseInstance($this->basename);
    }


    /**  
     *   
     *
     *
     */
    public function query(string $req)
    {
        try {
            // get db && get table(+regex)
            // $db = DBHandler::getInstance();
            // apply query
            // $basename = $this->getBaseName();
            // $data = DBHandler::getInstance($basename)->query($req);
            // return result
            
        }
        catch (ERROR $e){
            // error handling
        }
    }

    
    /**  
     *   
     *   
     *
     *
     */
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
    
    
    /**  
     *   
     *   
     *
     *
     */
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
    

    
    /**  
     *   
     *   
     *
     *
     */
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
    }

    public function drop()
    {
        ## get db && get table(+regex)

    }


}