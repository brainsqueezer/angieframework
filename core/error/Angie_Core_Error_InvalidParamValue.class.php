<?php

  /**
  * This error is thrown when we don't get valid value in the function or method param
  *
  * @package Angie.core
  * @subpackage errors
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Core_Error_InvalidParamValue extends Angie_Error {
  
    /**
    * Name of the variable
    *
    * @var string
    */
    private $variable_name;
    
    /**
    * Value of the variable
    *
    * @var mixed
    */
    private $variable_value;
  
    /**
    * Construct the InvalidParamError
    *
    * @param string $var_name Variable name
    * @param string $var_value Variable value that broke the code
    * @return InvalidParamError
    */
    function __construct($var_name, $var_value, $message = null) {
      if(is_null($message)) {
        $message = "$$var_name is not valid param value";
      } // if
      
      parent::__construct($message);
      
      $this->setVariableName($var_name);
      $this->setVariableValue($var_value);
    } // __construct
    
    /**
    * Return errors specific params...
    *
    * @param void
    * @return array
    */
    function getAdditionalParams() {
      return array(
        'variable name' => $this->getVariableName(),
        'variable value' => $this->getVariableValue()
      ); // array
    } // getAdditionalParams
    
    // -------------------------------------------------------
    // Getters and setters
    // -------------------------------------------------------
    
    /**
    * Get variable_name
    *
    * @param null
    * @return string
    */
    function getVariableName() {
      return $this->variable_name;
    } // getVariableName
    
    /**
    * Set variable_name value
    *
    * @param string $value
    * @return null
    */
    function setVariableName($value) {
      $this->variable_name = $value;
    } // setVariableName
    
    /**
    * Get variable_value
    *
    * @param null
    * @return mixed
    */
    function getVariableValue() {
      return $this->variable_value;
    } // getVariableValue
    
    /**
    * Set variable_value value
    *
    * @param mixed $value
    * @return null
    */
    function setVariableValue($value) {
      $this->variable_value = $value;
    } // setVariableValue
  
  } // Angie_Core_Error_InvalidParamValue

?>