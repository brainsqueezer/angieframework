<?php

  /**
  * Router match error
  * 
  * This exception is thrown when specific request string does not match any of mapped routes
  *
  * @package Angie.toys
  * @subpackage router.errors
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Router_Error_Match extends Angie_Error {
  
    /**
    * Request string that was not matched
    *
    * @var string
    */
    private $request_string;
    
    /**
    * Constructor
    *
    * @param string $request_string
    * @param string $message
    * @return Angie_Router_Error_Match
    */
    function __construct($request_string, $message = null) {
      if(is_null($message)) {
        $message = "String '$request_string' does not match any of mapped routes";
      } // if
      parent::__construct($message);
      $this->setRequestString($request_string);
    } // __construct
    
    /**
    * Return additional error params
    *
    * @param void
    * @return array
    */
    function getAdditionalParams() {
      return array(
        'request string' => $this->getRequestString(),
      ); // array
    } // getAdditionalParams
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get request_string
    *
    * @param null
    * @return string
    */
    function getRequestString() {
      return $this->request_string;
    } // getRequestString
    
    /**
    * Set request_string value
    *
    * @param string $value
    * @return null
    */
    function setRequestString($value) {
      $this->request_string = $value;
    } // setRequestString
  
  } // Angie_Router_Error_Match

?>