<?php

require_once(__DIR__ . '/bootstrap.php');

use App\Game\GameServer;

$server = GameServer::run();