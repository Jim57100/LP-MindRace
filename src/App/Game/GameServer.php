<?php
namespace App\Game;

use Framework\Config\Config;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class GameServer
{
    /**
     * @param PlayStation $chatServer
     * @param int $port
     * @param string $ip
     * @return IoServer
     */
    public static function run()
    {
        $gamePlay = new GamePlay();
        $wsServer = new WsServer($gamePlay);
        $http = new HttpServer($wsServer);
        $port = Config::get('PLAY_STATION_PORT', 8080);
        $ipAddress = Config::get('PLAY_STATION_HOST', '0.0.0.0');
        $server = IoServer::factory($http, $port, $ipAddress);

        echo "Starting server with port: {$ipAddress} : {$port}".PHP_EOL;

        $server->run();
        return $server;
    }

}
