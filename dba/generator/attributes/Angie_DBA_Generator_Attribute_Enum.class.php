<?php

  class Angie_DBA_Generator_Attribute_Enum extends Angie_DBA_Generator_Attribute {
    
    /**
    * Array of valid attribute values
    *
    * @var array
    */
    private $valid_values = array();
    
    /**
    * Default value
    *
    * @var string
    */
    private $default_value;
  
    /**
    * Constructor
    *
    * @param Angie_DBA_Generator_Entity $owner_entity
    * @param string $name
    * @param array $valid_values
    * @param string $default_value
    * @return Angie_DBA_Generator_Attribute_Enum
    */
    function __construct(Angie_DBA_Generator_Entity $owner_entity, $name, $valid_values, $default_value) {
      $this->setValidValues($valid_values);
      $this->setDefaultValue($default_value);
      
      parent::__construct($owner_entity, $name);
    } // __construct
    
    /**
    * Return array of fields that describe this attribute
    *
    * @param void
    * @return Angie_DBA_Generator_Field
    */
    function getFields() {
      return new Angie_DBA_Generator_Field_Enum($this->getName(), $this->getValidValues(), $this->getDefaultValue());
    } // getFields
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get valid_values
    *
    * @param null
    * @return array
    */
    function getValidValues() {
      return $this->valid_values;
    } // getValidValues
    
    /**
    * Set valid_values value
    *
    * @param array $value
    * @return array
    */
    function setValidValues($values) {
      $this->valid_values = $values;
    } // setValidValues
    
    /**
    * Get default_value
    *
    * @param null
    * @return string
    */
    function getDefaultValue() {
      return $this->default_value;
    } // getDefaultValue
    
    /**
    * Set default_value value
    *
    * @param string $value
    * @return null
    */
    function setDefaultValue($value) {
      $this->default_value = $value;
    } // setDefaultValue
  
  } // Angie_DBA_Generator_Attribute_Enum

?>