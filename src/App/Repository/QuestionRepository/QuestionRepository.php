<?php

namespace App\Repository\QuestionRepository;
use PDO;
use App\Entity\ErrorLog\ErrorLog;
use App\Repository\AbstractRepository;
use App\Repository\UserRepository\UserRepository;


class QuestionRepository extends AbstractRepository
{
    
    /**
     * Method VerifAndUpdate
     *
     * @param $qst $qst
     *
     * @return void
     */
    public function VerifAndUpdate($qst)
    {
        $label = $_POST['label'];
        $level = $_POST['level'];
        
        if (empty($label)){
            $label = $qst['label'];
        }
        if (empty($level)) {
            $level = $qst['level'];
        }
        $this->update($_GET['id'],$label,$level);
        header("Location: /admin/questions");
        ErrorLog::ajouterMessageAlerte("Vos modifications ont été prises en compte", ErrorLog::COULEUR_ROUGE);
    } 

    
    /**
     * Method update
     *
     * @param $id $id [explicite description]
     * @param $label $label [explicite description]
     * @param $level $level [explicite description]
     *
     * @return array
     */
    public function update($id,$label,$level)
    {
        $pdo = self::$pdo;
        $req = "UPDATE questions SET label = :label, level = :level WHERE id = :id";
        $stmt = $pdo->prepare($req);
        $stmt->bindValue(":id",  $id, PDO::PARAM_STR);
        $stmt->bindValue(":label",  $label, PDO::PARAM_STR);
        $stmt->bindValue(":level",  $level, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }

    
    /**
     * Method findOne
     *
     * @param $id $id [explicite description]
     *
     * @return array
     */
    public function findOne($id) 
    {
        $pdo = self::$pdo;
        //récupération des données d'une question
        $req = "SELECT * FROM questions WHERE id = :id";
        $stmt = $pdo->prepare($req);
        $stmt->bindValue(":id",  $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }
    
    /**
     * Method findAll
     *
     * @return array
     */
    public function findAll() 
    {
        $pdo = self::$pdo;
        $req = "SELECT * FROM questions";
        $stmt = $pdo->prepare($req);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }

        
    /**
     * Method delete
     *
     * @param $id 
     *
     * @return void
     */
    public function delete($id)
    {
        $pdo = self::$pdo;
        $req = "DELETE FROM questions WHERE id = :id";
        $stmt = $pdo->prepare($req);
        $stmt->bindValue(":id",  $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
        header("Location: /admin/questions");
        ErrorLog::ajouterMessageAlerte("La question a bien été supprimée", ErrorLog::COULEUR_ROUGE);
    }

        
    /**
     * Method pagination
     *
     * @param $currentPage
     *
     */
    public function pagination(int $currentPage) :array
    {
        $pdo = self::$pdo;
        
        // On détermine le nombre total d'articles
        $sql = 'SELECT COUNT(*) AS nb_questions FROM `questions`;';
        $query = $pdo->prepare($sql);
        $query->execute();
        $result = $query->fetch();
        
        $nbArticles = (int) $result['nb_questions'];
        
        // On détermine le nombre d'articles par page
        $parPage = 12;
        
        // On calcule le nombre de pages total
        $pages = ceil($nbArticles / $parPage);
        
        // Calcul du 1er article de la page
        $premier = ($currentPage * $parPage) - $parPage;
        
        $sql = 'SELECT * FROM `questions` LIMIT :premier, :parpage;';
        
        // On prépare la requête
        $query = $pdo->prepare($sql);
        
        $query->bindValue(':premier', $premier, PDO::PARAM_INT);
        $query->bindValue(':parpage', $parPage, PDO::PARAM_INT);
        
        // On exécute
        $query->execute();
        
        // On récupère les valeurs dans un tableau associatif
        $articles = $query->fetchAll(PDO::FETCH_ASSOC);

        $tab[0] = $articles;
        $tab[1] = $pages;
        
        // return $articles;
        return $tab;
    }

}