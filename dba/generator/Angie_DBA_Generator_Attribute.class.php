<?php

  /**
  * Entity attribute
  *
  * @package Angie.DBA
  * @subpackage generator
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_DBA_Generator_Attribute {
    
    /**
    * Attribute name
    *
    * @var string
    */
    private $name;
  
    /**
    * Constructor
    *
    * @param string $name
    * @return Angie_DBA_Generator_Attribute
    */
    function __construct($name) {
      $this->setName($name);
    } // __construct
    
    /**
    * Return array of fields that are used to describe this attribute
    * 
    * Attribute can be described with one or many field (object of Angie_DBA_Generator_Field class). This function 
    * returns a single field object or array of field objects
    *
    * @param void
    * @return mixed
    */
    abstract function getFields();
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get name
    *
    * @param null
    * @return string
    */
    function getName() {
      return $this->name;
    } // getName
    
    /**
    * Set name value
    *
    * @param string $value
    * @return null
    */
    function setName($value) {
      $this->name = $value;
    } // setName
  
  } // Angie_DBA_Generator_Attribute

?>