<?php

namespace App\Game;

use PDO;
use Ratchet\ConnectionInterface;
use App\Repository\GamePlayRepository\GamePlayRepository;

class GameTable
{
    /**
     * @var string 
     */
    private string $tableId;

    /**
     * @var TableManager
     */
    private TableManager $tableManager;

    /**
     * @var GamePlayer[]
     */
    private $players = []; // Array of GamePlayer

    /**
     * @var int
     * playerIndex
     */
    private int $currentTurn = 0;

    /**
     * @var bool
     * Verify can game continue.
     */
    private bool $isContinue = true;

    /**
     * @var bool
     * is all player ready.
     */
    private bool $canStart = false;

    public function __construct(string $tableId, TableManager $tableManager)
    {
        $this->tableId = $tableId;
        $this->tableManager = $tableManager; 
    }

    public function getTableId()
    {
        return $this->tableId;
    }

    public function getTableManager()
    {
        return $this->tableManager;
    }

    public function addPlayer(GamePlayer $player)
    {
        $this->players[] = $player;
    }

    public function getPlayers()
    {
        return $this->players;
    }

    public function getIsContinue()
    {
        return $this->isContinue;
    }

    public function setIsContinue(bool $isContinue)
    {
        return $this->isContinue = $isContinue;
    }

    public function getCurrentTurn()
    {
        return $this->currentTurn;
    }

    public function getCanStart()
    {
        return $this->canStart;
    }

    public function findPlayer(int $playerId)
    {
        foreach ($this->getPlayers() as $player) {
            if ($player && $player->getPlayerId() == $playerId) {
                return $player;
            }
        }

        return null;
    }

    public function joinPlayer($playerId, ConnectionInterface $playerConn)
    {
        $allConnected = true;
        $isFound = false;
        foreach ($this->getPlayers() as $player) {
            if ($player && $player->getPlayerId() == $playerId) {
                $player->setIsConnected(true);
                $player->setConnection($playerConn);
                $player->setResourceId($playerConn->resourceId);
                $isFound = true;
            }
            if ($allConnected && !$player->getIsConnected()) {
                $allConnected = false;
            }
        }
        if (count($this->getPlayers()) > 1 && $allConnected) {
            $this->canStart = true;
        }

        return $isFound;
    }

    public function findPlayerByConnection(ConnectionInterface $conn)
    {
        foreach ($this->getPlayers() as $player) {
            if ($player->getResourceId() == $conn->resourceId) {
                return $player;
            }
        }

        return null;
    }

    public function getPlayerResult()
    {
        $result   = [];
        $winTimes = [];

        foreach($this->getPlayers() as $player) {
            $result[]   = $player->toArray();
            $winTimes[] = ($player->getWinTime() ?: (time() + 1));
        }

        print_r($winTimes);

        array_multisort($winTimes, SORT_ASC, SORT_NUMERIC, $result);

        return array_values($result);
    }

    public function setNextPlayerTurn()
    {
        if ($this->getCanStart()) {
            $this->currentTurn++;
            if ($this->currentTurn >= count($this->getPlayers())){
                echo $this->currentTurn;
                $this->currentTurn = 0;
            }
        }

        return $this->getCurrentTurn();
    }

    public function toArray()
    {
        $playersArray = [];
        foreach ($this->getPlayers() as $player) {
            $playersArray[] = $player->toArray();
        }

        return [
            'id' => $this->getTableId(),
            'players' => $playersArray,
            'isContinue' => $this->getIsContinue(),
            'currentTurn' => $this->getCurrentTurn(),
            'canStart' => $this->getCanStart(),
        ];
    }
}
