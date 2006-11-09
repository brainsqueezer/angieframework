<?php

  /**
  * Belongs to relationship
  *
  * @package Angie.DBA
  * @subpackage generator.relationships
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Relationship_BelongsTo extends Angie_DBA_Generator_Relationship {
  
    /**
    * Name of the foreign_key field
    *
    * @var string
    */
    private $foreign_key;
    
    /**
    * Additional conditions that will be used for reading related objects
    *
    * @var string
    */
    private $conditions;
    
    /**
    * Order part of the query used when querying related objects
    *
    * @var string
    */
    private $order;
    
    /**
    * Complete SQL statement that is used to read related object
    *
    * @var string
    */
    private $finder_sql;
    
    /**
    * Construct belongs to relationship
    *
    * @param Angie_DBA_Generator_Entity $owner_entity
    * @param mixed $target_entity
    * @return Angie_DBA_Generator_Relationship
    */
    function __construct(Angie_DBA_Generator_Entity $owner_entity, Angie_DBA_Generator_Entity $target_entity, $options = null) {
      parent::__construct($owner_entity, $target_entity, $options);
      
      $foreign_key = $this->getForeignKey();
        
      if(!$owner_entity->fieldExists($foreign_key)) {
        $owner_entity->addField(new Angie_DBA_Generator_IntegerField($foreign_key, true), $this);
      } // if
    } // __construct
    
    /**
    * Render object class properties and methods
    *
    * @param void
    * @return null
    */
    function renderObjectMembers() {
      Angie_DBA_Generator::assignToView('relationship', $this);
      Angie_DBA_Generator::assignToView('entity', $this->getOwnerEntity());
      Angie_DBA_Generator::assignToView('target_entity', $this->getTargetEntity());
      Angie_DBA_Generator::displayView('belongs_to_relationship');
    } // renderObjectMembers
    
    // ---------------------------------------------------
    //  Util methods
    // ---------------------------------------------------
    
    /**
    * Return getter name
    *
    * @param void
    * @return string
    */
    function getGetterName() {
      return 'get' . Angie_Inflector::camelize($this->getName());
    } // getGetterName
    
    /**
    * Return setter name
    *
    * @param void
    * @return string
    */
    function getSetterName() {
      return 'set' . Angie_Inflector::camelize($this->getName());
    } // getSetterName
    
    /**
    * Return foreign key getter name
    *
    * @param void
    * @return string
    */
    function getForeignKeyGetterName() {
      return 'get' . Angie_Inflector::camelize($this->getForeignKey());
    } // getForeignKeyGetterName
    
    /**
    * Return foreign key setter name
    *
    * @param void
    * @return string
    */
    function getForeignKeySetterName() {
      return 'set' . Angie_Inflector::camelize($this->getForeignKey());
    } // getForeignKeySetterName
    
    /**
    * Return target entity primary key name - if complex first field will be selected
    *
    * @param void
    * @return string
    */
    function getTargetEntityPrimaryKeyName() {
      return array_var($this->getTargetEntity()->getPrimaryKeyFieldNames(), 0);
    } // getTargetEntityPrimaryKeyName
    
    /**
    * Return getter name of the target entity primary key field name
    *
    * @param void
    * @return string
    */
    function getTargetEntityPrimaryKeyGetter() {
      return 'get' . Angie_Inflector::camelize($this->getTargetEntityPrimaryKeyName());
    } // getTargetEntityPrimaryKeyName
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Return relationship name
    *
    * @param void
    * @return string
    */
    function getName() {
      $name = parent::getName();
      if($name) {
        return $name;
      } else {
        $foreign_key = $this->getForeignKey();
        if($foreign_key) {
          if(str_ends_with($foreign_key, '_id')) {
            return substr($foreign_key, 0, strlen($foreign_key) - 3);
          } else {
            return $foreign_key;
          } // if
        } else {
          return $this->getTargetEntityName();
        } // if
      } // if
    } // getName
    
    /**
    * Get foreign_key
    *
    * @param null
    * @return string
    */
    function getForeignKey() {
      if($this->foreign_key) {
        return $this->foreign_key;
      } else {
        return $this->getTargetEntityName() . '_id';
      } // if
    } // getForeignKey
    
    /**
    * Set foreign_key value
    *
    * @param string $value
    * @return null
    */
    function setForeignKey($value) {
      $this->foreign_key = $value;
    } // setForeignKey
    
    /**
    * Get conditions
    *
    * @param null
    * @return string
    */
    function getConditions() {
      return $this->conditions;
    } // getConditions
    
    /**
    * Set conditions value
    *
    * @param string $value
    * @return null
    */
    function setConditions($value) {
      $this->conditions = $value;
    } // setConditions
    
    /**
    * Get order
    *
    * @param null
    * @return string
    */
    function getOrder() {
      return $this->order;
    } // getOrder
    
    /**
    * Set order value
    *
    * @param string $value
    * @return null
    */
    function setOrder($value) {
      $this->order = $value;
    } // setOrder
    
    /**
    * Get finder_sql
    *
    * @param null
    * @return string
    */
    function getFinderSql() {
      return $this->finder_sql;
    } // getFinderSql
    
    /**
    * Set finder_sql value
    *
    * @param string $value
    * @return null
    */
    function setFinderSql($value) {
      $this->finder_sql = $value;
    } // setFinderSql
  
  } // Angie_DBA_Generator_Relationship_BelongsTo

?>