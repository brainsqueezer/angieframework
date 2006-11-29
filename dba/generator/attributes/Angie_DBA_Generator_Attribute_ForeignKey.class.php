<?php

  /**
  * Foreign key attribute
  * 
  * This attribute is added to the entity to describe a foreign key attribute. 
  * It is usually an unsigned, required integer field
  *
  * @package Angie.DBA
  * @subpackage generator.attributes
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Attribute_ForeignKey extends Angie_DBA_Generator_Attribute_Integer {
  
    /**
    * Construct foreign key attribute
    *
    * @param Angie_DBA_Generator_Entity $entity
    * @param string $name
    * @param mixed $default_value
    * @return Angie_DBA_Generator_Attribute_ForeignKey
    */
    function __construct(Angie_DBA_Generator_Entity $entity, $name, $default_value = null) {
      parent::__construct($entity, $name, $default_value, true, true, false);
    } // __construct
    
  } // Angie_DBA_Generator_Attribute_ForeignKey

?>