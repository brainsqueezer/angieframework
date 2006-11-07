<?php

  /**
  * Generator relationship
  * 
  * Childs of this class are used to describe relationship between two entities. There are 
  * four child classes:
  * 
  * - Angie_DBA_Generator_Relationship_HasOne - has one relationship
  * - Angie_DBA_Generator_Relationship_BelongsTo - belongs to relationship
  * - Angie_DBA_Generator_Relationship_HasMany - has many relationship
  * - Angie_DBA_Generator_Relationship_HasManyAndBelongsToMany - has many and belongs to many 
  *   relationship
  *
  * @package Angie.DBA
  * @subpackage generator
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_DBA_Generator_Relationship extends Angie_DBA_Generator_Block {
    
    /**
    * Relationship name
    *
    * @var string
    */
    private $name;
    
    /**
    * Relationship target entity
    *
    * @var Angie_DBA_Generator_Entity
    */
    private $target_entity;
    
    /**
    * Target entity name, set if we can't provide a target entity instance when we are 
    * creating the relationship
    *
    * @var string
    */
    private $target_entity_name;
  
    /**
    * Constructor
    * 
    * Construct the relationship between two entities. $owner_entity must be a valid 
    * Angie_DBA_Generator_Entity instance, while $target_entity can be a valid entity 
    * instance or entity name (if we don't have the entity defined).
    * 
    * $options is associtative array of option_name => option_value pairs. Option is 
    * set if setter method for given option name exists. For instance, if you want to 
    * set a class_name option setClassName() method must be defined
    *
    * @param Angie_DBA_Generator_Entity $owner_entity
    * @param mixed $target_entity
    * @return Angie_DBA_Generator_Relationship
    */
    function __construct(Angie_DBA_Generator_Entity $owner_entity, Angie_DBA_Generator_Entity $target_entity, $options = null) {
      parent::__construct($owner_entity);
      $this->setTargetEntity($target_entity);
      
      $allowed_methods = array('setName');
      $protected_methods = get_class_methods('Angie_DBA_Generator_Relationship');
      if(is_foreachable($protected_methods)) {
        foreach($protected_methods as $k => $protected_method) {
          if(in_array($protected_method, $allowed_methods)) {
            unset($protected_methods[$k]);
          } // if
        } // foreach
      } // if
      
      populate_through_setter($this, $options, $protected_methods);
    } // __construct
    
    // ---------------------------------------------------
    //  Generator
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
    
    /**
    * Get target_entity
    *
    * @param null
    * @return Angie_DBA_Generator_Entity
    */
    function getTargetEntity() {
      if($this->target_entity instanceof Angie_DBA_Generator_Entity) {
        return $this->target_entity;
      } // if
      
      $target_entity = Angie_DBA_Generator::getEntity($this->getTargetEntityName());
      if($target_entity instanceof Angie_DBA_Generator_Entity) {
        $this->setTargetEntity($target_entity);
      } // if
      
      return $this->target_entity;
    } // getTargetEntity
    
    /**
    * Set target_entity value
    *
    * @param Angie_DBA_Generator_Entity $value
    * @return null
    */
    function setTargetEntity(Angie_DBA_Generator_Entity $value) {
      $this->target_entity = $value;
      $this->target_entity_name = $value->getName();
    } // setTargetEntity
    
    /**
    * Get target_entity_name
    *
    * @param null
    * @return string
    */
    protected function getTargetEntityName() {
      return $this->target_entity_name;
    } // getTargetEntityName
    
    /**
    * Set target_entity_name value
    *
    * @param string $value
    * @return null
    */
    protected function setTargetEntityName($value) {
      $this->target_entity_name = $value;
    } // setTargetEntityName
  
  } // Angie_DBA_Generator_Relationship

?>