<?php

  /**
  * Load Angie resources and prepare the environment
  * 
  * This file will include all system resources and make sure that they are initialized properly. There is no external 
  * configuration required for the framework process.
  *
  * @package Angie
  * @author Ilija Studen <ilija.studen@gmail.com>
  */

  define('ANGIE_PATH', dirname(__FILE__));
  
  set_include_path(''); // don't rely on include path
  
  require_once ANGIE_PATH . '/core/constants.php';
  require_once ANGIE_PATH . '/core/functions/general.php';
  require_once ANGIE_PATH . '/core/functions/files.php';
  require_once ANGIE_PATH . '/core/functions/web.php';
  require_once ANGIE_PATH . '/core/functions/utf.php';
  require_once ANGIE_PATH . '/core/functions/utils.php';
  
  require_once ANGIE_PATH . '/Angie.class.php';
  require_once ANGIE_PATH . '/core/Angie_Error.class.php';
  require_once ANGIE_PATH . '/engine/Angie_Engine.class.php';
  require_once ANGIE_PATH . '/template/Angie_TemplateEngine.class.php';
  require_once ANGIE_PATH . '/controller/Angie_Controller.class.php';
  require_once ANGIE_PATH . '/controller/Angie_Request.class.php';
  require_once ANGIE_PATH . '/datetime/Angie_DateTime.class.php';
  require_once ANGIE_PATH . '/toys/Angie_Inflector.class.php';
  require_once ANGIE_PATH . '/toys/Angie_AutoLoader.class.php';
  
  Angie_DateTime::init(); // reset environment timezone and use GMT from now one

?>