<?php
/** __________________________________________________________________________________
*   Regex manipulation methods
*
*   defaultRegex( void ) : void
*       apply defaut regex to current table
*
*   execRegex ( array data , allowSpec ) : bool
*       execute regex on data by colname to current table
*       allowSpec : if true : allow using words listed in method
*       return array of results
*
*   setRegex ( array regex [colname]=>regex ) : bool
*       create or update a regex
*
*   delRegex (array data [colname_1, colname_2, etc.]) : true
*       delete a regex
*
*   dropRegex ( void ) : true
*       delete all regex
*/

class EpdoRegexHandler {

    private $REGEX=[];

    public function __construct() {
        $this->defaultRegex();
    }


    /** __________________________________________________________________________________
    *   import default model regex
    *   @param void
    *   @return void
    */
    final public function defaultRegex() : void
    {
        $this->REGEX = [
            'login' => '#([a-z0-9-_.]){2,}#i',
            'email' => '#([a-z0-9-_.]{2,})@([a-z0-9-_.]{2,})\.([a-z0-9-_.]{2,})#i',
            'passwd' => '#([0-9a-z-_.*=+,:;!#$£€\[\]\(\){}\'"])#i',
            'lastname' => '#(([a-z-]){2,} ?)+#i',
            'surname' => '#(([a-z-]){2,} ?)+#i',
            'phone' => '#(([0-9]){2,3}[/. -]{1}){3,5}#i',
            'ipv4'  => '#([0-9]{3}\.){1,}\.([0-9]{3})#',
        ];
    }

    /** __________________________________________________________________________________
    *   apply regex on data and return array with [colname] => 1 or 0.
    *   allowspecs : allowing specifics words.
    *   @param array:[string:colname=>string:regex],bool:allowSpec
    *   @return array[string:colname=>int].
    */
    final public function execRegex(array $data = [],bool $allowSpec = false) : int
    {
        $test = [];
        $forbbiden = '#select|insert|update|delete|truncate|drop#i';
        foreach($this->REGEX as $colname => $regex){
            if(
                ( isset($data[$colname]) && !preg_match($regex,$data[$colname]) )
                or ( $allowSpec === false && preg_match($forbbiden, $data[$colname]) )
            ){
                $test[$colname]=0;
            } else {
                $test[$colname]=1;
            }
        }
        return $test;
    }

    /** __________________________________________________________________________________
    *   add or edit a regex rule
    *   @param array[col_1=>'regex_1',etc]
    *   @return true
    */
    final public function setRegex(array $regex = []) : bool
    {
        $this->REGEX = array_merge($this->REGEX, $regex);
        return true;
    }

    /** __________________________________________________________________________________
    *   delete a regex rule
    *   @param array[col_1,col_2,etc]
    *   @return true
    */
    final public function delRegex(array $regex = []) : bool
    {
        foreach($regex as $regexname){
            unset($this->REGEX[$regexname]);
        }
        return true;
    }

    /** __________________________________________________________________________________
    *   drop all regex
    *   @param void
    *   @return true
    */
    final public function dropRegex() : bool
    {
        $this->REGEX = [];
        return true;
    }




}
