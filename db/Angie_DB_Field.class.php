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
    * @var string
    */
    private $type = Angie_DBA_Generator::TYPE_VARCHAR;
    
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
    * Constructor
    *
    * @param void
    * @return Angie_DB_Field
    */
    function __construct($name, $properties = null) {
      $this->setName($name);
      if($properties) {
        populate_through_setter($this, $properties);
      } // if
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
    * Set type value
    *
    * @param string $value
    * @return null
    */
    function setType($value) {
      $this->type = $value;
    } // setType
    
    /**
    * Get size
    *
    * @param null
    * @return string
    */
    function getSize() {
      return $this->size;
    } // getSize
    
    /**
    * Set size value
    *
    * @param string $value
    * @return null
    */
    function setSize($value) {
      $this->size = $value;
    } // setSize
    
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
  
  } // Angie_DB_Field

?>