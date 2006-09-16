<?php

  /**
  * Helper does not exists error
  * 
  * This error is thrown when we try to use helper that does not exist or system is unable to
  * find it (not in expected location). Additional param for this error is helper name - name
  * of the helper we are looking for but we can't find
  *
  * @package Angie.controller
  * @subpackage errors
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Controller_Error_HelperDnx extends Angie_Error {
    
    /**
    * Name of the helper
    *
    * @var string
    */
    private $helper_name;
  
    /**
    * Constructor the error. If $message is NULL default message will be generated
    *
    * @param string $helper_name
    * @param string $message
    * @return Angie_Controller_Error_HelperDnx
    */
    function __construct($helper_name, $message = null) {
      if(is_null($message)) {
        $message = "Helper '$helper_name' does not exists";
      } // if
      parent::__construct($message);
      $this->setHelperName($helper_name);
    } // __construct
    
    /**
    * Return errors specific params...
    *
    * @param void
    * @return array
    */
    function getAdditionalParams() {
      return array(
        'helper name' => $this->getHelperName()
      ); // array
    } // getAdditionalParams
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get helper_name
    *
    * @param null
    * @return string
    */
    function getHelperName() {
      return $this->helper_name;
    } // getHelperName
    
    /**
    * Set helper_name value
    *
    * @param string $value
    * @return null
    */
    function setHelperName($value) {
      $this->helper_name = $value;
    } // setHelperName
  
  } // Angie_Controller_Error_HelperDnx

?>