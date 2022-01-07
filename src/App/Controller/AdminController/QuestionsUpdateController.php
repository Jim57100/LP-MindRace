<?php

namespace App\Controller\AdminController;
use App\Entity\Security\Security;
use Framework\Controller\AbstractController;
use App\Repository\AnswerRepository\AnswerRepository;
use App\Repository\QuestionRepository\QuestionRepository;


class QuestionsUpdateController extends AbstractController
{
    public function __invoke(): string
    {
        $questionRepository = QuestionRepository::getInstance();
        $answerRepository = AnswerRepository::getInstance();

        $answers = $answerRepository->findByIdQuestion($_GET['id']);
        $qst = $questionRepository->findOne($_GET['id']);

        if ($this->isPost())
        {
            if ($_POST['btn']=='delete'){
                $id = $_GET['id'];
                $answerRepository->delete($id);
                $questionRepository->delete($id);
            }
            if ($_POST['btn']=='update'){
                $questionRepository->VerifAndUpdate($qst);
                $answerRepository->VerifAndUpdate($qst,$answers);
            }     
        }

        if(Security::isLogged() && $_SESSION['ROLE']['role'] === 'ROLE_ADMIN') {  
            return $this->render('admin/questionsUpdate/questionsUpdate.html.twig', [
                'id' => $_GET['id'],
                'qst' => $qst,
                'answers' => $answers,
            ]);
        } else {
            $this->redirect('/login');
        }
    }
}
