<?php
namespace App\Game;

use Ratchet\ConnectionInterface;

class GameClient
{
    /**
     * @var mixed
     */
    protected $resourceId;

    /**
     * @var ConnectionInterface
     */
    protected $connection;


    public function __construct()
    {
        
    }

    /**
     * @return mixed
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * @param mixed $resourceId
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function setConnection(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

}
