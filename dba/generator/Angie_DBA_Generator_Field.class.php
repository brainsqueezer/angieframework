<?php

  /**
  * Generator field
  * 
  * Generator attributes can be described with multiple fields and this class is used to describe single field. 
  * Properties of this class are similar to properties used to describe database field, but that does not meant that 
  * described propertis need to be used for generation of SQL.
  *
  * @package Angie.DBA
  * @subpackage generator
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Field {
    
    /**
    * Field name
    *
    * @var string
    */
    private $name;
    
    /**
    * Name of the getter method
    *
    * @var string
    */
    private $getter_name;
    
    /**
    * Name of the setter method
    *
    * @var string
    */
    private $setter_name;
    
    /**
    * PHP native type for this filed (can be class name)
    *
    * @var string
    */
    private $native_type = 'mixed';
    
    /**
    * Functio that is used for casting
    *
    * @var string
    */
    private $cast_function = '';
    
    /**
    * Primitive field type (varchar, integer, float, text...)
    *
    * @var string
    */
    private $type = Angie_DBA_Generator::TYPE_VARCHAR;
    
    /**
    * Type size, one of possible four values (TINY, SMALL, MEDIUM, NORMAL, BIG and empty string - NONE)
    *
    * @var string
    */
    private $type_size = '';
    
    /**
    * Lenght attribute is used for multiple primary types in diferent context - varchars, floats, integers
    *
    * @var integer
    */
    private $lenght = 100;
    
    /**
    * Precission is used only with floating point field types, number of siginificant digits
    *
    * @var integer
    */
    private $precision;
    
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
    private $is_not_null = false;
    
    /**
    * Unsignt is used with numeric types to mark fields that can have 0 and positive values
    *
    * @var boolean
    */
    private $is_unsigned = false;
    
    /**
    * If value of auto increment field is not set on insert it is set to the next value in queue to ensure uniqueness. 
    * Usually used for ID fields
    *
    * @var boolan
    */
    private $is_auto_increment = false;
  
    /**
    * Constructor
    * 
    * Construct field and set property values. $name is field name and $properties is an associative value of additional 
    * properties that can be set. Possible fields:
    * 
    * - type - string
    * - type_size - string
    * - lenght - integer
    * - precision - integer
    * - default_value - mixed
    * - is_not_null - boolean
    * - is_unsigned - boolean
    * - is_auto_increment - boolean
    *
    * @param void
    * @return Angie_DBA_Generator_Field
    */
    function __construct($name, $properties = null) {
      $this->setName($name);
      if(is_array($properties)) {
        foreach($properties as $property_name => $property_value) {
          $setter = 'set' . Angie_Inflector::camelize($property_name);
          if(method_exists($this, $setter)) {
            $this->$setter($property_value);
          } // if
        } // foreach
      } // if
    } // __construct
    
    // ---------------------------------------------------
    //  Generator
    // ---------------------------------------------------
    
    /**
    * Render object class properties and methods
    *
    * @param void
    * @return null
    */
    function renderObjectMembers() {
      Angie_DBA_Generator::assignToView('field', $this);
      Angie_DBA_Generator::displayView('field_object_members');
    } // renderObjectMembers
    
    /**
    * Render manager class fields and methods
    *
    * @param void
    * @return null
    */
    function renderManagerMembers() {
      return;
    } // renderManagerMembers
    
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
    * Get getter_name
    *
    * @param null
    * @return string
    */
    function getGetterName() {
      if(trim($this->getter_name) == '') {
        return 'get' . ucfirst(Angie_Inflector::camelize($this->getName()));
      } // if
      return $this->getter_name;
    } // getGetterName
    
    /**
    * Set getter_name value
    *
    * @param string $value
    * @return null
    */
    function setGetterName($value) {
      $this->getter_name = $value;
    } // setGetterName
    
    /**
    * Get setter_name
    *
    * @param null
    * @return string
    */
    function getSetterName() {
      if(trim($this->setter_name) == '') {
        return 'set' . ucfirst(Angie_Inflector::camelize($this->getName()));
      } // if
      return $this->setter_name;
    } // getSetterName
    
    /**
    * Set setter_name value
    *
    * @param string $value
    * @return null
    */
    function setSetterName($value) {
      $this->setter_name = $value;
    } // setSetterName
    
    /**
    * Get native_type
    *
    * @param null
    * @return string
    */
    function getNativeType() {
      return $this->native_type;
    } // getNativeType
    
    /**
    * Set native_type value
    *
    * @param string $value
    * @return null
    */
    function setNativeType($value) {
      $this->native_type = $value;
    } // setNativeType
    
    /**
    * Get cast_function
    *
    * @param null
    * @return string
    */
    function getCastFunction() {
      return $this->cast_function;
    } // getCastFunction
    
    /**
    * Set cast_function value
    *
    * @param string $value
    * @return null
    */
    function setCastFunction($value) {
      $this->cast_function = $value;
    } // setCastFunction
    
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
    * Get type_site
    *
    * @param null
    * @return string
    */
    function getTypeSize() {
      return $this->type_site;
    } // getTypeSize
    
    /**
    * Set type_site value
    *
    * @param string $value
    * @return null
    */
    function setTypeSize($value) {
      $this->type_site = $value;
    } // setTypeSize
    
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
    * Get precision
    *
    * @param null
    * @return integer
    */
    function getPrecision() {
      return $this->precision;
    } // getPrecision
    
    /**
    * Set precision value
    *
    * @param integer $value
    * @return null
    */
    function setPrecision($value) {
      $this->precision = $value;
    } // setPrecision
    
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
    * Get is_not_null
    *
    * @param null
    * @return boolean
    */
    function getIsNotNull() {
      return $this->is_not_null;
    } // getIsNotNull
    
    /**
    * Set is_not_null value
    *
    * @param boolean $value
    * @return null
    */
    function setIsNotNull($value) {
      $this->is_not_null = $value;
    } // setIsNotNull
    
    /**
    * Get is_unsigned
    *
    * @param null
    * @return boolean
    */
    function getIsUnsigned() {
      return $this->is_unsigned;
    } // getIsUnsigned
    
    /**
    * Set is_unsigned value
    *
    * @param boolean $value
    * @return null
    */
    function setIsUnsigned($value) {
      $this->is_unsigned = $value;
    } // setIsUnsigned
    
    /**
    * Get is_auto_increment
    *
    * @param null
    * @return boolean
    */
    function getIsAutoIncrement() {
      return $this->is_auto_increment;
    } // getIsAutoIncrement
    
    /**
    * Set is_auto_increment value
    *
    * @param boolean $value
    * @return null
    */
    function setIsAutoIncrement($value) {
      $this->is_auto_increment = $value;
    } // setIsAutoIncrement
  
  } // Angie_DBA_Generator_Field

?>