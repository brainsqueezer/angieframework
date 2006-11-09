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
    * Owner entity
    *
    * @var Angie_DBA_Generator_Entity
    */
    private $owner_entity;
    
    /**
    * Construct the block and set owner entity
    *
    * @param Angie_DBA_Generator_Entity $owner_entity
    * @return Angie_DBA_Generator_Block
    */
    function __construct(Angie_DBA_Generator_Entity $owner_entity) {
      $this->setOwnerEntity($owner_entity);
    } // __construct
    
    // ---------------------------------------------------
    //  Abstract
    // ---------------------------------------------------
    
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
    * Get owner_entity
    *
    * @param null
    * @return Angie_DBA_Generator_Entity
    */
    function getOwnerEntity() {
      return $this->owner_entity;
    } // getOwnerEntity
    
    /**
    * Set owner_entity value
    *
    * @param Angie_DBA_Generator_Entity $value
    * @return null
    */
    function setOwnerEntity(Angie_DBA_Generator_Entity $value) {
      $this->owner_entity = $value;
    } // setOwnerEntity
  
  } // Angie_DBA_Generator_Block

?>