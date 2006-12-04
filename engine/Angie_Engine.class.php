<?php

  /**
  * Abstract engine
  * 
  * This class provides stub function and partial implementation of default 
  * behaviour. Purpose of engine is to tie rest of the system together - to know 
  * how to access controllers, how to build models, how to init application etc. 
  * Every Angie project can override default behaviour and implement things 
  * specific for that project without hacking the rest of the system
  *
  * @package Angie.engines
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_Engine {
    
    /**
    * Request object; it is preapred by init method by default
    *
    * @var Angie_Request
    */
    private $request;
    
    /**
    * Construct the engine
    * 
    * This function will register close() method that will be executed on script 
    * shutdown
    *
    * @param void
    * @return Angie_Engine
    */
    function __construct() {
      register_shutdown_function(array($this, 'close'));
    } // __construct
    
    /**
    * Initialization method
    * 
    * This method is called in init.php before we execute any action. By default 
    * this method will connect to the database using db. configuration options. 
    * If you don't wish that kind of behavior just override this method in your 
    * project engine class
    *
    * @param void
    * @return null
    */
    function init() {
      if(Angie::getConfig('db.connect_on_init')) {
        Angie_DB::setConnection(new Angie_DB_MySQL_Connection(array(
          'hostname' => Angie::getConfig('db.hostname'),
          'username' => Angie::getConfig('db.username'),
          'password' => Angie::getConfig('db.password'),
          'name'     => Angie::getConfig('db.name'),
          'persist'  => Angie::getConfig('db.persist')
        ))); // Angie_DB_MySQL_Connection
      } // if
    } // init
    
    /**
    * Execute request that is constructed in init() method
    * 
    * This function will use request that is constructed by init() method, 
    * extract controller and action names and execute that action if exists
    *
    * @param void
    * @return null
    */
    function execute() {
      $request = $this->getRequest();
      if($request instanceof Angie_Request) {
        require_once $this->getApplicationInitfilePath($request->getApplicationName()); // init
        $this->executeAction($request->getControllerName(), $request->getActionName()); // execute
      } // if
    } // execute
    
    /**
    * Clean up function
    * 
    * This function is called on script shutdown (works in multiengine 
    * environment too). Use it save logs, send status emails, update status or 
    * whatever need to be done on end of request execution.
    *
    * @param void
    * @return null
    */
    function close() {
      
    } // close
    
    // ---------------------------------------------------
    //  Some default settings
    // ---------------------------------------------------
    
    /**
    * Return name of default application
    *
    * @param void
    * @return string
    */
    function getDefaultApplicationName() {
      return Angie::getConfig('system.default_application');
    } // getDefaultApplicationName
    
    /**
    * Return name fo default controller
    *
    * @param void
    * @return string
    */
    function getDefaultControllerName() {
      return Angie::getConfig('system.default_controller');
    } // getDefaultControllerName
    
    /**
    * Return name of default action
    *
    * @param void
    * @return string
    */
    function getDefaultActionName() {
      return Angie::getConfig('system.default_action');
    } // getDefaultActionName
    
    // ---------------------------------------------------
    //  Application level paths
    // ---------------------------------------------------
    
    /**
    * Return full path of given application
    *
    * @param string $application
    * @return string
    */
    function getApplicationPath($application) {
      return PROJECT_PATH . "/applications/$application";
    } // getApplicationPath
    
    /**
    * Check if specific application exists inside of a project
    *
    * @param string $application
    * @return boolean
    */
    function applicationExists($application) {
      return is_dir($this->getApplicationPath($application));
    } // applicationExists
    
    /**
    * Return application initialization file path
    *
    * @param string $application
    * @return string
    */
    function getApplicationInitfilePath($application) {
      return $this->getApplicationPath($application) . '/init.php';
    } // getApplicationInitfilePath
    
    /**
    * Return a classname of application controller
    * 
    * Application controller is base controller that all application controllers 
    * subclass by default.
    *
    * @param string $application
    * @return string
    */
    function getApplicationControllerClass($application) {
      return $this->getControllerClass($application);
    } // getApplicationControllerClassName
    
    /**
    * Return path of application section in public part of the project
    *
    * @param void
    * @return string
    */
    function getPublicApplicationPath($application) {
      return PUBLIC_PATH . "/$application";
    } // getPublicApplicationPath
    
    /**
    * Return controller file path
    * 
    * $controller can be intepreted in two ways:
    * 
    * 1. As a controller name that need to be converted to controller class 
    *    name if $is_controller_class value is false
    * 2. As a already prepared controller class if $is_controller_class is set 
    *    to true
    * 
    * This function will not check if controller file actualy exists. It will 
    * just generated and return the path where engine expects to find the 
    * controller
    *
    * @param string $controller
    * @param boolean $is_controller_class
    * @param $application_name
    * @return string
    */
    function getControllerPath($controller, $is_controller_class = false, $application_name = null) {
      $application = is_null($application_name) ? $this->getRequest()->getApplicationName() : $application_name;
      
      $controller_class = $is_controller_class ? $controller : $this->getControllerName($controller);
      return $this->getApplicationPath($application) . "/controllers/$controller_class.class.php";
    } // getControllerPath
    
    /**
    * Return path of specific layout
    * 
    * This function will just return the path, it will not check if layout 
    * really exists
    *
    * @param string $layout_name
    * @return string
    */
    function getLayoutPath($layout_name, $application_name = null) {
      $application = is_null($application_name) ? $this->getRequest()->getApplicationName() : $application_name;
      return $this->getApplicationPath($application) . "/layouts/$layout_name.php";
    } // getLayoutPath
    
    /**
    * Return path of view folder for specific controller and application
    *
    * @param string $controller_name
    * @param string $application_name
    * @return string
    */
    function getViewsFolderPath($controller_name, $application_name = null) {
      $application = is_null($application_name) ? $this->getRequest()->getApplicationName() : $application_name;
      return $this->getApplicationPath($application) . "/views/$controller_name";
    } // getViewsFolderPath
    
    /**
    * Return path of specific view file
    * 
    * If $controller_name value is pressent we will return controller related 
    * path (under controller subfolder). If it is missing function will assume 
    * that you are looking for file that is in /view folder, not inside any 
    * controller related subfolder
    *
    * @param string $view_name
    * @param string $controller_name
    * @parma string $application_name
    * @return string
    */
    function getViewPath($view_name, $controller_name = null, $application_name = null) {
      $application = is_null($application_name) ? $this->getRequest()->getApplicationName() : $application_name;
      
      if(trim($controller_name) == '') {
        return $this->getApplicationPath($application) . "/views/$view_name.php";
      } else {
        return $this->getApplicationPath($application) . "/views/$controller_name/$view_name.php";
      } // if
    } // getViewPath
    
    /**
    * Checks if view exists
    * 
    * This function will use getViewPath() method to generate view path and 
    * return true if targeted view file exists and is readable
    *
    * @param string $view_name
    * @param string $controller_name
    * @param string $application_name
    * @return boolean
    */
    function viewExists($view_name, $controller_name = null, $application_name = null) {
      return is_readable($this->getViewPath($view_name, $controller_name, $application_name));
    } // viewExists
    
    /**
    * Return filesystem path of specific helper ($helper_name). 
    * 
    * This function will return path of a specific application level helper. To 
    * get path of a system level helper use getSystemHelperPath() method
    *
    * @param string $helper_name
    * @param string $application_name
    * @return string
    */
    function getHelperPath($helper_name, $application_name = null) {
      $application = is_null($application_name) ? $this->getRequest()->getApplicationName() : $application_name;
      return $this->getApplicationPath($application) . "/helpers/$helper_name.php";
    } // getHelperPath
    
    /**
    * Return path of a system level helper
    *
    * @param string $helper_name
    * @return string
    */
    function getSystemHelperPath($helper_name) {
      return ANGIE_PATH . "/controller/helpers/$helper_name.php";
    } // getSystemHelperPath
    
    /**
    * Check if specific helper exists
    *
    * @param string $helper_name
    * @param string $application_name
    * @return boolean
    */
    function helperExists($helper_name, $application_name = null) {
      return is_file($this->getHelperPath($helper_name));
    } // helperExists
    
    /**
    * Check if specific system level helper exists
    *
    * @param string $helper_name
    * @return boolean
    */
    function systemHelperExists($helper_name) {
      return is_file($this->getSystemHelperPath($helper_name));
    } // systemHelperExists
    
    /**
    * Use specific helper
    * 
    * This function will check if a specific helper exists and include it. If 
    * application and system level helpers exist both will be included. If no 
    * helper is included an exception will be thrown.
    *
    * @param string $helper_name
    * @param string $application_name
    * @return string
    * @throws Angie_Controller_Error_HelperDnx If helper $helper_name does not exist
    */
    function useHelper($helper_name, $application_name = null) {
      $application = is_null($application_name) ? $this->getRequest()->getApplicationName() : $application_name;
      
      $application_helper_path = $this->getHelperPath($helper_name, $application);
      $system_helper_path = $this->getSystemHelperPath($helper_name);
      
      $helper_included = false;
      
      if(is_file($application_helper_path)) {
        require_once $application_helper_path;
        $helper_included = true;
      } // if
      
      if(is_file($system_helper_path)) {
        require_once $system_helper_path;
        $helper_included = true;
      }
      
      if($helper_included) {
        return true;
      } // if
      
      throw new Angie_Controller_Error_HelperDnx($helper_name, $application);
    } // useHelper
    
    // ---------------------------------------------------
    //  Util methods
    // ---------------------------------------------------
    
    /**
    * Execute $action_name action of $controller_name controller. 
    * 
    * This function will execute a specific action of a specific controller in 
    * a specific application. If application is not yet initialized its init.php 
    * will be included. 
    * 
    * All arguments are required.
    *
    * @param string $controller_name
    * @param string $action_name
    * @param string $application_name
    * @return null
    */
    function executeAction($controller_name, $action_name, $application_name = null) {
      $application = is_null($application_name) ? $this->getRequest()->getApplicationName() : $application_name;
      
      $controller = Angie::engine()->getController($controller_name, $application_name);
      $controller->execute($action_name);
    } // executeAction
    
    /**
    * Include controller class for $controller_name controller, construct it and return it
    *
    * @param string $controller_name
    * @param string $application_name
    * @return Angie_Controller
    */
    function getController($controller_name, $application_name = null) {
      $application = is_null($application_name) ? $this->getRequest()->getApplicationName() : $application_name;
      
      $controller_class = $this->getControllerClass($controller_name);
      $controller_file = $this->getControllerPath($controller_class, true, $application);
      
      if(!is_file($controller_file)) {
        throw new Angie_FileSystem_Error_FileDnx($controller_file);
      } // if
      
      require $controller_file;
      
      $reflection = new ReflectionClass($controller_class);
      if($reflection->isAbstract()) {
        throw new Angie_Controller_Error_ControllerDnx($controller_name);
      } // if
      
      $controller = new $controller_class();
      if(!($controller instanceof Angie_Controller)) {
        throw new Angie_Core_Error_InvalidInstance('controller', $controller, 'Angie_Controller');
      } // if
      
      return $controller;
    } // getController
    
    /**
    * Return controller name based on controller class
    * 
    * Name will be converted to underscore and 'Controller' sufix will be 
    * removed. Example:
    * <pre>
    * MyStuffController => my_stuff
    * TaskController => task
    * </pre>
    *
    * @param string $controller_class
    * @return string
    */
    function getControllerName($controller_class) {
      return Angie_Inflector::underscore(substr($controller_class, 0, strlen($controller_class) - 10));
    } // getControllerName
    
    /**
    * Return controller class based on controller name
    * 
    * Controller name will be camelized and Controller will be added as sufix
    * 
    * Examples:
    * <pre>
    * my_stuff => MyStuffController
    * tasks => TaskController
    * </pre>
    *
    * @param string $controller_name
    * @return string
    */
    function getControllerClass($controller_name) {
      return Angie_Inflector::camelize($controller_name) . 'Controller';
    } // getControllerClass
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get request
    *
    * @param null
    * @return Angie_Request
    */
    function getRequest() {
      return $this->request;
    } // getRequest
    
    /**
    * Set request value
    *
    * @param Angie_Request $value
    * @return null
    */
    function setRequest(Angie_Request $value) {
      $this->request = $value;
    } // setRequest
    
  } // Angie_Engine

?>