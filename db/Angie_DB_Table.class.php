<?php

  /**
  * Database table description
  * 
  * This class is used to describe a construction of a given database table. It 
  * supports definition of fields, primary key and naming of a table
  *
  * @package Angie.DB
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DB_Table {
    
    /**
    * Name of the table
    *
    * @var string
    */
    private $name;
    
    /**
    * Table name prefix
    *
    * @var string
    */
    private $prefix;
    
    /**
    * Array of table fields (Angie_DB_Field objects)
    *
    * @var array
    */
    private $fields;
    
    /**
    * Array of fields that make primary key
    *
    * @var array
    */
    private $primary_key = array();
  
    /**
    * Constructor
    *
    * @param string $name
    * @param array $fields
    * @param array $primary_key
    * @return Angie_DB_Table
    */
    function __construct($name, $fields = array(), $primary_key = array()) {
      $this->setName($name);
      $this->setFields($fields);
      if(is_array($primary_key)) {
        $this->setPrimaryKey($primary_key);
      } else {
        $this->setPrimaryKey(array($primary_key));
      } // if
    } // __construct
    
    // ---------------------------------------------------
    //  Utils
    // ---------------------------------------------------
    
    /**
    * Generate prefixed name
    *
    * @param void
    * @return string
    */
    function getPrefixedName() {
      return trim($this->getPrefix()) . trim($this->getName());
    } // getPrefixedName
    
    /**
    * Read field and keys description from database
    * 
    * This function uses given database connection resource to read more data 
    * about this table from the database. It needs to be implemented in 
    * subclasses (by default it will just throw an exception)
    *
    * @param Angie_DB_Connection $connection
    * @return null
    * @throws Angie_Error
    */
    function readDescription(Angie_DB_Connection $connection) {
      throw new Angie_Error('Angie_DB_Table::readDescription() needs to be implemented in subclass');
    } // readDescription
    
    /**
    * Check if $field is part of tables primary key
    * 
    * $field can be field name or Angie_DB_Field object
    *
    * @param Angie_DB_Field $field
    * @return boolean
    */
    function isPrimaryKey($field) {
      $field_name = $field instanceof Angie_DB_Field ? $field->getName() : $field;
      return array_var($this->primary_key, $field_name);
    } // isPrimaryKey
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get name
    *
    * @param null
    * @return string
    */
    function getName() {
      return $this->name;
    } // getName
    
    /**
    * Set name value
    *
    * @param string $value
    * @return null
    */
    function setName($value) {
      $this->name = $value;
    } // setName
    
    /**
    * Get prefix
    *
    * @param null
    * @return string
    */
    function getPrefix() {
      return $this->prefix;
    } // getPrefix
    
    /**
    * Set prefix value
    *
    * @param string $value
    * @return null
    */
    function setPrefix($value) {
      $this->prefix = $value;
    } // setPrefix
    
    /**
    * Get fields
    *
    * @param null
    * @return array
    */
    function getFields() {
      return $this->fields;
    } // getFields
    
    /**
    * Check if specific field exists
    * 
    * Check if $field exists in this table. $field can be field name or 
    * Angie_DB_Field object
    *
    * @param Angie_DB_Field $field
    * @return boolean
    */
    function fieldExists($field) {
      $field_name = $field instanceof Angie_DB_Field ? $field->getName() : $field;
      return isset($this->fields[$field_name]);
    } // fieldExists
    
    /**
    * Add a single field to the table
    *
    * @param Angie_DB_Field $field
    * @return Angie_DB_Field
    */
    function addField(Angie_DB_Field $field) {
      $this->fields[$field->getName()] = $field;
      $field->setTable($this);
      return $field;
    } // addField
    
    /**
    * Set fields value
    *
    * @param array $value
    * @return null
    */
    function setFields($fields) {
      $this->fields = array();
      if(is_foreachable($fields)) {
        foreach($fields as $field) {
          if($field instanceof Angie_DB_Field) {
            $this->addField($field);
          } else {
            throw new Angie_Core_Error_InvalidParamValue('fields', $fields, '$fields should be an array of Angie_DB_Field objects');
          } // if
        } // foreach
      } // if
    } // setFields
    
    /**
    * Get primary_key
    *
    * @param null
    * @return array
    */
    function getPrimaryKey() {
      return $this->primary_key;
    } // getPrimaryKey
    
    /**
    * Set primary_key value
    *
    * @param array $value
    * @return null
    */
    function setPrimaryKey($value) {
      $this->primary_key = $value;
    } // setPrimaryKey
    
    /**
    * Add primary key field name
    * 
    * $field can be field name or instance of Angie_DB_Field class
    *
    * @param Angie_DB_Field $field
    * @return null
    */
    function addPrimaryKey($field) {
      $field_name = $field instanceof Angie_DB_Field ? $field->getName() : $field;
      if($this->fieldExists($field_name)) {
        if(!in_array($field_name, $this->primary_key)) {
          $this->primary_key[] = $field_name;
        } // if
      } else {
        throw new Angie_DB_Error("Field '$field_name' does not exists in this table");
      } // if
    } // addPrimaryKey
    
  } // Angie_DB_Table

?>