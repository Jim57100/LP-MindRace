<?php

namespace App\Controller\AdminController;
use App\Entity\Security\Security;
use Framework\Controller\AbstractController;
use App\Repository\UserRepository\UserRepository;
use App\Repository\QuestionRepository\QuestionRepository;


class AdminController extends AbstractController
{
    public function __invoke()
    {
        //Récupérer toutes les questions
        $qst = QuestionRepository::getInstance();
        $liste_qst = count($qst->findAll());
        //Récupérer tous les joueurs
        $user = UserRepository::getInstance();
        $liste_user = count($user->findAll());


        if(Security::isLogged() && $_SESSION['ROLE']['role'] === 'ROLE_ADMIN') {
            return $this->render('admin/admin.html.twig', [
                "title" => "Administration",
                'liste_user' => $liste_user,
                'liste_question' => $liste_qst,
                'admin' => true
            ]);
        } else {
            $this->redirect('/login');
        }

    }
}
