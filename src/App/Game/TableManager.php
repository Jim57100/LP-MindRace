<?php

namespace App\Game;

use PDO;
use App\Repository\GamePlayRepository\GamePlayRepository;
use Ratchet\ConnectionInterface;

class TableManager extends GameClient
{
    private int $id;

    public function __construct(ConnectionInterface $conn, int $id)
    {
        parent::__construct();

        $this->id = $id;
        $this->setResourceId($conn->resourceId);
        $this->setConnection($conn);
    }

    public function getId()
    {
        return $this->id;
    }
}
