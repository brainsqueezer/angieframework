<?php

  /**
  * Generator integer field
  *
  * @package Angie.DBA
  * @subpackage generator.fields
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Field_Integer extends Angie_DBA_Generator_Field {
  
    /**
    * Constructor
    *
    * Constructs the integer field. Same rules apply like for ordinary field except that type is always set to INTEGER. 
    * Properties that are most important for integer fields are set to be arguments of fields constructor
    * 
    * @param string $name
    * @param boolean $is_unsigned
    * @param boolean $is_auto_increment
    * @param string $size
    * @param mixed $properties
    * @return Angie_DBA_Generator_Field_Integer
    */
    function __construct($name, $is_unsigned = false, $is_auto_increment = false, $size = null, $properties = null) {
      parent::__construct($name, $properties);
      
      $this->setType(Angie_DBA_Generator::TYPE_INTEGER);
      $this->setCastFunction('intval');
      
      $this->setIsUnsigned($is_unsigned);
      $this->setIsAutoIncrement($is_auto_increment);
      if(!is_null($size)) {
        $this->setTypeSize($size);
      } // if
    } // __construct
  
  } // Angie_DBA_Generator_Field_Integer

?>