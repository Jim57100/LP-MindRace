<?php

namespace App\Controller\AdminController;
use App\Entity\Security\Security;
use Framework\Controller\AbstractController;
use App\Repository\AnswerRepository\AnswerRepository;
use App\Repository\QuestionRepository\QuestionRepository;


class QuestionsAdminController extends AbstractController
{
    public function __invoke(): string
    {
        error_reporting(0);

        //Récupérer toutes les questions
        $questionRepository = QuestionRepository::getInstance();
        $liste_qst = $questionRepository->pagination(1);
        
        //Récupérer toutes les réponses 
        $answerRepository = AnswerRepository::getInstance();
        $liste_rep = $answerRepository->findAll();

        if($this->isPost()){
            if ($_POST['btn']=='delete'){
                $id = $_POST['postId'];
                $answerRepository->delete($id);
                $questionRepository->delete($id);
            }
            if ($_POST['btn']=='update'){
                $id = $_POST['postId'];
                header("Location: /admin/questionsUpdate?id=$id");
            }
            if ($_POST['pagination']){
                $pages=$_POST['pagination'];
                $liste_qst = $questionRepository->pagination($pages);
            }
        }
        
        if(Security::isLogged() && $_SESSION['ROLE']['role'] === 'ROLE_ADMIN') {  
            return $this->render('admin/questions/questions.html.twig', [
                'question' => $liste_qst[0],
                'answer' => $liste_rep,
                'page' => $liste_qst[1],
                'title' => 'liste des questions'
            ]);
        } else {
            $this->redirect('/login');
        }
    }
}
