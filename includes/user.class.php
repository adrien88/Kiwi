<?php

class user extends userManager {

  public function __construct(){
    session_start();
    if(
      (!isset($_SESSION['login'])
      || empty($_SESSION['login']))
      && !isset($_SESSION['try'])
    ){
      $_SESSION['try']=3;
      $_SESSION['login']='Visiteur';
      $_SESSION['id_user']=-1;
    }
  }

  public function login_form(){

  }

  public function login_test_form(){

  }

  public function unlogin(){

  }

}
