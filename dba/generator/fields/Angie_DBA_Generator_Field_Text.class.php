<?php

  /**
  * Text field
  * 
  * Text field is used to described long, multiline text values
  *
  * @package Angie.DBA
  * @subpackage generator.fields
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Field_Text extends Angie_DBA_Generator_Field {
  
    /**
    * Constructor
    *
    * Set propert type and provide easy access to size property
    * 
    * @param string $name
    * @param string $size
    * @param mixed $properties
    * @return Angie_DBA_Generator_Field_Text
    */
    function __construct($name, $size = null, $properties = null) {
      parent::__construct($name, $properties);
      
      $this->setType(Angie_DBA_Generator::TYPE_TEXT);
      $this->setCastFunction('strval');
      
      if(!is_null($size)) {
        $this->setTypeSize($size);
      } // if
    } // __construct
  
  } // Angie_DBA_Generator_Field_Text

?>