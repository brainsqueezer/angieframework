<?php

  /**
  * This exception is thrown when we are trying to use non existing action of specific controller
  *
  * @package Angie.controller
  * @subpackage errors
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Controller_Error_ActionDnx extends Angie_Error {
  
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
    * Construct the Angie_Controller_Error_ActionDnx
    *
    * @param string $controller Controller name
    * @param string $action Controller action
    * @param string $message Error message, if NULL default will be used
    * @return Angie_Controller_Error_ActionDnx
    */
    function __construct($controller, $action, $message = null) {
      if(is_null($message)) {
        $message = "Invalid controller action $controller::$action()";
      } // if
      parent::__construct($message);
      
      $this->setControllerName($controller);
      $this->setActionName($action);
    } // __construct
    
    /**
    * Return errors specific params...
    *
    * @param void
    * @return array
    */
    function getAdditionalParams() {
      return array(
        'controller name' => $this->getControllerName(),
        'action name' => $this->getActionName()
      ); // array
    } // getAdditionalParams
    
    // -------------------------------------------------------
    // Getters and setters
    // -------------------------------------------------------
    
    /**
    * Get controller
    *
    * @param null
    * @return string
    */
    function getControllerName() {
      return $this->controller_name;
    } // getControllerName
    
    /**
    * Set controller value
    *
    * @param string $value
    * @return null
    */
    function setControllerName($value) {
      $this->controller_name = $value;
    } // setControllerName
    
    /**
    * Get action
    *
    * @param null
    * @return string
    */
    function getActionName() {
      return $this->action_name;
    } // getActionName
    
    /**
    * Set action value
    *
    * @param string $value
    * @return null
    */
    function setActionName($value) {
      $this->action_name = $value;
    } // setActionName

  } // Angie_Controller_Error_ActionDnx

?>