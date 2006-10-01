<?php

  /**
  * Connection does not exist error
  * 
  * This error is thrown if we are trying to get a specific connection, but it is not registered
  *
  * @package Angie.DB
  * @subpackage errors
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DB_Error_ConnectionDnx extends Angie_Error {
  
    /**
    * Name of the requested connection
    *
    * @var string
    */
    private $connection_name;
    
    /**
    * Constructor
    *
    * @param string $connection_name
    * @param string $message
    * @return Angie_DB_Error_ConnectionDnx
    */
    function __construct($connection_name, $message = null) {
      if(is_null($message)) {
        $message = "Connection '$connection_name' not registered";
      } // if
      parent::__construct($message);
      $this->setConnectionName($connection_name);
    } // __construct
    
    /**
    * Return additional error params
    *
    * @param void
    * @return array
    */
    function getAdditionalParams() {
      return array(
        'connection name' => $this->getConnectionName()
      ); // array
    } // getAdditionalParams
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get connection_name
    *
    * @param null
    * @return string
    */
    function getConnectionName() {
      return $this->connection_name;
    } // getConnectionName
    
    /**
    * Set connection_name value
    *
    * @param string $value
    * @return null
    */
    function setConnectionName($value) {
      $this->connection_name = $value;
    } // setConnectionName
  
  } // Angie_DB_Error_ConnectionDnx

?>