<?php

namespace App\Entity\Security;

abstract class Security {

  public static function secureHTML($chaine) 
  {
    return htmlspecialchars($chaine);
  }
  
  public static function isLogged() :bool
  {
    return isset($_SESSION['USER']);
  }
  
  public static function logout()
  {
    unset($_SESSION['USER']);
    unset($_SESSION['ROLE']);
    unset($_SESSION['token']);
    session_destroy();
  }
}