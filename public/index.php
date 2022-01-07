<?php
  session_start();
  

  use Framework\Kernel;
  
  require_once(__DIR__ . '/../vendor/autoload.php');
  
	
  //Kernel
  $kernel = new Kernel();
  $kernel->handle($_SERVER['REQUEST_METHOD'], explode('?', $_SERVER['REQUEST_URI'])[0]);

  






