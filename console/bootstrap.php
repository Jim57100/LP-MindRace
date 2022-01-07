<?php
  if (php_sapi_name() !== 'cli') {
      throw new Exception("Request not accept from Browser");
  }

  require_once(__DIR__ . '/../vendor/autoload.php');
