<?php

  /**
  * Has many relationship
  * 
  * This class describes situation when $entity has a collection of $target_entity related objects. Options for this 
  * relatinship:
  * 
  * - name            - Relationship name, inherited from base class. If name is not present script will use plural of 
  *                     target  entity name.
  * - foreign key     - Name of the field in target entity that is for lookup. If it is not set by the user script will 
  *                     use singular of target entity name + '_id' sufix (Examples: user_id, book_id).
  * - conditions      - Additional conditions that are appended to foreign_key = ID condition. This is ignored if user 
  *                     provides a full finder SQL.
  * - order           - Order part of the extraction SQL. It is ignored if finder SQL is provided.
  * - finder_sql      - Full SQL query that is used for extraction of data.
  * - counter_sql     - Full SQL query that is used for couinting related entities.
  *
  * @package Angie.DBA
  * @subpackage generator.relationships
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Relationship_HasMany extends Angie_DBA_Generator_Relationship {
    
    /**
    * Name of the foreign key field
    *
    * @var string
    */
    private $foreign_key;
    
    /**
    * Query conditions, ignored if $finder_sql is present
    *
    * @var string
    */
    private $conditions;
    
    /**
    * Query order, ignored if $finder_sql is present
    *
    * @var string
    */
    private $order;
    
    /**
    * Finder SQL
    *
    * @var string
    */
    private $finder_sql;
    
    /**
    * Counter SQL
    *
    * @var string
    */
    private $counter_sql;
    
    // ---------------------------------------------------
    //  Implementation of abstract methods
    // ---------------------------------------------------
    
    /**
    * Return additional fields introduced by this relationship
    *
    * @param void
    * @return array
    */
    function getFields() {
      return null;
    } // getFields
    
    /**
    * Render object class properties and methods
    *
    * @param void
    * @return null
    */
    function renderObjectMembers() {
      Angie_DBA_Generator::assignToView('relationship', $this);
      Angie_DBA_Generator::assignToView('entity', $this->getEntity());
      Angie_DBA_Generator::assignToView('target_entity', $this->getTargetEntity());
      Angie_DBA_Generator::displayView('has_many_relationship');
    } // renderObjectMembers
    
    // ---------------------------------------------------
    //  Helper methods
    // ---------------------------------------------------
    
    /**
    * Return getter name
    *
    * @param void
    * @return string
    */
    function getGetterName() {
      return 'get' . Angie_Inflector::camelize($this->getRelationName());
    } // getGetterName
    
    /**
    * Return counter method name
    *
    * @param void
    * @return string
    */
    function getCounterName() {
      return 'count' . Angie_Inflector::camelize($this->getRelationName());
    } // getCounterName
    
    /**
    * Return adder method name
    *
    * @param void
    * @return null
    */
    function getAdderName() {
      return 'add' . Angie_Inflector::camelize(Angie_Inflector::singularize($this->getRelationName()));
    } // getAdderName
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get relationship name
    * 
    * If name is not set by the user this function will make it as a plural of raget entity's name. Example:
    * 
    * User has many company entities - companies
    * User has many book entities - books
    * Catalog has many page entities - pages
    *
    * @param null
    * @return string
    */
    function getName() {
      $name = parent::getName();
      if($name) {
        return $name;
      } else {
        return Angie_Inflector::pluralize($this->getTargetEntityName());
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
        return $this->getEntity()->getName() . '_id';
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
    * @return mixed
    */
    function getConditions() {
      return $this->conditions;
    } // getConditions
    
    /**
    * Set conditions value
    *
    * @param mixed $value
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
    
    /**
    * Get counter_sql
    *
    * @param null
    * @return string
    */
    function getCounterSql() {
      return $this->counter_sql;
    } // getCounterSql
    
    /**
    * Set counter_sql value
    *
    * @param string $value
    * @return null
    */
    function setCounterSql($value) {
      $this->counter_sql = $value;
    } // setCounterSql
  
  } // Angie_DBA_Generator_Relationship_HasMany

?>