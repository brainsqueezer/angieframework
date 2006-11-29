<?php

  /**
  * Date time attribute
  * 
  * Date time attribute is described with a single date-time field
  *
  * @package Angie.DBA
  * @subpackage generator.attributes
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Attribute_DateTime extends Angie_DBA_Generator_Attribute {
    
    /**
    * Native (PHP) type. This value is used in generated docs for accessors
    *
    * @var string
    */
    protected $native_type = 'Angie_DateTime';
  
    /**
    * Return fields that describe this attribute
    *
    * @param void
    * @return Angie_DB_Field_DateTime
    */
    function getFields() {
      return new Angie_DB_Field_DateTime($this->getName(), $this->getDefaultValue(), $this->getRequired());
    } // getFields
  
  } // Angie_DBA_Generator_Attribute_DateTime

?>