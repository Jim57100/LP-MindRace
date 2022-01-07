<?php
namespace App\Game;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Exception;

class GamePlay implements MessageComponentInterface
{
    private PlayStation $playStation;

    public function __construct()
    {
        $this->playStation = new PlayStation();
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {

    }

    /**
     * @param ConnectionInterface $conn
     * @param string $msg
     * @throws Exception
     */
    public function onMessage(ConnectionInterface $conn, $message)
    {
        echo "Message received | Resource: " . $conn->resourceId . ", Message: " . $message . PHP_EOL;

        if ($message == "ping") {
            $this->handleHeartbeat($conn);
            return;
        }

        $message = json_decode($message, true);

        if (!isset($message['action'])) {
            throw new Exception('No action specified');
        }
        $action = $message['action'];
        $params = isset($message['params']) && is_array($message['params']) ? $message['params'] : [];
        $method = "handle" . ucfirst($action);
        if (!method_exists($this, $method)) {
            throw new Exception('Invalid action. Usage: '. $action);
        }

        $this->$method($params, $conn);
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->closeClientConnection($conn);
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->closeClientConnection($conn);
        $conn->close();
    }

    /** Started Handle methods */

    protected function handleCreate(array $params, ConnectionInterface $conn)
    {
        $this->playStation->createGameTable($params, $conn);
    }

    protected function handleJoinPlayer(array $params, ConnectionInterface $conn)
    {
        $this->playStation->joinTable($params, $conn);
    }

    protected function handleGetQuestion(array $params, ConnectionInterface $conn)
    {
        $this->playStation->selectQuestion($params, $conn);
    }

    protected function handleSetAnswer(array $params, ConnectionInterface $conn)
    {
        $this->playStation->giveAnswer($params, $conn);
    }

    protected function handleSendVerification(array $params, ConnectionInterface $conn)
    {
        $this->playStation->answerVerification($params, $conn);
    }

    protected function handleHeartbeat(ConnectionInterface $conn)
    {
        echo "Pong For " . $conn->resourceId . PHP_EOL;
    }

    /** Ended Handle methods */

    /**
     * @param ConnectionInterface $conn
     */
    protected function closeClientConnection(ConnectionInterface $conn)
    {
        $table = $this->findTableByManagerConnection($conn);

        if ($table) {
            $this->playStation->announceManagerLeft($table);

            return true;
        }

        list($table, $player) = $this->findTableAndPlayer($conn);

        if ($table && $player) {
            $this->playStation->announcePlayerLeft($table, $player);

            return true;
        }

        return false;
    }

    /**
     * @param ConnectionInterface $conn
     */
    protected function findTableAndPlayer(ConnectionInterface $conn)
    {
        return $this->playStation->findTableAndPlayer($conn);
    }

    /**
     * @param ConnectionInterface $conn
     */
    protected function findTableByManagerConnection(ConnectionInterface $conn)
    {
        return $this->playStation->findTableByManagerConnection($conn);
    }
}
