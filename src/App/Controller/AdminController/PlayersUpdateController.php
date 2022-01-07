<?php

namespace App\Controller\AdminController;
use App\Entity\Security\Security;
use Framework\Controller\AbstractController;
use App\Repository\UserRepository\UserRepository;


class PlayersUpdateController extends AbstractController
{
    public function __invoke(): string
    {

        $userRepository = UserRepository::getInstance();
        $user = $userRepository->findOneById($_GET['id']);

        if ($this->isPost())
        {
            $userRepository->VerifAndUpdate($user);
        }

        if(Security::isLogged() && $_SESSION['ROLE']['role'] === 'ROLE_ADMIN') {  
            return $this->render('admin/profilUpdate/profilUpdate.html.twig', [
                'user' => $user,
                
            ]);
        } else {
            $this->redirect('/login');
        }
    }
}
