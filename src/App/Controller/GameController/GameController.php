<?php

namespace App\Controller\GameController;

use App\Entity\Security\Security;
use Framework\Controller\AbstractController;
use Framework\Config\Config;

class GameController extends AbstractController
{
    public function __invoke()
    {
      $token = isset($_GET['token']) ? $_GET['token'] : "";               //attrape le token
      $token && $_SESSION['GAME_TOKEN'] = $token;                         //store le token en session et dans la variable

      if(Security::isLogged() && ($_SESSION['ROLE']['role'] === 'ROLE_ADMIN' || $_SESSION['ROLE']['role'] === 'ROLE_USER')) {
      
        $serverPort = Config::get('PLAY_STATION_PORT', 8080);             //voir fichier config
        $host       = Config::get('PLAY_STATION_HOST', isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost');
        
        if (empty($token)) {
          $token = $_SESSION['GAME_TOKEN'];
        }
        
        unset($_SESSION['GAME_TOKEN']);
        
        //on kick si pas de token
        if (empty($token)) {
          return $this->redirect('/login');
        }

        //on decode le token en base 64
        $tokenInfo = json_decode(base64_decode($token), true);
        if ($tokenInfo['userId'] != $_SESSION['USER_ID']) {           //si on ne retrouve l'id dans le token
          return $this->redirect('/login?error=invalid_user_token');  
        }
        return $this->render('game/game.html.twig', [
          'brokerHost' => "ws://{$host}:{$serverPort}",               //le HTTP du WS
          'gameToken'  => $token,
        ]);
      } else {
        $this->redirect('/login');
      }
    }
}
