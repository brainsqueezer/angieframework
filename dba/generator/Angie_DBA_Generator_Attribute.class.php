<?php

  /**
  * Entity attribute
  *
  * @package Angie.DBA
  * @subpackage generator
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_DBA_Generator_Attribute extends Angie_DBA_Generator_Block {
    
    /**
    * Attribute name
    *
    * @var string
    */
    private $name;
  
    /**
    * Constructor
    *
    * @param Angie_DBA_Generator_Entity $entity
    * @param string $name
    * @return Angie_DBA_Generator_Attribute
    */
    function __construct(Angie_DBA_Generator_Entity $entity, $name) {
      parent::__construct($entity);
      $this->setName($name);
    } // __construct
    
    // ---------------------------------------------------
    //  Renderer
    // ---------------------------------------------------
    
    /**
    * Render object class properties and methods
    *
    * @param void
    * @return null
    */
    function renderObjectMembers() {
      return;
    } // renderObjectMembers
    
    /**
    * Render manager class fields and methods
    *
    * @param void
    * @return null
    */
    function renderManagerMembers() {
      return;
    } // renderManagerMembers
    
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