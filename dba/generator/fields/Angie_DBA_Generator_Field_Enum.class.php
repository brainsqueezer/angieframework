<?php

  /**
  * Enumerable generator field
  * 
  * This field can hold only one of given set of values
  *
  * @package Angie.DBA
  * @subpackage generator.fields
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Field_Enum extends Angie_DBA_Generator_Field {
    
    /**
    * Array of possible enum values
    *
    * @var array
    */
    private $possible_value;
    
    /**
    * Default value
    *
    * @var string
    */
    private $default_values;
  
    /**
    * Constructor
    * 
    * $possible_values and $default_value are used for creating rules for enumeration - first one represent the array 
    * of values that this field can have and second one represents default value used
    *
    * @param void
    * @return Angie_DBA_Generator_Field_Enum
    */
    function __construct($name, $possible_values, $default_value) {
      parent::__construct($name);
      
      $this->setType(Angie_DBA_Generator::TYPE_ENUM);
      $this->setPossibleValues($possible_values);
      $this->setDefaultValue($default_value);
      
      $this->setCastFunction('enumval');
      $this->setCastFunctionArguments(array(
        $possible_values,
        $default_value
      )); // setCastFunction
    } // __construct
    
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
      $this->possible_values = $value;
    } // setPossibleValues
    
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
  
  } // Angie_DBA_Generator_Field_Enum

?>