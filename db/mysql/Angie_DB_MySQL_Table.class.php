<?php

  /**
  * MySQL table definition
  * 
  * This class extends basic table class with a specific MySQL properties 
  * (engine, default charset, collation etc)
  *
  * @package Angie.DB
  * @subpackage mysql
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DB_MySQL_Table extends Angie_DB_Table {
    
    /**
    * MySQL storage engine
    *
    * @var string
    */
    private $engine = null;
    
    /**
    * Default charset for a given table
    *
    * @var string
    */
    private $default_charset = null;
    
    /**
    * Default collation for a given table
    *
    * @var string
    */
    private $default_collation = null;
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get engine
    *
    * @param null
    * @return string
    */
    function getEngine() {
      return $this->engine;
    } // getEngine
    
    /**
    * Set engine value
    *
    * @param string $value
    * @return null
    */
    function setEngine($value) {
      $this->engine = $value;
    } // setEngine
    
    /**
    * Get default_charset
    *
    * @param null
    * @return string
    */
    function getDefaultCharset() {
      return $this->default_charset;
    } // getDefaultCharset
    
    /**
    * Set default_charset value
    *
    * @param string $value
    * @return null
    */
    function setDefaultCharset($value) {
      $this->default_charset = $value;
    } // setDefaultCharset
    
    /**
    * Get default_collation
    *
    * @param null
    * @return string
    */
    function getDefaultCollation() {
      return $this->default_collation;
    } // getDefaultCollation
    
    /**
    * Set default_collation value
    *
    * @param string $value
    * @return null
    */
    function setDefaultCollation($value) {
      $this->default_collation = $value;
    } // setDefaultCollation
  
  } // Angie_DB_MySQL_Table

?>