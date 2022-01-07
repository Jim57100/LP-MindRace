<?php

namespace Framework\DbConfig;

class DbConfig {

  private $settings = [];
  private static $_instance;

  public function __construct()
  {
    $this->settings = require dirname(__DIR__) . '../../../config/app.local.php';
  }

  //Singleton
  public static function getInstance() {
    
    if(is_null(self::$_instance)) {
      self::$_instance = new DbConfig();
    }
    return self::$_instance;
  }

  //récupération des clés de app.local.php
  public function get($key) {

    if(!isset($this->settings[$key])) {
      return null;
    }
    return $this->settings[$key];
  }

}