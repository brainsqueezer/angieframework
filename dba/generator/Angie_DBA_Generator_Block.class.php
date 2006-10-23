<?php

  /**
  * Generator block
  * 
  * Define requirements for all generator blocks. Block is a peace of puzzle that will be used 
  * by generator to build a set of functional DBA classes - entity attributes, relationship 
  * definitions, objects that define some additional, specific behavior etc.
  * 
  * Responsability of a single block is to report what fields it requires (if any), it needs to 
  * know how to render all required methods etc
  *
  * @package Angie.DBA
  * @subpackage generator
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_DBA_Generator_Block {
    
    /**
    * Parent entity
    *
    * @var Angie_DBA_Generator_Entity
    */
    private $entity;
    
    /**
    * Construct the block and set owner entity
    *
    * @param Angie_DBA_Generator_Entity $entity
    * @return Angie_DBA_Generator_Block
    */
    function __construct(Angie_DBA_Generator_Entity $entity) {
      $this->setEntity($entity);
    } // __construct
    
    // ---------------------------------------------------
    //  Abstract
    // ---------------------------------------------------
  
    /**
    * Return all fields introduced by this block
    * 
    * This function needs to return array of entity fields that are introduced when this 
    * block was added to the entity - relationship fields, complex attributes etc
    *
    * @param void
    * @return array
    */
    abstract function getFields();
    
    /**
    * Render object class properties and methods
    *
    * @param void
    * @return null
    */
    abstract function renderObjectMembers();
    
    /**
    * Render manager class fields and methods
    *
    * @param void
    * @return null
    */
    abstract function renderManagerMembers();
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get entity
    *
    * @param null
    * @return Angie_DBA_Generator_Entity
    */
    function getEntity() {
      return $this->entity;
    } // getEntity
    
    /**
    * Set entity value
    *
    * @param Angie_DBA_Generator_Entity $value
    * @return null
    */
    function setEntity($value) {
      $this->entity = $value;
    } // setEntity
  
  } // Angie_DBA_Generator_Block

?>