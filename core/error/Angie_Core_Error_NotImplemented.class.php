<?php

  /**
  * Not implemented error
  * 
  * This error is thrown when we try to call a method that is left without an 
  * implementation
  *
  * @package Angie.core
  * @subpackage errors
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Core_Error_NotImplemented extends Angie_Error {
    
    /**
    * Class name
    *
    * @var string
    */
    private $class;
    
    /**
    * Method name
    *
    * @var string
    */
    private $method;
  
    /**
    * Constructor
    *
    * @param string $class
    * @param string $method
    * @param string $message
    * @return Angie_Core_Error_NotImplemented
    */
    function __construct($class, $method, $message = null) {
      if($message === null) {
        $message = $class . '::' . $method . '() is not implemented';
      } // if
      parent::__construct($message);
      
      $this->setClass($class);
      $this->setMethod($method);
    } // __construct
    
    /**
    * Return additional error params
    *
    * @param void
    * @return array
    */
    function getAdditionalParams() {
      return array(
        'class' => $this->getClass(),
        'method' => $this->getMethod(),
      ); // array
    } // getAdditionalParams
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get class
    *
    * @param null
    * @return string
    */
    function getClass() {
      return $this->class;
    } // getClass
    
    /**
    * Set class value
    *
    * @param string $value
    * @return null
    */
    function setClass($value) {
      $this->class = $value;
    } // setClass
    
    /**
    * Get method
    *
    * @param null
    * @return string
    */
    function getMethod() {
      return $this->method;
    } // getMethod
    
    /**
    * Set method value
    *
    * @param string $value
    * @return null
    */
    function setMethod($value) {
      $this->method = $value;
    } // setMethod
  
  } // Angie_Core_Error_NotImplemented

?>