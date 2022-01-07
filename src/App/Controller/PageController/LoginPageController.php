<?php

namespace App\Controller\PageController;

use App\Entity\ErrorLog\ErrorLog;
use App\Entity\Security\Security;
use Framework\Controller\AbstractController;
use App\Repository\UserRepository\UserRepository;


class LoginPageController extends AbstractController
{

    public function __invoke() 
    {
        unset($_SESSION['USER']);
        
        if($this->isPost()) {
            
            $userRepository = UserRepository::getInstance();
     
            if(!empty($_POST['username']) && !empty($_POST['password'])){
               
                $username = Security::secureHTML($_POST['username']);
                $password = Security::secureHTML($_POST['password']);
                
                $url = $userRepository->login($username, $password);
                
                $this->redirect($url);
             
            } else {
                ErrorLog::ajouterMessageAlerte("Login ou Mot de Passe manquant", ErrorLog::COULEUR_ORANGE);
                
                $this->redirect('/login');
            };

        }
        
        
        return $this->render('login/login.html.twig', [
            "title" => "Connexion",
        ]);
    
    }

}