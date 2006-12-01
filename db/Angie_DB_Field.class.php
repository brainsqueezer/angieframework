<?php

  /**
  * Database field definition
  * 
  * Class that describes a single database field. This class is abstract with 
  * implementation for every specific type.
  *
  * @package Angie.DB
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_DB_Field {
    
    /**
    * Field name
    *
    * @var string
    */
    private $name;
    
    /**
    * Primitive field type (varchar, integer, float, text...)
    * 
    * Protected field that needs to be overriden in subclasses
    *
    * @var string
    */
    protected  $type = Angie_DB::TYPE_VARCHAR;
    
    /**
    * Default field value. If false it is ignored
    *
    * @var mixed
    */
    private $default_value = false;
    
    /**
    * If true the field will be marked as NOT NULL
    *
    * @var boolean
    */
    private $not_null = false;
    
    /**
    * Table object, if this field belongs to a table
    *
    * @var Angie_DB_Table
    */
    private $table;
  
    /**
    * Constructor
    *
    * @param sting $name
    * @param mixed $default_value
    * @param boolean $not_null
    * @return Angie_DB_Field
    */
    function __construct($name, $default_value = null, $not_null = false) {
      $this->setName($name);
      $this->setDefaultValue($default_value);
      $this->setNotNull($not_null);
    } // __construct
    
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
    * Get type
    *
    * @param null
    * @return string
    */
    function getType() {
      return $this->type;
    } // getType
    
    /**
    * Get default_value
    *
    * @param null
    * @return mixed
    */
    function getDefaultValue() {
      return $this->default_value;
    } // getDefaultValue
    
    /**
    * Set default_value value
    *
    * @param mixed $value
    * @return null
    */
    function setDefaultValue($value) {
      $this->default_value = $value;
    } // setDefaultValue
    
    /**
    * Get not_null
    *
    * @param null
    * @return boolean
    */
    function getNotNull() {
      return $this->not_null;
    } // getNotNull
    
    /**
    * Set not_null value
    *
    * @param boolean $value
    * @return null
    */
    function setNotNull($value) {
      $this->not_null = $value;
    } // setNotNull
    
    /**
    * Get table
    *
    * @param null
    * @return Angie_DB_Table
    */
    function getTable() {
      return $this->table;
    } // getTable
    
    /**
    * Set table value
    * 
    * Value can be instance of Angie_DB_Table class or null for reseting 
    * relation. In case of invalid value exception will be thrown
    *
    * @param Angie_DB_Table $value
    * @return Angie_DB_Table
    * @throws Angie_Core_Error_InvalidParamValue
    */
    function setTable($value) {
      if(is_null($value) || ($value instanceof Angie_DB_Table)) {
        $this->table = $value;
        return $value;
      } else {
        throw new Angie_Core_Error_InvalidParamValue('value', $value, '$value can be NULL or instance of Angie_DB_Table class');
      } // if
    } // setTable
  
  } // Angie_DB_Field

?>