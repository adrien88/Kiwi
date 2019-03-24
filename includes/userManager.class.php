<?php

class userManager {

  public function getinfo($login){
    global $PDO;
    $req='SELECT * FROM users WHERE login = \''.$login.'\'';
    $stat=$PDO->query($req);
    if($stat!==false){
      return $stat->fetch();
    }
    return false;
  }

  public function editinfo($login){
    global $PDO;
    $req='UPDATE users SET WHERE login = \''.$login.'\'';
  }

  public function insertinfo($DATA){
    global $PDO;
    foreach($DATA as $key => $val){

    }
    $req='INSERT INTO users VALUES';
    $stat=$PDO->prepare($req);
    $stat->execute();
  }

  public function deletinfo(){
    global $PDO;
    $req='DELETE FROM user WHERE login = \''.$_SESSION['login'].'\'';
    $stat=$PDO->prepare($req);
    $stat->execute();
  }

}
