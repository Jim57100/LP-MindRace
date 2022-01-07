<?php

namespace App\Controller\PageController;
use App\Entity\Security\Security;
use Framework\Controller\AbstractController;


class HomePageController extends AbstractController
{
    public function __invoke()
    {
        Security::logout();
        
        return $this->render('homepage/home.html.twig');
    }
}
