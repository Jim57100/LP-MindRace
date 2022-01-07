<?php

namespace App\Entity\ErrorLog;


abstract class ErrorLog {

  public const COULEUR_ROUGE = "alert-danger";
  public const COULEUR_ORANGE = "alert-warning";
  public const COULEUR_VERTE = "alert-success";


  public static function ajouterMessageAlerte($message,$type) {
    if(empty($_SESSION['alert'])) {

        $_SESSION['alert'][]=[
            "message" => $message,
            "type" => $type
        ];

    }

  }

}