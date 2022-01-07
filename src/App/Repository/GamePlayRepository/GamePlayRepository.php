<?php

namespace App\Repository\GamePlayRepository;

use PDO;
use App\Repository\AbstractRepository;
use Exception;

class GamePlayRepository extends AbstractRepository
{   
    public function selectPlayerQuestion($difficulty, array $previousQuestions): array
    {
        $questionStmt = self::$pdo->prepare("SELECT id, label FROM questions where level = ? AND id NOT IN (?) ORDER BY RAND() LIMIT 1;");
        $answerStmt   = self::$pdo->prepare("SELECT id, label FROM answers where id_question = ?;");

        $questionStmt->execute([
            $difficulty,
            $previousQuestions ? implode(", ", $previousQuestions) : "0",
        ]);
        $question = $questionStmt->fetch(PDO::FETCH_ASSOC);
        $questionStmt->closeCursor();

        if (!$question || !$question['id']) {
            throw new Exception('Failed to get valid question.');
        }

        $answerStmt->execute([
            $question['id'],
        ]);

        $question['answers']          = $answerStmt->fetchAll(PDO::FETCH_ASSOC);
        $question['needVerification'] = count($question['answers']) <= 1;

        $answerStmt->closeCursor();

        return $question;
    }
}
