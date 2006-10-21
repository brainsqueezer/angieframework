<?php

  /**
  * String field
  * 
  * This class describes single string (VARCHAR) field and makes sure that proper type is used and that lenght property 
  * is present
  *
  * @package Angie.DBA
  * @subpackage generator.fields
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_StringField extends Angie_DBA_Generator_Field {
  
    /**
    * Constructor
    *
    * Construct string filed - make sure that type and lenght are properly set
    * 
    * @param string $name
    * @param integer $lenght
    * @param mixed $properties
    * @return Angie_DBA_Generator_StringField
    */
    function __construct($name, $lenght, $properties = null) {
      parent::__construct($name, $properties);
      $this->setType(Angie_DBA_Generator::TYPE_VARCHAR);
      $this->setLenght($lenght);
    } // __construct
  
  } // Angie_DBA_Generator_StringField

?>