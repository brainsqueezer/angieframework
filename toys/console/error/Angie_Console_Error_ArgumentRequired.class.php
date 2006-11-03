<?php

  /**
  * Argument required error
  * 
  * This error is thrown when specific option requires an argument but it is not present
  *
  * @package Angie.toys
  * @subpackage console.errors
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Console_Error_ArgumentRequired extends Angie_Error {
    
    /**
    * Option
    *
    * @var string
    */
    private $option;
  
    /**
    * Constructor
    *
    * @param void
    * @return Angie_Console_Error_ArgumentRequired
    */
    function __construct($option, $message = null) {
      if(is_null($message)) {
        $message = "Option '$option' requires an argument";
      } // if
      parent::__construct($message);
    } // __construct
    
    /**
    * Return additional error params
    *
    * @param void
    * @return array
    */
    function getAdditionalParams() {
      return array(
        'option' => $this->getOption()
      ); // array
    } // getAdditionalParams
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get option
    *
    * @param null
    * @return string
    */
    function getOption() {
      return $this->option;
    } // getOption
    
    /**
    * Set option value
    *
    * @param string $value
    * @return null
    */
    function setOption($value) {
      $this->option = $value;
    } // setOption
  
  } // Angie_Console_Error_ArgumentRequired

?>