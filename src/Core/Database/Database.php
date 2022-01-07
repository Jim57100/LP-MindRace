<?php

namespace Framework\Database;

use PDO;
use Framework\DbConfig\DbConfig;

class Database {

  private $db_host;
  private $db_name;
  private $db_user;
  private $db_pass;
  private $pdo;
  

  public function __construct(DbConfig $settings) {

    $this->db_host = $settings->get('db_host');
    $this->db_name = $settings->get('db_name');
    $this->db_user = $settings->get('db_user');
    $this->db_pass = $settings->get('db_pass');
    
  }

  public function getPDO() {

    if($this->pdo === null) { 
      $db = new PDO("{$this->db_host};dbname={$this->db_name}", "{$this->db_user}", "{$this->db_pass}");
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->pdo = $db;
    }

    return $this->pdo;

  }


}