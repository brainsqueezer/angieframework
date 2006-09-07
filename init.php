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
  
  require ANGIE_PATH . '/Angie.class.php';
  require ANGIE_PATH . '/engine/Angie_Engine.class.php';
  require ANGIE_PATH . '/controller/Angie_Controller.class.php';
  require ANGIE_PATH . '/controller/Angie_Request.class.php';
  
  require ANGIE_PATH . '/error/Angie_Error.class.php';
  require ANGIE_PATH . '/error/core/Angie_Error_Core_InvalidInstance.class.php';
  require ANGIE_PATH . '/error/file_system/Angie_Error_FileSystem_FileDnx.class.php';

?>