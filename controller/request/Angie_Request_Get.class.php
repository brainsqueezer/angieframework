<?php

  /**
  * Default request handle for URL request without routing ("dirty" URLs). All paramethars 
  * are collected from $_GET ($request_string provided to constructor is ignored)
  *
  * @package Angie.controller
  * @subpackage requests
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Request_Get extends Angie_Request {
    
    /**
    * Name of the $_GET param that holds controller value
    *
    * @var string
    */
    private $controller_param_name = 'c';
    
    /**
    * Name of the $_GET param that holds action name
    *
    * @var string
    */
    private $action_param_name = 'a';
    
    /**
    * Construct basic HTTP request handler
    *
    * @param string $request_string Request string
    * @param string $controller_param_name Name of the $_GET variable that holds controller 
    *   name value
    * @param string $action_param_name Name of the $_GET variable that holds action name 
    *   value
    * @return Angie_Request_Http
    */
    function __construct($request_string, $controller_param_name = null, $action_param_name = null) {
      if(trim($controller_param_name)) {
        $this->setControllerParamName($controller_param_name);
      } // if
      if(trim($action_param_name)) {
        $this->setActionParamName($action_param_name);
      } // if
      parent::__construct($request_string);
    } // __construct
  
    /**
    * This function will get input string and transform it into controller/action/params. 
    * In case of any error it will return false
    *
    * @param string $request_string Request that need to be processed
    * @return boolean
    */
    protected function process($request_string) {
      $controller_param_name = $this->getControllerParamName();
      $action_param_name = $this->getActionParamName();
      
      // Extract and set controller name
      $controller_name = '';
      if(isset($_GET[$controller_param_name])) {
        $controller_name = array_var($_GET, $controller_param_name);
        unset($_GET[$controller_param_name]);
      } // if
      if(trim($controller_name) == '') {
        $controller_name = Angie::engine()->getDefaultControllerName();
      } // if
      $this->setControllerName($controller_name);
      
      // Extract and set action name
      $action_name = '';
      if(isset($_GET[$action_param_name])) {
        $action_name = array_var($_GET, $action_param_name);
        unset($_GET[$action_param_name]);
      } // if
      if(trim($action_name) == '') {
        $action_name = Angie::engine()->getDefaultActionName();
      } // if
      $this->setActionName($action_name);
      
      // Other params
      foreach($_GET as $k => $v) {
        $this->setParam($k, $v);
      } // foreach
      
    } // process
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get controller_param_name
    *
    * @param null
    * @return string
    */
    function getControllerParamName() {
      return $this->controller_param_name;
    } // getControllerParamName
    
    /**
    * Set controller_param_name value
    *
    * @param string $value
    * @return null
    */
    function setControllerParamName($value) {
      $this->controller_param_name = $value;
    } // setControllerParamName
    
    /**
    * Get action_param_name
    *
    * @param null
    * @return string
    */
    function getActionParamName() {
      return $this->action_param_name;
    } // getActionParamName
    
    /**
    * Set action_param_name value
    *
    * @param string $value
    * @return null
    */
    function setActionParamName($value) {
      $this->action_param_name = $value;
    } // setActionParamName
  
  } // Angie_Request_Http

?>