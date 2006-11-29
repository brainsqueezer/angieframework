<?php

  /**
  * Text attribute
  * 
  * This class is used to set an attribute described by a long text field
  *
  * @package Angie.DBA
  * @subpackage generator.attributes
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Attribute_Text extends Angie_DBA_Generator_Attribute {
    
    /**
    * Native (PHP) type. This value is used in generated docs for accessors
    *
    * @var string
    */
    protected $native_type = 'string';
    
    /**
    * Return fields that are used for storing this attribute
    *
    * @param void
    * @return Angie_DB_Field_Text
    */
    function getFields() {
      return new Angie_DB_Field_Text($this->getName(), $this->getDefaultValue(), $this->getRequired());
    } // getFields
  
  } // Angie_DBA_Generator_Attribute_Text

?>