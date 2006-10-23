<?php

  /**
  * DBA validation error
  * 
  * This error is thrown when we fail to save object because of validation error
  *
  * @package Angie.DBA
  * @subpackage runtime.errors
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Error_Validate extends Angie_Error {
    
    /**
    * Object that we failed to save
    *
    * @var Angie_DBA_Object
    */
    private $object;
    
    /**
    * Array of reported errors
    *
    * @var array
    */
    private $errors;
  
    /**
    * Constructor
    *
    * @param void
    * @return Angie_DBA_Error_Validate
    */
    function __construct($object, $errors, $message = null) {
      if(is_null($message)) {
        $message = 'Failed to save object. ' . count($errors) . ' errors';
      } // if
      $this->setObject($object);
      $this->setErrors($errors);
    } // __construct
    
    /**
    * Return additional error params
    *
    * @param void
    * @return array
    */
    function getAdditionalParams() {
      return array(
        'object' => $this->getObject(),
        'errors' => $this->getErrors(),
      ); // array
    } // getAdditionalParams
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get object
    *
    * @param null
    * @return Angie_DBA_Object
    */
    function getObject() {
      return $this->object;
    } // getObject
    
    /**
    * Set object value
    *
    * @param Angie_DBA_Object $value
    * @return null
    */
    function setObject($value) {
      $this->object = $value;
    } // setObject
    
    /**
    * Get errors
    *
    * @param null
    * @return array
    */
    function getErrors() {
      return $this->errors;
    } // getErrors
    
    /**
    * Set errors value
    *
    * @param array $value
    * @return null
    */
    function setErrors($value) {
      $this->errors = $value;
    } // setErrors
  
  } // Angie_DBA_Error_Validate

?>