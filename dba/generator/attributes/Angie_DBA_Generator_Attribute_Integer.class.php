<?php

  /**
  * Integer attribute
  * 
  * Integer attribute is entity attribute that is represented by single integer field. Constructor artguments are 
  * selected to best fit integer type
  *
  * @package Angie.DBA
  * @subpackage generator.attributes
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Attribute_Integer extends Angie_DBA_Generator_Attribute {
    
    /**
    * Native (PHP) type. This value is used in generated docs for accessors
    *
    * @var string
    */
    protected $native_type = 'integer';
    
    /**
    * Is this field unisnged
    *
    * @var boolean
    */
    private $unsigned = false;
    
    /**
    * Is this ID auto increment
    *
    * @var integer
    */
    private $auto_increment = false;
  
    /**
    * Constructor
    * 
    * Construct the integer attribute. Default value for attributes:
    * 
    * $size - normal
    * $lenght - auto for given size
    * $is_unsigned - false
    * $is_auto_increment - false
    *
    * @param Angie_DBA_Generator_Entity $entity
    * @param string $name
    * @param mixed $default_value
    * @param boolean $unsigned
    * @param boolean $unsigned
    * @param boolean $auto_increment
    * @return Angie_DBA_Generator_IntegerAttribute
    */
    function __construct(Angie_DBA_Generator_Entity $entity, $name, $default_value = null, $required = false, $unsigned = false, $auto_increment = false) {
      parent::__construct($entity, $name, $default_value, $required);
      
      $this->setUnsigned($unsigned);
      $this->setAutoIncrement($auto_increment);
    } // __construct
    
    /**
    * Return field that describes this attribute
    *
    * @param void
    * @return Angie_DB_Field_Integer
    */
    function getFields() {
      $integer_field = new Angie_DB_Field_Integer($this->getName(), $this->getDefaultValue(), $this->getRequired());
      $integer_field->setUnsigned($this->getUnsigned());
      $integer_field->setAutoIncrement($this->getAutoIncrement());
      
      return $integer_field;
    } // getFields
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get unsigned
    *
    * @param null
    * @return boolean
    */
    function getUnsigned() {
      return $this->unsigned;
    } // getUnsigned
    
    /**
    * Set unsigned value
    *
    * @param boolean $value
    * @return null
    */
    function setUnsigned($value) {
      $this->unsigned = $value;
    } // setUnsigned
    
    /**
    * Get auto_increment
    *
    * @param null
    * @return boolean
    */
    function getAutoIncrement() {
      return $this->auto_increment;
    } // getAutoIncrement
    
    /**
    * Set auto_increment value
    *
    * @param boolean $value
    * @return null
    */
    function setAutoIncrement($value) {
      $this->auto_increment = $value;
    } // setAutoIncrement
  
  } // Angie_DBA_Generator_Attribute_Integer

?>