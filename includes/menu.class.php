<?php
class menu extends menuManager {


  /*
      Print Menus
  */
  public static function auto(){
    $data = self::getAll();
    $str = '';
    if($data){
      foreach($data as $key => $menu){

        // url simple
        if(
          !empty($menu['url'])
          && empty($menu['content'])
        ){
          $str .= '
            <li class="nav-item">
              <a class="nav-link" href="'.$menu['url'].'">'.$menu['name'].'</a>
            </li>
          ';
        }
        // menus dropdowns
        else{

          $str .= '<li class="nav-item dropdown" title="'.$menu['description'].'">';
          // if link on dropdown

            $str .=
            '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            '.$menu['name'].'
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            '.$menu['content'].'
            </div>
            </li>
          ';
        }

      }
    }
    $str .= '';
    return $str;
  }


  /* ****************************************************************************
      OPERATING ON MENUS LINKS
  */

  /*
      Add in Menu
  */
  public static function addLinks($title,$description,$url){
    $data = self::add();
    return $str;
  }

  /*
      Edit in Menu
  */
  public static function editLinks($id_menu,$title,$description,$url){
    $data = self::getById($id_menu);
    return $str;
  }

  /*
      Delete in Menu
  */
  public static function deleteLinks($id_menu,$url){
    $data = self::getById($id_menu);
    return $str;
  }

  /* ****************************************************************************
      OPERATING ON MENUS HIMSELFS
  */

  /*
      Add in Menu
  */
  public static function addEntry($title,$description,$url){
    $data = self::add();
    return $str;
  }



}
