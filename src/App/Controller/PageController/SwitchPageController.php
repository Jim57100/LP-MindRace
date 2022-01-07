<?php

namespace App\Controller\PageController;

use App\Entity\Security\Security;
use Framework\Controller\AbstractController;


class SwitchPageController extends AbstractController
{
    public function __invoke() 
    {
        if(Security::isLogged() && ($_SESSION['ROLE']['role'] === 'ROLE_ADMIN')) {
            return $this->render('login/switch.html.twig');
        } else {
            $this->redirect('/login');
        }
    }
}
