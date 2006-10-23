<?php

  /**
  * String attribute
  * 
  * Attribute that is represented by a single varchar field
  *
  * @package Angie.DBA
  * @subpackage generator.attributes
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_StringAttribute extends Angie_DBA_Generator_Attribute {
    
    /**
    * Field lenght
    *
    * @var integer
    */
    private $lenght;
  
    /**
    * Constructor
    *
    * @param Angie_DBA_Generator_Entity $entity
    * @param string $name
    * @param integer $lenght
    * @return Angie_DBA_Generator_StringAttribute
    */
    function __construct(Angie_DBA_Generator_Entity $entity, $name, $lenght) {
      parent::__construct($entity, $name);
      $this->setLenght($lenght);
    } // __construct
    
    /**
    * Return fields that represent this attribute
    *
    * @param void
    * @return Angie_DBA_Generator_Field
    */
    function getFields() {
      return new Angie_DBA_Generator_StringField($this->getName(), $this->getLenght());
    } // getFields
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get lenght
    *
    * @param null
    * @return integer
    */
    function getLenght() {
      return $this->lenght;
    } // getLenght
    
    /**
    * Set lenght value
    *
    * @param integer $value
    * @return null
    */
    function setLenght($value) {
      $this->lenght = $value;
    } // setLenght
  
  } // Angie_DBA_Generator_StringAttribute

?>