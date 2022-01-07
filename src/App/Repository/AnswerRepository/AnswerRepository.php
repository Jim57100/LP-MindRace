<?php

namespace App\Repository\AnswerRepository;

use PDO;
use App\Entity\ErrorLog\ErrorLog;
use App\Repository\AbstractRepository;

class AnswerRepository extends AbstractRepository
{
  public function VerifAndUpdate($qst,$answers)
  {
    if ($qst['answers'] == 1)
    {
        $answerPost1  = $_POST[$answers[0]['id']];
        if (empty($answerPost1)) $answerPost1 = $answers[0]['label'];
        if (strlen($answerPost1)<256) {
            $this->update($answers[0]['id'],$answerPost1);
        }
    }
    elseif ($qst['answers'] == 2)
    {
          $answerPost1  = $_POST[$answers[0]['id']];
          $answerPost2  = $_POST[$answers[1]['id']];
          if (empty($answerPost1)) $answerPost1 = $answers[0]['label'];
          if (empty($answerPost2)) $answerPost2 = $answers[1]['label'];
          if ((strlen($answerPost1)<256) && (strlen($answerPost2)<256))
          {
            $this->update($answers[0]['id'],$answerPost1);
            $this->update($answers[1]['id'],$answerPost2);
          }
        }
        elseif ($qst['answers'] == 3)
        {
          $answerPost1  = $_POST[$answers[0]['id']];
          $answerPost2  = $_POST[$answers[1]['id']];
          $answerPost3  = $_POST[$answers[2]['id']];
          if (empty($answerPost1)) $answerPost1 = $answers[0]['label'];
          if (empty($answerPost2)) $answerPost2 = $answers[1]['label'];
          if (empty($answerPost3)) $answerPost3 = $answers[2]['label'];
          if ((strlen($answerPost1)<256) && (strlen($answerPost2)<256) && (strlen($answerPost3)<256)) 
          {
            $this->update($answers[0]['id'],$answerPost1);
            $this->update($answers[1]['id'],$answerPost2);
            $this->update($answers[2]['id'],$answerPost3); 
          }
          else ErrorLog::ajouterMessageAlerte("Une de vos réponses dépassent la longueur autorisée (256 caractères)", ErrorLog::COULEUR_ROUGE);
        }
  }

  public function update($id,$label)
  {
    $pdo = self::$pdo;
    //mise à jour d'une question
    $req = "UPDATE answers SET label = :label WHERE id = $id";
    $stmt = $pdo->prepare($req);
    $stmt->bindValue(":label",  $label, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $result;
  }

  public function delete($id)
  {
      $pdo = self::$pdo;
      //suppression des réponses pour la question à supprimer
      $req = "DELETE FROM answers WHERE id_question = :id";
      $stmt = $pdo->prepare($req);
      $stmt->bindValue(":id",  $id, PDO::PARAM_INT);
      $stmt->execute();
      $stmt->closeCursor();
  }

  public function findByIdQuestion($id_question)
  {
      $pdo = self::$pdo;
      $req = "SELECT * FROM answers WHERE id_question = $id_question";
      $stmt = $pdo->prepare($req);
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  
      $stmt->closeCursor();
      return $result;
  }

  public function findAll()
  {
      $pdo = self::$pdo;
      $req = "select * from answers";
      $stmt = $pdo->prepare($req);
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  
      $stmt->closeCursor();
      return $result;
  }

}