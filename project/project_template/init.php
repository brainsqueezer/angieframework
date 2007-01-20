<?= '<?php' ?>

  /**
  * Application initialization file
  * 
  * This file will initialize framwrok, create applicatio engine, load configuration, collect users request etc.
  *
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  
  // ---------------------------------------------------
  //  Define path constants
  // ---------------------------------------------------
  
  if(!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__FILE__));
  } // if
  
  if(!defined('CONFIG_PATH')) {
    define('CONFIG_PATH', ROOT_PATH . '/project/config');
  } // if
  
  // ---------------------------------------------------
  //  Init Angie and other system resources
  // ---------------------------------------------------
  
  if(!defined('ANGIE_INITED')) {
    require 'angie/init.php'; // init only if Angie is not inited
  } // if
  
  require_once ROOT_PATH . '/functions.php';
  require_once ROOT_PATH . '/engine.php';
  
  // ---------------------------------------------------
  //  Init engine and handle the request if request is
  //  present (in case of test or some other scripts
  //  it is not)
  // ---------------------------------------------------
  
  Angie::loadConfiguration(CONFIG_PATH, ANGIE_ENVIRONMENT);
  define('ROOT_URL', Angie::getConfig('project.url')); // just for convinience
  
  if(Angie::getConfig('system.debugging')) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
  } else {
    ini_set('display_errors', 0);
    error_reporting(0);
  } // if
  
  Angie::setProjectEngine(Angie::getConfig('system.engine'), ROOT_PATH, ROOT_URL);
  Angie::useTemplateEngine(Angie::getConfig('system.template_engine'));
  if(Angie::getConfig('system.routing')) {
    Angie::loadRoutes(CONFIG_PATH);
  } // if
  
  Angie::engine()->init();
  
<?= '?>' ?>