<?php

namespace App\Controller\PageController;

use App\Entity\ErrorLog\ErrorLog;
use App\Entity\Security\Security;
use Framework\Controller\AbstractController;
use App\Repository\UserRepository\UserRepository;


class InvitationPageController extends AbstractController
{
  public function __invoke()
  {
    
    if($this->isPost()) {

    /**
     * Autocomplete
     */
      if(!empty($_POST['username'])) {
        
        $username = $_POST['username'];
        $result = UserRepository::getInstance()->userAutoComplete($username);

        $replace_string = '<b>'.$username.'</b>';
        
        $data = [];

        foreach ($result as $row) {
          $data[] = array('username' => str_ireplace($username, $replace_string, $row['username']));
        }
        return json_encode($data);

      }
        

      /**
       * Send players usernames and colors
       */
      if(!empty(array_filter($_POST['players'])) && count((array_filter($_POST['players']))) > 1 ) {

        $players = $_POST['players'];
        $color = $_POST['color'];
        $cpt = 0;
        $verif = true;

        for ($i = 0 ; $i <= count($players)-1 ; $i++){
          if ($players[$i] != '') $cpt++;
        }

        for ($i = 0 ; $i <= $cpt ; $i++){
          if(((!empty($players[$i]))&&(empty($color[$i]))) || ((empty($players[$i]))&&(!empty($color[$i])))){
            ErrorLog::ajouterMessageAlerte('Veuillez ajouter une couleur à chaque joueur', ErrorLog::COULEUR_ORANGE);
            $verif = false;
          }
        }
        
        //Générer url avec token de n° de jeu
        if ( $verif == true){
          $qs = '/qrcode?'.http_build_query(['players' => $players, 'color' => $color, 'table' => uniqid().rand(1111, 9999).rand(33333, 99999)], '');
          header('Location:'.$qs);
        }
                  
      } else {
        ErrorLog::ajouterMessageAlerte('Veuillez inviter au moins 2 joueurs', ErrorLog::COULEUR_ORANGE);
      }
        
    }

      
    if(Security::isLogged()) {
      return $this->render('invitation/invitation.html.twig');
    } else {
      $this->redirect('/login');
    }
    
  }
}
