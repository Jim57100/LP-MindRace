<?php

namespace App\Game;

use PDO;
use Ratchet\ConnectionInterface;
use App\Repository\GamePlayRepository\GamePlayRepository;

class PlayStation
{
    const PLAYER_TYPE_MANAGER = 'manager';
    const PLAYER_TYPE_GUEST   = 'player';

    /**
     * @var GameTable[]
     */
    private $gameTables = [];

    private $usedTables = [];

    public function __construct()
    {
        
    }

    public function addTable(GameTable $gameTable)
    {
        $this->gameTables[] = $gameTable;
    }

    public function findTable(string $tableId)
    {
        foreach ($this->gameTables as $table) {
            if ($table && $table->getTableId() === $tableId) {
                return $table;
            }
        }

        return null;
    }

    public function findTableAndPlayer(ConnectionInterface $conn)
    {
        foreach ($this->gameTables as $table) {
            $player = $table->findPlayerByConnection($conn);
            if ($player) {
                return [$table, $player];
            }
        }

        return [null, null];
    }

    public function removeTable(string $tableId)
    {
        if (!$tableId) {
            return false;
        }

        foreach ($this->gameTables as $index => $table) {
            if ($table->getTableId() == $tableId) {
                unset($this->gameTables[$index]);
                return true;
            }
        }

        return false;
    }




    public function findTableByManagerConnection(ConnectionInterface $conn)
    {
        foreach ($this->gameTables as $table) {
            if ($table->getTableManager()->getResourceId() == $conn->resourceId) {
                return $table;
            }
        }

        return null;
    }

    public function createGameTable(array $params, ConnectionInterface $tableMasterConn)
    {
        $tableId      = $params['tableId'];
        $managerId    = (int) $params['userId'];
        $tableManager = new TableManager($tableMasterConn, $managerId);

        if (!$managerId || !$tableId) {
            Message::send(Message::ERROR_CAN_NOT_JOIN, [
                'player' => $tableManager,
                'error'  => 'invalid_table',
            ]);
            $tableMasterConn->close();

            return false;
        }

        if (in_array($tableId, $this->usedTables)) {
            Message::send(Message::ERROR_CAN_NOT_JOIN, [
                'player' => $tableManager,
                'error'  => 'table_already_used',
            ]);
            $tableMasterConn->close();

            return false;
        }

        $table = new GameTable($tableId, $tableManager);

        if (!empty($params['players']) && is_array($params['players'])) {
            foreach ($params['players'] as $player) {
                $table->addPlayer(new GamePlayer(
                    $player['id'],
                    $player['pawn']
                ));
            }
        }
        
        $this->addTable($table);

        $this->usedTables[] = $tableId;
    }

    public function joinTable(array $params, ConnectionInterface $playerConn)
    {
        $tableId      = $params['tableId'];
        $playerId     = $params['userId'];
        $table        = $this->findTable($tableId);
        $playerStatus = false;

        if ($table && $table->getIsContinue() && !$table->getCanStart()) {
            $playerStatus = $table->joinPlayer($playerId, $playerConn);
        }
        
        if ($playerStatus) {
            $sentMessage = Message::send(Message::PLAYER_CONNECTED, [
                'table' => $table,
                'connectedUserId' => $playerId, 
            ]);

            if ($sentMessage && $table->getCanStart()) {
                $sentMessage = Message::send(Message::PLAYER_SPELL, [
                    'table' => $table,
                    'connectedUserId' => $playerId, 
                ]);
            }
        } else {
            $errorCode = 'invalid_table';
            if (!$table && in_array($playerId, $this->usedTables)) {
                $errorCode = 'game_over';
            } else if ($table && $table->getCanStart()) {
                $errorCode = 'already_started';
            } else if ($table) {
                $errorCode = 'invalid_player';
            }

            $newPlayer = new GamePlayer(0, "");
            $newPlayer->setConnection($playerConn);
            $newPlayer->setResourceId($playerConn->resourceId);
            Message::send(Message::ERROR_CAN_NOT_JOIN, [
                'player' => $newPlayer,
                'error'  => $errorCode,
            ]);
            $playerConn->close();
            return false;
        }

        return $playerStatus;
    }

