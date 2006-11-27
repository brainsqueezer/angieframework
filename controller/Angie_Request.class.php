<?php

  /**
  * Request class
  * 
  * Requests are objects contructed based on user input to translate what user 
  * wants into a form that can be used by the rest of the system to form a 
  * response and respond.
  * 
  * Requests needs to extract three values:
  * 
  * - application name
  * - controller name
  * - action name
  * 
  * It can also extract a number of request arguments but that is optional.
  *
  * @package Angie.controller
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_Request {
    
    /**
    * Value of original, raw request that is processed and prepared by process() method
    *
    * @var string
    */
    private $request_string;
    
    /**
    * Name of the selected application
    *
    * @var string
    */
    private $application_name;
    
    /**
    * Controller name
    *
    * @var string
    */
    private $controller_name;
    
    /**
    * Action name
    *
    * @var string
    */
    private $action_name;
    
    /**
    * Request params, name => value pairs in associative array
    *
    * @var array
    */
    private $params = array();
  
    /**
    * Constructor. 
    *
    * @param string $request_string
    * @return Angie_Request
    */
    function __construct($request_string) {
      $this->process($request_string);
    } // __construct
    
    // ---------------------------------------------------
    //  Abstract methods
    // ---------------------------------------------------
    
    /**
    * This function will get input string and transform it into controller/action/params. 
    * In case of any error it will return false
    *
    * @param string $request_string Request that need to be processed
    * @return boolean
    */
    abstract protected function process($request_string);
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get request_string
    *
    * @param null
    * @return string
    */
    function getRequestString() {
      return $this->request_string;
    } // getRequestString
    
    /**
    * Set request_string value
    *
    * @param string $value
    * @return null
    */
    protected function setRequestString($value) {
      $this->request_string = $value;
    } // setRequestString
    
    /**
    * Get application_name
    *
    * @param null
    * @return string
    */
    function getApplicationName() {
      return $this->application_name;
    } // getApplicationName
    
    /**
    * Set application_name value
    *
    * @param string $value
    * @return null
    */
    function setApplicationName($value) {
      $this->application_name = $value;
    } // setApplicationName
    
    /**
    * Get controller_name
    *
    * @param null
    * @return string
    */
    function getControllerName() {
      return $this->controller_name;
    } // getControllerName
    
    /**
    * Set controller_name value
    *
    * @param string $value
    * @return null
    */
    protected function setControllerName($value) {
      $this->controller_name = $value;
    } // setControllerName
    
    /**
    * Get action_name
    *
    * @param null
    * @return string
    */
    function getActionName() {
      return $this->action_name;
    } // getActionName
    
    /**
    * Set action_name value
    *
    * @param string $value
    * @return null
    */
    protected function setActionName($value) {
      $this->action_name = $value;
    } // setActionName
    
    /**
    * Return all params as associative array
    *
    * @param void
    * @return array
    */
    function getParams() {
      return $this->params;
    } // getParams
    
    /**
    * Return value of specific param. If that param is not found $default will be returned
    *
    * @param string $name Param name
    * @param mixed $default
    * @return mixed
    */
    function getParam($name, $default = null) {
      return array_var($this->params, $name, $default);
    } // getParam
    
    /**
    * Set value of specific param
    *
    * @param string $name
    * @param mixed $value
    * @return null
    */
    protected function setParam($name, $value) {
      $this->params[$name] = $value;
    } // setParam
  
  } // Angie_Request

?>