<?php

  class Angie_DBA_Generator_Field_Enum extends Angie_DBA_Generator_Field {
  
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
      $this->setCastFunction('enumval');
      $this->setCastFunctionArguments(array(
        $possible_values,
        $default_value
      )); // setCastFunction
    } // __construct
  
  } // Angie_DBA_Generator_Field_Enum

?>