    public function selectQuestion(array $params, ConnectionInterface $conn)
    {
        $tableId = $params['tableId'];
        $playerId = $params['userId'];
        $difficulty = $params['difficulty'];

        $table = $this->findTable($tableId);
        if (!$table) {
            return false;
        }

        $player = $table->findPlayer($playerId);

        if (!$player) {
            return false;
        }

        $questionSet = $player->selectQuestion($difficulty);
        $sentMessage = Message::send(Message::QUESTION_SELECTED, [
            'table'      => $table,
            'questionSet' => $questionSet,
        ]);

        return true;
    }

    public function giveAnswer(array $params, ConnectionInterface $conn) {
        $tableId  = $params['tableId'];
        $playerId = $params['userId'];
        $answer   = (int) $params['answer'];

        if (!$answer) {
            return false;
        }

        $table = $this->findTable($tableId);
        if (!$table) {
            return false;
        }

        $isSpellFinish = false;

        $player = $table->findPlayer($playerId);
        if (!$player->needAnswerVerification()) {
            $isCorrectAnswer = $player->chooseAnswer($answer);
            $sentMessage = Message::send(Message::ANSWER_REPLAY, [
                'isCorrectAnswer' => $isCorrectAnswer,
                'table'           => $table,
                'player'          => $player,
            ]);

            $isSpellFinish = true;
        } /* else {
            $sentMessage = Message::send(Message::ANSWER_VERIFICATION, [
                'manager' => $table->getTableManager(),
                'answer'  => $answer,
            ]);

        } */

        if ($isSpellFinish) {
            $this->playerSpellFinished($table);
        }
    }

    public function answerVerification(array $params, ConnectionInterface $conn)
    {
        $tableId         = $params['tableId'];
        $playerId        = $params['userId'];
        $isCorrectAnswer = (bool) $params['isCorrectAnswer'];

        $table = $this->findTable($tableId);
        if (!$table) {
            return false;
        }

        foreach ($table->getPlayers() as $index => $player) {
            if ($table->getCurrentTurn() == $index) {
                $isCorrectAnswer = $player->chooseAnswer($player->getChosenAnswer(), $isCorrectAnswer);
                $sentMessage     = Message::send(Message::ANSWER_REPLAY, [
                    'isCorrectAnswer' => $isCorrectAnswer,
                    'table'           => $table,
                    'player'          => $player,
                ]);
                break;
            }
        }

        $this->playerSpellFinished($table);
    }

    private function playerSpellFinished(GameTable $table)
    {
        $table->setNextPlayerTurn();
        $playerId       = 0;
        $winPlayerCount = 0;
        $statusMessage  = Message::send(Message::PLAYER_STATUS, [
            'table' => $table,
        ]);

        foreach ($table->getPlayers() as $index => $player) {
            if ($player->getWinTime()) {
                $winPlayerCount++;
            }
            if ($table->getCurrentTurn() == $index) {
                if ($player->getWinTime()) {
                    $table->setNextPlayerTurn();
                }
                $playerId = $player->getPlayerId();
            }
        }

        if ($winPlayerCount == (count($table->getPlayers()) - 1)) {
            $table->setIsContinue(false);
            $resultMessage = Message::send(Message::GAME_RESULT, [
                'result' => $table->getPlayerResult(),
                'table'  => $table,
            ]);
            $this->removeTable($table->getTableId());
            $this->disConnectAllPlayers($table);
            unset($table);
            
            return true;
        }
        
        $spellMessage = Message::send(Message::PLAYER_SPELL, [
            'table' => $table,
            'connectedUserId' => $playerId, 
        ]);

        return true;
    }

    private function disConnectAllPlayers(GameTable $table)
    {
        foreach($table->getPlayers() as $player) {
            $player->getConnection() && $player->getConnection()->close();
        }
        $table->getTableManager()->getConnection()->close();
    }
}
