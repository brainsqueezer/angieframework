<?php

  /**
  * Abstract engine - this class provides stub function and partial implementation 
  * of default behaviour. Purpose of engine is to tie rest of the system together - 
  * to know how to access controllers, how to build models, how to init application 
  * etc. Every Angie project can override default behaviour and implement things 
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
    * Construct the engine. This function will register close() method that will be 
    * executed on script shutdown
    *
    * @param void
    * @return Angie_Engine
    */
    function __construct() {
      register_shutdown_function(array($this, 'close'));
    } // __construct
    
    // ---------------------------------------------------
    //  Abstract functions
    // ---------------------------------------------------
  
    /**
    * Init the system - this function is called after the engine is contructed to init 
    * all the resources requred by the engine and prepare the environment
    *
    * @param string $request_type Class of request type (Get, Routed, Console etc)
    * @param string $request_string Request string
    * @return null
    */
    function init($request_type = null, $request_string = null) {
      if($request_type) {
        $request_class_file = ANGIE_PATH . "/controller/request/$request_type.class.php";
        if(!is_file($request_class_file)) {
          throw new Angie_FileSystem_Error_FileDnx($request_class_file);
        } // if
        
        require $request_class_file;
        
        $request = new $request_type($request_string);
        if(!($request instanceof Angie_Request)) {
          throw new Angie_Core_Error_InvalidInstance('request', $request, 'Angie_Request');
        } // if
        
        $this->setRequest($request);
      } // if
    } // init
    
    /**
    * Execute request. Request is prepared outside of engine class and forwareded to 
    * the engine
    *
    * @param void
    * @return null
    */
    function execute() {
      $controller = Angie::engine()->getController($this->getRequest()->getControllerName());
      $controller->execute($this->getRequest()->getActionName());
    } // execute
    
    /**
    * Clean up function - this one is called on script shutdown (works in multiengine 
    * environment too). Use it save logs, send status emails, update status or whatever
    * need to be done on end of page execution
    *
    * @param void
    * @return null
    */
    abstract function close();
    
    // ---------------------------------------------------
    //  Application level paths
    // ---------------------------------------------------
    
    /**
    * Return controller file path
    *
    * @param string $controller_name Name of the controller
    * @param boolean $is_controller_class If true $controller is treated as class name. If false
    *   it is treated as controller name and will be converted to controller name
    * @return string
    */
    function getControllerPath($controller, $is_controller_class = false) {
      $controller_class = $is_controller_class ? $controller : $this->getControllerName($controller);
      return APPLICATION_PATH . "/controllers/$controller_class.class.php";
    } // getControllerPath
    
    /**
    * Return filesystem path of specific helper ($helper_name). This function will just return the path,
    * it will not check if it really exists or include it
    *
    * @param string $helper_name
    * @return string
    */
    function getHelperPath($helper_name) {
      return APPLICATION_PATH . "/helpers/$helper_name.php";
    } // getHelperPath
    
    /**
    * Check if specific helper exists
    *
    * @param string $helper_name
    * @return boolean
    */
    function helperExists($helper_name) {
      return is_file($this->getHelperPath($helper_name));
    } // helperExists
    
    /**
    * This function will check if helper exists and include it. If it exists function will return true
    * else it will return false
    *
    * @param string $helper_name
    * @return string
    */
    function useHelper($helper_name) {
      if($this->helperExists($helper_name)) {
        require $this->getHelperPath($helper_name);
        return true;
      } // if
      return false;
    } // useHelper
    
    /**
    * Return path of specific layout. This function will just return the path, it will not check if 
    * layout really exists
    *
    * @param string $layout_name
    * @return string
    */
    function getLayoutPath($layout_name) {
      return APPLICATION_PATH . "/layouts/$layout_name.php";
    } // getLayoutPath
    
    /**
    * Return path of specific view file. If $controller_name value is pressent we will return controller 
    * related path (under controller folder). If it is missing we will return global, not controller 
    * related path
    *
    * @param string $view_name Name of the view file
    * @param string $controller_name Controller name, optional
    * @return string
    */
    function getViewPath($view_name, $controller_name = null) {
      if(trim($controller_name) == '') {
        return APPLICATION_PATH . "/views/$view_name.php";
      } else {
        return APPLICATION_PATH . "/views/$controller_name/$view_name.php";
      } // if
    } // getViewPath
    
    // ---------------------------------------------------
    //  Util methods
    // ---------------------------------------------------
    
    /**
    * Include controller class for $controller_name controller, construct it and return it
    *
    * @param string $controller_name
    * @return Angie_Controller
    */
    function getController($controller_name) {
      $controller_class = $this->getControllerClass($controller_name);
      $controller_file = $this->getControllerPath($controller_class, true);
      
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
    * Return name of default controller
    *
    * @param void
    * @return string
    */
    function getDefaultControllerName() {
      return Angie::DEFAULT_CONTROLLER_NAME;
    } // getDefaultControllerName
    
    /**
    * Return name of default action
    *
    * @param void
    * @return string
    */
    function getDefaultActionName() {
      return Angie::DEFAULT_ACTION_NAME;
    } // getDefaultActionName
    
    /**
    * Return controller name based on controller class; name will be converted to underscore 
    * and 'Controller' sufix will be removed
    *
    * @param string $controller_class
    * @return string
    */
    function getControllerName($controller_class) {
      return Angie_Inflector::underscore(substr($controller_class, 0, strlen($controller_class) - 10));
    } // getControllerName
    
    /**
    * Return controller class based on controller name; controller name will be 
    * camelized and Controller will be added as sufix
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
    protected function setRequest(Angie_Request $value) {
      $this->request = $value;
    } // setRequest
    
  } // Angie_Engine

?>