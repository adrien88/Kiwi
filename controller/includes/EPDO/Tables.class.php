<?php 

// include 'controller/includes/EPDO/QueryHandler.trait.php';
// include 'controller/includes/EPDO/RegexHandler.trait.php';

class Tables {

    // use QueryHandler, RegexHandler;
    private $STRUCT;


    /**
     * 
     */
    public function __conctruct(array $struct){
        
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