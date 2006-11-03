<?php

  /**
  * Argument not allowed error
  * 
  * This error is thrown when we have an argument on option that does not allow arguments
  *
  * @package Angie.toys
  * @subpackage getopt.errors
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Getopt_Error_ArgumentNotAllowed extends Angie_Error {
  
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
    * @return Angie_Getopt_Error_ArgumentNotAllowed
    */
    function __construct($option, $message = null) {
      if(is_null($message)) {
        $message = "Option '$option' does not allow any arguments";
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
  
  } // Angie_Getopt_Error_ArgumentNotAllowed

?>