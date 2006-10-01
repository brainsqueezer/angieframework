<?php

  /**
  * Database connection error
  * 
  * This error is thrown on connect failure. Database password is excluded from exception details
  *
  * @package Angie.DB
  * @subpackage errors
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DB_Error_Connect extends Error {
  
    /**
    * Hostname
    *
    * @var string
    */
    private $host;
    
    /**
    * Username
    *
    * @var string
    */
    private $user;
    
    /**
    * Database name
    *
    * @var string
    */
    private $database;
  
    /**
    * Construct the DBConnectError
    *
    * @param string $host
    * @param string $username
    * @param string $databae
    * @param string $message
    * @return DBConnectError
    */
    function __construct($host, $username, $database, $message = null) {
      if(is_null($message)) {
        $message = "Failed to connect to database";
      } // if
      
      parent::__construct($message);
      
      $this->setHost($host);
      $this->setUser($username);
      $this->setDatabase($database);
    } // __construct
    
    /**
    * Return errors specific params...
    *
    * @access public
    * @param void
    * @return array
    */
    function getAdditionalParams() {
      return array(
        'hostname' => $this->getHost()
      ); // array
    } // getAdditionalParams
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get host
    *
    * @access public
    * @param null
    * @return string
    */
    function getHost() {
      return $this->host;
    } // getHost
    
    /**
    * Set host value
    *
    * @access public
    * @param string $value
    * @return null
    */
    function setHost($value) {
      $this->host = $value;
    } // setHost
    
    /**
    * Get user
    *
    * @access public
    * @param null
    * @return string
    */
    function getUser() {
      return $this->user;
    } // getUser
    
    /**
    * Set user value
    *
    * @access public
    * @param string $value
    * @return null
    */
    function setUser($value) {
      $this->user = $value;
    } // setUser
    
    /**
    * Get database
    *
    * @access public
    * @param null
    * @return string
    */
    function getDatabase() {
      return $this->database;
    } // getDatabase
    
    /**
    * Set database value
    *
    * @access public
    * @param string $value
    * @return null
    */
    function setDatabase($value) {
      $this->database = $value;
    } // setDatabase
  
  } // Angie_DB_Error_Connect

?>