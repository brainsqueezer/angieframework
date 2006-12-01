<?php

  /**
  * Float entity attribute
  * 
  * Float attribute is numeric type used for storing decimal values. It has 
  * lenght and precission (number of significant digits). Also, float field can 
  * be unsigned (support only for values equal to or larger than 0).
  *
  * @package Angie.DBA
  * @subpackage generator.attributes
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Attribute_Float extends Angie_DBA_Generator_Attribute {
  
    /**
    * Native (PHP) type. This value is used in generated docs for accessors
    *
    * @var string
    */
    protected $native_type = 'float';
    
    /**
    * Value lenght
    *
    * @var integer
    */
    private $lenght;
    
    /**
    * Precission - number of significant decimals
    *
    * @var integer
    */
    private $precission;
    
    /**
    * Is this field unisnged
    *
    * @var boolean
    */
    private $unsigned = false;
  
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
    function __construct(Angie_DBA_Generator_Entity $entity, $name, $default_value = null, $required = false, $lenght = null, $precission = null, $unsigned = false) {
      parent::__construct($entity, $name, $default_value, $required);
      
      $this->setLenght($lenght);
      $this->setPrecission($precission);
      $this->setUnsigned($unsigned);
    } // __construct
    
    /**
    * Return field that describes this attribute
    *
    * @param void
    * @return Angie_DB_Field_Integer
    */
    function getFields() {
      $float_field = new Angie_DB_Field_Float($this->getName(), $this->getDefaultValue(), $this->getRequired());
      $float_field->setLenght($this->getLenght());
      $float_field->setPrecission($this->getPrecission());
      $float_field->setUnsigned($this->getUnsigned());
      
      return $float_field;
    } // getFields
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get lenght
    *
    * @param null
    * @return integer
    */
    function getLenght() {
      return $this->lenght;
    } // getLenght
    
    /**
    * Set lenght value
    *
    * @param integer $value
    * @return null
    */
    function setLenght($value) {
      $this->lenght = $value;
    } // setLenght
    
    /**
    * Get precission
    *
    * @param null
    * @return integer
    */
    function getPrecission() {
      return $this->precission;
    } // getPrecission
    
    /**
    * Set precission value
    *
    * @param integer $value
    * @return null
    */
    function setPrecission($value) {
      $this->precission = $value;
    } // setPrecission
    
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
  
  } // Angie_DBA_Generator_Attribute_Float

?>