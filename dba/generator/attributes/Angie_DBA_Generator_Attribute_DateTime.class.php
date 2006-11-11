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
    * Return fields that describe this attribute
    *
    * @param void
    * @return Angie_DBA_Generator_Attribute_DateTime
    */
    function getFields() {
      return new Angie_DBA_Generator_Field_DateTime($this->getName());
    } // getFields
  
  } // Angie_DBA_Generator_Attribute_DateTime

?>