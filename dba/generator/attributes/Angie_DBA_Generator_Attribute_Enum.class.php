<?php

  /**
  * Enumerable entity attribute
  * 
  * This attribute describes a situation when entity has an attribute that can have only one value of given set of 
  * values.
  *
  * @package Angie.DBA
  * @subpackage generator.attributes
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Attribute_Enum extends Angie_DBA_Generator_Attribute {
    
    /**
    * Native (PHP) type. This value is used in generated docs for accessors
    *
    * @var string
    */
    protected $native_type = 'string';
    
    /**
    * Array of valid attribute values
    *
    * @var array
    */
    private $possible_values = array();
  
    /**
    * Constructor
    *
    * @param Angie_DBA_Generator_Entity $owner_entity
    * @param string $name
    * @param array $valid_values
    * @param string $default_value
    * @return Angie_DBA_Generator_Attribute_Enum
    */
    function __construct(Angie_DBA_Generator_Entity $owner_entity, $name, $default_value = null, $required = false, $possible_values = null) {
      parent::__construct($owner_entity, $name, $default_value, $required);
      $this->setPossibleValues($possible_values);
    } // __construct
    
    /**
    * Return array of fields that describe this attribute
    *
    * @param void
    * @return Angie_DB_Field_Enum
    */
    function getFields() {
      $enum_field = new Angie_DB_Field_Enum($this->getName(), $this->getDefaultValue(), $this->getRequired());
      $enum_field->setPossibleValues($this->getPossibleValues());
      
      return $enum_field;
    } // getFields
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get possible_values
    *
    * @param null
    * @return array
    */
    function getPossibleValues() {
      return $this->possible_values;
    } // getPossibleValues
    
    /**
    * Set possible_values value
    *
    * @param array $value
    * @return null
    */
    function setPossibleValues($value) {
      if(is_array($value)) {
        $this->possible_values = $value;
      } else {
        $this->possible_values = array();
      } // if
    } // setPossibleValues
  
  } // Angie_DBA_Generator_Attribute_Enum

?>