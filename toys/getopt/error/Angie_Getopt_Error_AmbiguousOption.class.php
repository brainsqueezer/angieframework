<?php

  /**
  * Ambiuous option error
  *
  * @package Angie.toys
  * @subpackage getopt.errors
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Getopt_Error_AmbiguousOption extends Angie_Error {
  
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
    * @return Angie_Getopt_Error_AmbiguousOption
    */
    function __construct($option, $message = null) {
      if(is_null($message)) {
        $message = "Option '$option' is ambigous";
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
  
  } // Angie_Getopt_Error_AmbiguousOption

?>