<?php

namespace App\Game;

use PDO;
use App\Repository\GamePlayRepository\GamePlayRepository;
use App\Repository\AnswerRepository\AnswerRepository;

class GamePlayer extends GameClient
{
    const MAX_STEPS = 48;
 
    private int    $playerId;
    private string $pawn;
    private string $playerName = "";
    private int    $question = 0;
    private        $previousQuestions = [];
    private int    $questionDifficulty;
    private int    $step = 0;
    private int    $chosenAnswer = 0;
    private bool   $isConnected = false;
    private string|null $winTime = null;

    public function __construct(int $playerId, string $pawn)
    {
        parent::__construct();

        $this->playerId = $playerId;
        $this->pawn     = $pawn;
    }

    public function getPlayerId()
    {
        return $this->playerId;
    }

    public function getPlayerName()
    {
        return $this->playerName;
    }

    public function getPawn()
    {
        return $this->pawn;
    }

    public function getQuestionDifficulty()
    {
        return $this->questionDifficulty;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function getPreviousQuestions()
    {
        return $this->previousQuestions;
    }

    public function getStep()
    {
        return $this->step;
    }

    public function getChosenAnswer()
    {
        return $this->chosenAnswer;
    }

    public function getIsConnected()
    {
        return $this->isConnected;   
    }

    public function setIsConnected(bool $isConnected)
    {
        $this->isConnected = $isConnected;   
    }

    public function selectQuestion($difficulty): array
    {
        $questionSet = GamePlayRepository::getInstance()->selectPlayerQuestion($difficulty, $this->previousQuestions);
        $this->questionDifficulty  = (int) $difficulty;
        $this->question            = (int) $questionSet['id'];
        $this->previousQuestions[] = $this->question;
        $questionSet['difficulty'] = $this->questionDifficulty;

        return $questionSet;
    }

    public function getWinTime()
    {
        return $this->winTime;
    }

    public function chooseAnswer(int $answerId, bool $isVerified = true) : bool
    {
        $this->chosenAnswer = $answerId;
        $isCorrect = false;
        if ($this->needAnswerVerification()) {
            $isCorrect = $isVerified;
        } else {
            $answers = AnswerRepository::getInstance()->findByIdQuestion($this->getQuestion());
            if (!$answers){
                $isCorrect = true;
            } else {
                foreach($answers as $answer) {
                    if ($answer['id'] == $answerId) {
                        $isCorrect = ($answer['valid'] == 1);
                        break;
                    }
                }
            }
        }

        $this->step = $this->step + ($this->questionDifficulty * ($isCorrect ? 1 : -1));
        if (self::MAX_STEPS <= $this->step) {
            $this->winTime = time();
            $this->step    = self::MAX_STEPS;
        }
        if ($this->step <= 0) {
            $this->step = 0;
        }

        return $isCorrect;
    }

    public function needAnswerVerification() : bool
    {
        $answers = AnswerRepository::getInstance()->findByIdQuestion($this->getQuestion());
        if (count($answers) == 1) {
            return true;
        }

        return false;
    }

    public function toArray()
    {
        return [
            'id'          => $this->getPlayerId(),
            'name'        => $this->getPlayerName(),
            'pawn'        => $this->getPawn(),
            'isConnected' => $this->getIsConnected(),
            'step'        => $this->getStep(),
        ];
    }
}
