<?= '<?php' ?>


  /**
  * Application initialization file
  * 
  * Purpose of this file is to initialize project level resources. It will intialize Anige framework, load configuration 
  * and initialize project (call init() method of project engine).
  *
  * @package <?= $project_name ?>.application
  */
  
  // ---------------------------------------------------
  //  Define path constants
  // ---------------------------------------------------
  
  if(!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__FILE__));
  } // if
  
  define('DEVELOPMENT_PATH', ROOT_PATH . '/development');
  define('PROJECT_PATH',     ROOT_PATH . '/project');
  define('PUBLIC_PATH',      ROOT_PATH . '/public');
  define('VENDOR_PATH',      ROOT_PATH . '/vendor');
  define('CONFIG_PATH',      PROJECT_PATH . '/config');
  define('CACHE_PATH',       PROJECT_PATH . '/cache');
  
  // ---------------------------------------------------
  //  Init Angie and other system resources
  // ---------------------------------------------------
  
  require_once 'angie/init.php';
  require_once PROJECT_PATH . '/functions.php';
  
  // ---------------------------------------------------
  //  Init engine and handle the request if request is
  //  present (in case of test or some other scripts
  //  it is not)
  // ---------------------------------------------------
  
  Angie::loadConfiguration(CONFIG_PATH, ANGIE_ENVIRONMENT);
  if(Angie::getConfig('system.debugging')) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
  } else {
    ini_set('display_errors', 0);
    error_reporting(0);
  } // if
  
  Angie::setProjectEngine(PROJECT_PATH, Angie::getConfig('system.engine'));
  Angie::useTemplateEngine(Angie::getConfig('system.template_engine'));
  Angie::loadRoutes(CONFIG_PATH);
  
  Angie::engine()->init();
  
<?= '?>' ?>