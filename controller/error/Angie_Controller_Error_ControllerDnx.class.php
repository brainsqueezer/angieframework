<?php

  /**
  * This error is thrown when we are trying to use a non existing or abstract controller
  *
  * @package Angie.controller
  * @subpackage errors
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Controller_Error_ControllerDnx extends Angie_Error {
  
    /**
    * Controller name
    *
    * @var string
    */
    private $controller_name;
  
    /**
    * Constructor
    *
    * @param string $controller_name
    * @param string $message
    * @return ControllerDnxError
    */
    function __construct($controller_name, $message = null) {
      if(is_null($message)) {
        $message = "Controller '$controller_name' is missing";
      } // if
      
      parent::__construct($message);
      $this->setControllerName($controller_name);
    } // __construct
    
    /**
    * Return errors specific params...
    *
    * @param void
    * @return array
    */
    function getAdditionalParams() {
      return array(
        'controller' => $this->getControllerName()
      ); // array
    } // getAdditionalParams
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
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
    function setControllerName($value) {
      $this->controller_name = $value;
    } // setControllerName
  
  } // Angie_Controller_Error_ControllerDnx

?>