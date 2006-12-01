<?php

  /**
  * Describe table error
  * 
  * This error is thrown when connection fails to describe specific table (table 
  * does not exists or any other error).
  *
  * @package Angie.DB
  * @subpackage errors
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DB_Error_Describe extends Angie_Error {
    
    /**
    * Table name
    *
    * @var string
    */
    private $table_name;
  
    /**
    * Constructor
    *
    * @param string $table_name
    * @param string $message
    * @return Angie_DB_Error_Describe
    */
    function __construct($table_name, $message = null) {
      if(is_null($message)) {
        $message = "Failed to describe table '$table_name'";
      } // if
      parent::__construct($message);
      $this->setTableName($table_name);
    } // __construct
    
    /**
    * Return additional error params
    *
    * @param void
    * @return array
    */
    function getAdditionalParams() {
      return array(
        'table name' => $this->getTableName(),
      ); // array
    } // getAdditionalParams
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get table_name
    *
    * @param null
    * @return string
    */
    function getTableName() {
      return $this->table_name;
    } // getTableName
    
    /**
    * Set table_name value
    *
    * @param string $value
    * @return null
    */
    function setTableName($value) {
      $this->table_name = $value;
    } // setTableName
  
  } // Angie_DB_Error_Describe

?>