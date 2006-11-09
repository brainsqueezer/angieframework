<?php

  /**
  * Has many relationship
  * 
  * This class describes situation when $entity has a collection of $target_entity related objects. Options for this 
  * relatinship:
  * 
  * - name            - Relationship name, inherited from base class. If name is not present script will use plural of 
  *                     target  entity name. Relationship names are use for generating method names. Example: 
  *                     relationship named 'owned_companies' will result in methods such as getOwnedCompanies(), 
  *                     addOwnedCompany() etc.
  * - foreign key     - Name of the field in target entity that is for lookup. If it is not set by the user script will 
  *                     use singular of target entity name + '_id' sufix (Examples: user_id, book_id).
  * - conditions      - Additional conditions that are appended to foreign_key = ID condition. This is ignored if user 
  *                     provides a full finder SQL.
  * - order           - Order part of the extraction SQL. It is ignored if finder SQL is provided.
  * - finder_sql      - Full SQL query that is used for extraction of data.
  * - counter_sql     - Full SQL query that is used for couinting related entities.
  * - on_delete       - This option determins how will script behave when owner object is deleted. There are four 
  *                     possible behaviors - cascade (related object will be loaded and deleted by calling their delete 
  *                     methods), delete (related objects will be deleted using a single delete query, fast but don't 
  *                     work if an object needs to do some clean-up), nullify (values of foreign keys for related 
  *                     objects will be reseted to NULL or 0) and do nothing.
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
    
    /**
    * Complete SQL statement that is used to delete related object
    *
    * @var string
    */
    private $deleter_sql;
    
    /**
    * Complete SQL statement that is used to nullify relation
    *
    * @var string
    */
    private $nullifier_sql;
    
    /**
    * Value of on_delete option
    * 
    * See class description for more details on possible values of on_delete option and what they mean
    *
    * @var string
    */
    private $on_delete;
    
    /**
    * Constructor has many relationship
    *
    * @param Angie_DBA_Generator_Entity $owner_entity
    * @param mixed $target_entity
    * @return Angie_DBA_Generator_Relationship
    */
    function __construct(Angie_DBA_Generator_Entity $owner_entity, Angie_DBA_Generator_Entity $target_entity, $options = null) {
      parent::__construct($owner_entity, $target_entity, $options);
      
      $foreign_key = $this->getForeignKey();
        
      if(!$target_entity->fieldExists($foreign_key)) {
        $target_entity->addField(new Angie_DBA_Generator_IntegerField($foreign_key, true), $this);
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
      return 'get' . Angie_Inflector::camelize($this->getName());
    } // getGetterName
    
    /**
    * Return counter method name
    *
    * @param void
    * @return string
    */
    function getCounterName() {
      return 'count' . Angie_Inflector::camelize($this->getName());
    } // getCounterName
    
    /**
    * Return adder method name
    *
    * @param void
    * @return null
    */
    function getAdderName() {
      return 'add' . Angie_Inflector::camelize(Angie_Inflector::singularize($this->getName()));
    } // getAdderName
    
    /**
    * Return name of the method that is used to delete all related entries
    *
    * @param void
    * @return string
    */
    function getDeleterName() {
      return 'delete' . Angie_Inflector::camelize($this->getName());
    } // getDeleterName
    
    /**
    * Return name of the method that is used to nullify all related entries
    *
    * @param void
    * @return string
    */
    function getNullifierName() {
      return 'nullify' . Angie_Inflector::camelize($this->getName());
    } // getNullifierName
    
    /**
    * Return foreign key setter function name
    *
    * @param void
    * @return string
    */
    function getForeignKeySetter() {
      return 'set' . Angie_Inflector::camelize($this->getForeignKey());
    } // getForeignKeySetter
    
    /**
    * Return name of primary key field of owner entity. If PK is complex name of the first field is returned
    *
    * @param void
    * @return string
    */
    function getEntityPrimaryKeyName() {
      return array_var($this->getOwnerEntity()->getPrimaryKeyFieldNames(), 0);
    } // getEntityPrimaryKeyName
    
    /**
    * Return getter name for primary key field of owner entity
    *
    * @param void
    * @return string
    */
    function getEntityPrimaryKeyGetter() {
      return 'get' . Angie_Inflector::camelize($this->getEntityPrimaryKeyName());
    } // getEntityPrimaryKeyGetter
    
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
        return $this->getOwnerEntity()->getName() . '_id';
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
    
    /**
    * Get deleter_sql
    *
    * @param null
    * @return string
    */
    function getDeleterSql() {
      return $this->deleter_sql;
    } // getDeleterSql
    
    /**
    * Set deleter_sql value
    *
    * @param string $value
    * @return null
    */
    function setDeleterSql($value) {
      $this->deleter_sql = $value;
    } // setDeleterSql
    
    /**
    * Get nullifier_sql
    *
    * @param null
    * @return string
    */
    function getNullifierSql() {
      return $this->nullifier_sql;
    } // getNullifierSql
    
    /**
    * Set nullifier_sql value
    *
    * @param string $value
    * @return null
    */
    function setNullifierSql($value) {
      $this->nullifier_sql = $value;
    } // setNullifierSql
    
    /**
    * Get on_delete
    *
    * @param null
    * @return string
    */
    function getOnDelete() {
      if($this->on_delete && Angie_DBA_Generator::isValidOnDeleteValue($this->on_delete)) {
        return $this->on_delete;
      } else {
        return Angie_DBA_Generator::ON_DELETE_NULLIFY;
      } // if
    } // getOnDelete
    
    /**
    * Set on_delete value
    *
    * @param string $value
    * @return null
    * @throws Angie_Core_Error_InvalidParamValue if $value is not a valid on_delete option value
    */
    function setOnDelete($value) {
      if(Angie_DBA_Generator::isValidOnDeleteValue($value)) {
        $this->on_delete = $value;
      } else {
        throw new Angie_Core_Error_InvalidParamValue('value', $value, '$value is not a valid on_delete option value');
      } // if
    } // setOnDelete
  
  } // Angie_DBA_Generator_Relationship_HasMany

?>