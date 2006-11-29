<?php

  /**
  * ID attribute
  * 
  * This class is used to describe ID attribute that is consistent of one field. It is represented by an unsigned 
  * integer that is auto_increment is most cases (optional)
  *
  * @package Angie.DBA
  * @subpackage generator.attributes
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Attribute_Id extends Angie_DBA_Generator_Attribute_Integer {
  
    /**
    * Constructor
    *
    * @param Angie_DBA_Generator_Entity $owner_entity
    * @param string $name
    * @param boolean $auto_increment
    * @return Angie_DBA_Generator_Attribute_Id
    */
    function __construct(Angie_DBA_Generator_Entity $owner_entity, $name, $auto_increment = true) {
      parent::__construct($owner_entity, $name, null, true, true, $auto_increment);
    } // __construct
  
  } // Angie_DBA_Generator_Attribute_Id

?>