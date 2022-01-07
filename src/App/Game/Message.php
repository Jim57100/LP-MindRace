<?php 

namespace App\Game;

use Exception;

class Message
{
    //Les codes de la bombe atomique
    const PLAYER_CONNECTED    = 10002;
    const PLAYER_SPELL        = 10003;
    const QUESTION_SELECTED   = 10004;
    const ANSWER_REPLAY       = 10005;
    const ANSWER_VERIFICATION = 10006;
    const PLAYER_STATUS       = 10007;
    const ERROR_CAN_NOT_JOIN  = 10008;
    const GAME_RESULT         = 10009;

    private function __construct()
    {
        
    }

    public static function send(int $type, array $params)
    {
        $status = false;
        switch ($type) {
            case self::PLAYER_CONNECTED:
                $status = self::playerConnected($params);
                break;
            case self::PLAYER_SPELL:
                $status = self::setPlayerSpell($params);
                break;
            case self::QUESTION_SELECTED:
                $status = self::questionSelected($params);
                break;
            case self::ANSWER_REPLAY:
                $status = self::sendAnswerReply($params);
                break;
            case self::ANSWER_VERIFICATION:
                $status = self::askForAnswerVerification($params);
                break;
            case self::PLAYER_STATUS:
                $status = self::sendCurrentPlayerStatus($params);
                break;
             case self::GAME_RESULT:
                $status = self::sendGameResult($params);
                break;
            case self::ERROR_CAN_NOT_JOIN:
                $status = self::sendErrorCannotJoin($params);
                break;

            default:
                throw new Exception("Message type not available");
                break;
        }

        return $status;
    }

    private static function buildMessage($action, array $params)
    {
        return json_encode([
            'action' => $action,
            'params' => $params,
        ]);
    }

    private static function sendMessage(GameClient|null $client, string $message)
    {
        if ($client && $client->getConnection()) {
            echo "Message sending | Resource: " . $client->getResourceId() . ", Message: " . $message . PHP_EOL;

            $client->getConnection()->send($message);
        }
    }

    private static function sendMessageToAllTablePlayers(GameTable $table, string $message)
    {
        foreach ($table->getPlayers() as $player) {
            if ($player && $player->getIsConnected()) {
                self::sendMessage($player, $message);
            }
        }

        self::sendMessage($table->getTableManager(), $message);

        return true;
    }

    private static function playerConnected(array $params)
    {

        if (!$params['table'] || !$params['connectedUserId']) {
            return false;
        }

        $table           = $params['table'];
        $connectedUserId = $params['connectedUserId'];
        $message         = self::buildMessage('player_connected', [
            'userId'   => $connectedUserId,
            'table'    => $table->toArray(),
        ]);

        return self::sendMessageToAllTablePlayers($table, $message);
    }

    private static function setPlayerSpell(array $params)
    {
        if (!$params['table']) {
            return false;
        }

        $table           = $params['table'];
        $getSpellMessage = self::buildMessage('get_spell', [
            'currentTurn' => $table->getCurrentTurn(),
        ]);
        $setSpellMessage = self::buildMessage('set_spell', [
            'currentTurn' => $table->getCurrentTurn(),
        ]);
        foreach ($table->getPlayers() as $index => $player) {
            if ($table->getCurrentTurn() == $index) {
                self::sendMessage($player, $getSpellMessage);
            } else {
                self::sendMessage($player, $setSpellMessage);
            }
        }

        self::sendMessage($table->getTableManager(), $setSpellMessage);

        return true;
    }

    private static function questionSelected(array $params)
    {
        if (!$params['questionSet'] || !$params['table']) {
            return false;
        }
        $questionSet = $params['questionSet'];
        $table       = $params['table'];
        $message     = self::buildMessage('set_question', [
            'questionSet' => $questionSet,
        ]);

        return self::sendMessageToAllTablePlayers($table, $message);
    }

    private static function sendAnswerReply(array $params)
    {
        if (!$params['player'] || !$params['table']) {
            return false;
        }

        $isCorrectAnswer = $params['isCorrectAnswer'];
        $player          = $params['player'];
        $table           = $params['table'];
        $message         = self::buildMessage('answer_reply', [
            'answer'          => $player->getChosenAnswer(),
            'isCorrectAnswer' => $isCorrectAnswer,
            'playerId'        => $player->getPlayerId(),
        ]);

        return self::sendMessageToAllTablePlayers($table, $message);
    }

    private static function askForAnswerVerification(array $params)
    {
        if (!$params['manager'] || !$params['answer']) {
            return false;
        }

        $manager = $params['manager'];
        $message = self::buildMessage('ask_answer_verification', [
            'answer' => $params['answer'],
        ]);

        self::sendMessage($manager, $message);

        return true;
    }

    private static function sendCurrentPlayerStatus(array $params)
    {
        if (!$params['table']) {
            return false;
        }

        $table   = $params['table'];
        $message = self::buildMessage('update_status', [
            'table'    => $table->toArray(),
        ]);

        return self::sendMessageToAllTablePlayers($table, $message);
    }

    private static function sendErrorCannotJoin(array $params)
    {
        if (!$params['player']) {
            return false;
        }

        $player = $params['player'];
        $error  = $params['error'];
        $message = self::buildMessage('error_can_not_join', [
            'reason' => $error,
        ]);

        self::sendMessage($player, $message);
    }

    private static function sendGameResult(array $params)
    {
        if (!$params['result'] || !$params['table']) {
            return false;
        }

        $result  = $params['result'];
        $table   = $params['table'];
        $message = self::buildMessage('player_result', [
            'result' => $result,
        ]);

        return self::sendMessageToAllTablePlayers($table, $message);
    }


}
