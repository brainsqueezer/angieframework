<?php

  /**
  * Binary entity attribute
  * 
  * Binary attributes are used when we want to story large amount of binary data 
  * in database (files, images etc)
  *
  * @package Angie.DBA
  * @subpackage generator.attributes
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Attribute_Binary extends Angie_DBA_Generator_Attribute {
  
    /**
    * Native (PHP) type. This value is used in generated docs for accessors
    *
    * @var string
    */
    protected $native_type = 'binary';
  
    /**
    * Return fields that describe this attribute
    *
    * @param void
    * @return Angie_DB_Field_DateTime
    */
    function getFields() {
      return new Angie_DB_Field_Binary($this->getName(), $this->getDefaultValue(), $this->getRequired());
    } // getFields
  
  } // Angie_DBA_Generator_Attribute_Binary

?>