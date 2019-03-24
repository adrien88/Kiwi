<?php


class menuManager {

  private static $tablename = "menus";


  /*
      GET urls list to create a menu
  */
  public static function getAll(){
    global $PDO;
    $req='SELECT * FROM '.self::$tablename.';';
    $stat=$PDO->prepare($req);
    $stat->execute();
    if($stat!==false){
      return $stat->fetchAll();
    }
    return false;
  }



}
