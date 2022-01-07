<?php

namespace App\Controller\AdminController;
use App\Entity\Security\Security;
use Framework\Controller\AbstractController;
use App\Repository\UserRepository\UserRepository;


class PlayersAdminController extends AbstractController
{
    public function __invoke(): string
    {
        error_reporting(0);

        $userRepository = UserRepository::getInstance();
        $liste_user = $userRepository->pagination(1);

            if($this->isPost()){
                if ($_POST['btn']=='delete'){
                $id = $_POST['postId'];
                // print_r($id);
                $userRepository->delete($id);
                }
                if ($_POST['btn']=='update'){
                $id = $_POST['postId'];
                // print_r($id);
                header("Location: /admin/playersUpdate?id=$id");
                }
            }
       
        if(Security::isLogged() && $_SESSION['ROLE']['role'] === 'ROLE_ADMIN') {   
            return $this->render('admin/listProfil/listProfil.html.twig', [
                'user' => $liste_user[0],
                'page' => $liste_user[1],
                'title' => 'Liste des joueurs'
            ]);
        } else {
            $this->redirect('/login');
        }
    }
}
