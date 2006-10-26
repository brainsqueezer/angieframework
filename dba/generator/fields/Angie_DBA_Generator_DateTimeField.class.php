<?php

  /**
  * Date-time field
  * 
  * Make sure that date-time type is enforced
  *
  * @package Angie.DBA
  * @subpackage generator.fields
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_DateTimeField extends Angie_DBA_Generator_Field {
  
    /**
    * Constructor
    *
    * @param void
    * @return Angie_DBA_Generator_DateTimeField
    */
    function __construct($name, $properties = null) {
      parent::__construct($name, $properties);
      $this->setType(Angie_DBA_Generator::TYPE_DATETIME);
      $this->setCastFunction('datetimeval');
    } // __construct
  
  } // Angie_DBA_Generator_DateTimeField

?>