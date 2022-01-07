<?php

namespace App\Controller\PageController;

use App\Entity\ErrorLog\ErrorLog;
use App\Entity\Security\Security;
use App\Entity\UserEntity\UserEntity;
use Framework\Controller\AbstractController;
use App\Repository\UserRepository\UserRepository;


class RegisterPageController extends AbstractController
{
  
  public function __invoke()
  {   
    
    unset($_SESSION['USER']);

    if($this->isPost()) {
      
      $user = new UserEntity($_POST);
      $userRepository = UserRepository::getInstance();
      
      if(!empty($_POST['username']) && !empty($_POST['password'] && !empty($_POST['mail']))) {
      
        $username = Security::secureHTML($_POST['username']);
        $mail = Security::secureHTML($_POST['mail']);
        $password = Security::secureHTML($_POST['password']);
        $confirmPassword = Security::secureHTML($_POST['confirm_password']);
        $firstName = Security::secureHTML($_POST['firstName']);
        $lastName = Security::secureHTML($_POST['lastName']);
        // var_dump("username: $username, mail: $mail, first: $firstName, last: $lastName"); die(); OK
        $url = $userRepository->validation_register($user, $username, $password, $confirmPassword, $mail, $firstName, $lastName);
    
        $this->redirect($url);
      
      } else {
        ErrorLog::ajouterMessageAlerte("Informations manquantes", ErrorLog::COULEUR_ORANGE);
        $this->redirect('/register');
      }
      
    }
    
    return $this->render('register/register.html.twig', [
      "page_title" => "S'enregistrer",
    ]);
  
  }

}

