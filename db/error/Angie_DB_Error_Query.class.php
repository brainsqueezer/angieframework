<?php

  /**
  * Query execution error
  * 
  * This exception is thrown when we fail to execute a query from some reason (invalid query, server problems such is 
  * too large query, dead connection etc)
  *
  * @package Angie.DB
  * @subpackage errors
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DB_Error_Query extends Angie_Error {
    
    /**
    * SQL that made the error
    *
    * @var string
    */
    private $sql;
    
    /**
    * Native server message
    *
    * @var string
    */
    private $native_message;
  
    /**
    * Constructor
    *
    * @param void
    * @return Angie_DB_Error_Query
    */
    function __construct($sql, $native_message, $message = null) {
      if(is_null($message)) {
        $message = "Failed to execute query. Error: $native_message";
      } // if
      parent::__construct($message);
      
      $this->setSQL($sql);
      $this->setNativeMessage($native_message);
    } // __construct
    
    /**
    * Return additional error params
    *
    * @param void
    * @return array
    */
    function getAdditionalParams() {
      return array(
        'sql' => $this->getSQL(),
        'native message' => $this->getNativeMessage()
      ); // array
    } // getAdditionalParams
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get sql
    *
    * @param null
    * @return string
    */
    function getSQL() {
      return $this->sql;
    } // getSQL
    
    /**
    * Set sql value
    *
    * @param string $value
    * @return null
    */
    function setSQL($value) {
      $this->sql = $value;
    } // setSQL
    
    /**
    * Get native_message
    *
    * @param null
    * @return string
    */
    function getNativeMessage() {
      return $this->native_message;
    } // getNativeMessage
    
    /**
    * Set native_message value
    *
    * @param string $value
    * @return null
    */
    function setNativeMessage($value) {
      $this->native_message = $value;
    } // setNativeMessage
  
  } // Angie_DB_Error_Query

?>