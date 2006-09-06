<?php

  /**
  * Load Angie resources and prepare the environment. This file will include all system resources and
  * make sure that they are initialized properly
  *
  * @package Angie
  * @author Ilija Studen <ilija.studen@gmail.com>
  */

  define('ANGIE_PATH', dirname(__FILE__));
  
  set_include_path(''); // don't rely on include path
  
  require ANGIE_PATH . '/core/constants.php';
  require ANGIE_PATH . '/core/functions.general.php';
  require ANGIE_PATH . '/core/functions.files.php';
  require ANGIE_PATH . '/core/functions.web.php';
  require ANGIE_PATH . '/core/functions.utf.php';
  require ANGIE_PATH . '/core/functions.utils.php';

?>