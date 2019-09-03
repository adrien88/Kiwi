<?php

/** __________________________________________________________________________________
*   DATA manipulation methods
*
*   query ( request(string), [Fetch_Method_Const] ) : mixed
*
*   insert ( data(array), [tablename(string)] ) : bool
*
*   update ( data(array), condition(array), [tablename(string)] ) : bool
*
*   delete ( condition(array), [tablename(string)] ) : bool
*
*/

trait QueryHandler {



    /** 
    *   send query request into table
    *
    *   @param : string query
    *   @return success:array
    *   @return error:false:throw:error_message
    */
    final public function query($req, $FETCH = null)
    {
        $stat = $this->PDO->prepare($req);
        $stat->execute();
        if(
            ($error = $stat->errorInfo()[2])
            && !empty($error)
        ){
            return $error;
        }
        else {
            if (!is_bool($stat)){
                $data = $stat->fetchAll($FETCH);
                unset($stat);
                if(!empty($data)){
                    if(count($data) > 1 ){
                        return $data;
                    }
                    else {
                        return $data[0];
                    }
                }
            }
            else{
                return false;
            }
        }
    }

    /**
    *   query a psecific patter data into table
    *
    *   @param : string query
    *   @return success:array
    *   @return error:false:throw:error_message
    *
    */

    // final public function search(array $pattern, $cols = '*')
    // {
    //     $str = '';
    //     foreach($pattern as $colname => $patt){
    //         $str .= $colname.' LIKE '.$patt.' AND';
    //     }
    //     $req = 'SELECT '.$cols.' WHERE '.$str.';';
    //     return $this->query($req);
    // }

    /**
    *   Insert data into table
    *
    *   @param : string tablename; array : data []
    *   @return success:true
    *   @return error:(string)errormessage
    *
    */
    final public function insert(array $data,$table = null)
    {
        // formater la requête
        $prepreq = implode(',',array_keys($data));
        $prepval = ':'.implode(',:',array_keys($data));

        // Sand
        $req = "INSERT INTO ".$this->tablename." ($prepreq) VALUES ($prepval);";
        $stat = $this->PDO->prepare($req);
        $stat->execute($data);
        if(
            ($error = $stat->errorInfo()[2]) &&
            !empty($error)
        ) {
            return $error."\nreq: ".$req."\n\n";
        }
        else {
            return true;
        }
    }

    /**
    *   Update data into table
    *
    *   @param : string tablename; array : data []
    *   @return success:true
    *   @return error:(string)errormessage
    *
    */
    final public function update(array $data = [], array $cond = [],$table = null)
    {

        $sdata = [];
        // write request
        $req = 'UPDATE '.$this->tablename.' SET ';
        foreach ($data as $key => $value) {
            $req .= ''.$key.' = :'.$key.', ';
        }
        $req = substr($req,0,-2);

        $req.=' WHERE ';
        foreach ($cond as $key => $value) {
            $req .= ''.$key.' = '.self::$PDO->quote($value).' AND ';
        }
        $req = substr($req,0,-4).';';

        // Sand
        $stat = $this->PDO->prepare($req);
        $stat->execute($data);
        if(
            ($error = $stat->errorInfo()[2]) &&
            !empty($error)
        ){
            return $error."\nreq: ".$req."\n\n";
        }
        else {
            return true;
        }
    }


    /**
    *   Delete data from table
    *
    *   @param : string tablename; array : data []
    *   @return success:bool
    *   @return error:(string)errormessage
    *
    */
    final public function delete(array $cond,$table = null)
    {
        $req = 'DELETE FROM '.$this->tablename.' WHERE ';
        foreach ($cond as $key => $value) {
            $req .= ''.$key.' = :'.$key.' AND ';
        }
        $req = substr($req,0,-4).';';
        $stat = $this->PDO->prepare($req);
        $stat->execute($cond);
        if(
            ($error = $stat->errorInfo()[2])
            && !empty($error)
        ){
            return $error."\nreq: ".$req."\n\n";
        }
        else {
            return true;
        }
    }

}
