<?php

  /**
  * Value does not exists in cache exception
  * 
  * This exception is thrown when we are trying to get a value for the cache that does not exists in the cache. It is 
  * usualy thrown on value fatching and suppressed by cache getter (default value is returned)
  *
  * @package Angie.cache
  * @subpackage errors
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Cache_Error_ValueDnx extends Angie_Error {
  
    /**
    * Name of the requested variable
    *
    * @var string
    */
    private $variable_name;
    
    /**
    * Constructor
    *
    * @param string $name
    * @param string $message
    * @return Angie_Cache_Error_ValueDnx
    */
    function __construct($name, $message = null) {
      if(is_null($message)) {
        $message = "'$name' is not found in the cache";
      } // if
      parent::__construct($message);
      $this->setVariableName($name);
    } // __construct
    
    /**
    * Return additional error params
    *
    * @param void
    * @return array
    */
    function getAdditionalParams() {
      return array(
        'variable name' => $this->getVariableName()
      ); // array
    } // getAdditionalParams
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
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
  
  } // Angie_Cache_Error_ValueDnx

?>