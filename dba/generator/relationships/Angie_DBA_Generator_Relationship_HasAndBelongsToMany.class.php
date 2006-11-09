<?php

  /**
  * Has and belongs to many description
  * 
  * This class is used to describe M:N relation between two entities through join table. Options:
  * 
  * - join_table      - Name of the join table. If null it will be made by combining plural of both owner and target 
  *                     entity names and sorting them alphabetically. For instance, relation between user and project 
  *                     entity will result in projects_users table name. 
  * - owner_key       - Name of the key in join table that coresponds to PK value in owner entity. If it is NULL it will 
  *                     be made like singular of owner entity name + '_id' sufix. user_id for example...
  * - target_key      - Name of the key in join table that coresponds to PK value in target entity entity. If it is NULL 
  *                     it will be made like singular of target entity name + '_id' sufix. project_id for example...
  * - conditions      - Additional conditions that are used for extraction
  * - order           - Order part of the query
  * - finder_sql      - Full SQL statement that is used for extraction. If this value is set conditions and order 
  *                     options are ignored
  * - counter_sql     - Full SQL used for counting the number of relations
  * - adder_sql       - SQL statement that is used for adding new entry in relations table. It will be pulled through 
  *                     prepareString() function with first parametar beeing value of owner entity PK and second 
  *                     parametar beeing value of target entity PK
  * - deleter_sql     - SQL statement that will be used to drop specific relation from join table. As with adder_sql 
  *                     this one will also be pulled through prepareString() function with first parametar beeing value 
  *                     of owner entity PK and second parametar beeing value of target entity PK
  * - cleaner_sql     - SQL statement that will drop all relations by owner entity primary key value (value is passed to 
  *                     prepareString())
  *
  * @package Angie.DBA
  * @subpackage generator.relationships
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Relationship_HasAndBelongsToMany extends Angie_DBA_Generator_Relationship {
    
    /**
    * Name of the join table
    *
    * @var string
    */
    private $join_table;
    
    /**
    * Name of owner key field
    *
    * @var string
    */
    private $owner_key;
    
    /**
    * Name of target key field
    *
    * @var string
    */
    private $target_key;
    
    /**
    * Additional findex / counter conditions
    *
    * @var string
    */
    private $conditions;
    
    /**
    * Order settings used in finder
    *
    * @var string
    */
    private $order;
    
    /**
    * Full finder SQL statement
    *
    * @var string
    */
    private $finder_sql;
    
    /**
    * Full counter SQL statement
    *
    * @var string
    */
    private $counter_sql;
    
    /**
    * Full adder SQL statement with sockets for owner and target key values
    *
    * @var string
    */
    private $adder_sql;
    
    /**
    * Full deleter SQL statement with sockets for owner and target key values
    *
    * @var string
    */
    private $deleter_sql;
    
    /**
    * Full SQL that is used for celaning relations between two objects
    *
    * @var string
    */
    private $cleaner_sql;
    
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
      Angie_DBA_Generator::displayView('has_and_belongs_to_many_relationship');
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
      return 'get' . Angie_Inflector::camelize(Angie_Inflector::pluralize($this->getTargetEntity()->getName()));
    } // getGetterMethod
    
    /**
    * Return name of the method that will set a collection of related objects
    *
    * @param void
    * @return string
    */
    function getSetterName() {
      return 'set' . Angie_Inflector::camelize(Angie_Inflector::pluralize($this->getTargetEntity()->getName()));
    } // getSetterName
    
    /**
    * Return counter name method
    *
    * @param void
    * @return string
    */
    function getCounterName() {
      return 'count' . Angie_Inflector::camelize(Angie_Inflector::pluralize($this->getTargetEntity()->getName()));
    } // getCounterName
    
    /**
    * Return name of the method that is used to clean up the collection of related objects
    *
    * @param void
    * @return string
    */
    function getCleanerName() {
      return 'clear' . Angie_Inflector::camelize(Angie_Inflector::pluralize($this->getTargetEntity()->getName()));
    } // getCleanerName
    
    /**
    * Return name of the method that is used to add single object to collection
    *
    * @param void
    * @return string
    */
    function getAdderName() {
      return 'add' . Angie_Inflector::camelize($this->getTargetEntity()->getName());
    } // getAdderName
    
    /**
    * Return name of the method that is used to remove specific object from the collection
    *
    * @param void
    * @return string
    */
    function getDeleterName() {
      return 'delete' . Angie_Inflector::camelize($this->getTargetEntity()->getName()) . 'Relation';
    } // getDeleterName
    
    /**
    * Return name of the method that will delete all realtions, but not the related objects
    *
    * @param void
    * @return string
    */
    function getAllRelationsDeleterName() {
      return 'delete' . Angie_Inflector::camelize($this->getTargetEntity()->getName()) . 'Relations';
    } // getAllRelationsDeleterName
    
    /**
    * Return primary key field name of owner entity
    *
    * @param void
    * @return string
    */
    function getOwnerEntityPrimaryKeyName() {
      return array_var($this->getOwnerEntity()->getPrimaryKeyFieldNames(), 0);
    } // getOwnerEntityPrimaryKeyName
    
    /**
    * Return owner entity primary key getter
    *
    * @param void
    * @return string
    */
    function getOwnerEntityPrimaryKeyGetterName() {
      return 'get' . Angie_Inflector::camelize($this->getOwnerEntityPrimaryKeyName());
    } // getOwnerEntityPrimaryKeyGetterName
    
    /**
    * Return primary key field name of target entity
    *
    * @param void
    * @return string
    */
    function getTargetEntityPrimaryKeyName() {
      return array_var($this->getTargetEntity()->getPrimaryKeyFieldNames(), 0);
    } // getTargetEntityPrimaryKeyName
    
    /**
    * Return target entity primary key getter
    *
    * @param void
    * @return string
    */
    function getTargetEntityPrimaryKeyGetterName() {
      return 'get' . Angie_Inflector::camelize($this->getTargetEntityPrimaryKeyName());
    } // getTargetEntityPrimaryKeyGetterName
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Return relation name
    *
    * @param void
    * @return string
    */
    function getName() {
      $name = trim(parent::getName());
      if($name) {
        return $name;
      } else {
        return $this->getOwnerEntity()->getName() . '_has_and_belongs_to_many_' . $this->getTargetEntity()->getName() . '_through_' . $this->getJoinTable();
      } // if
    } // getName
    
    /**
    * Get join_table
    *
    * @param null
    * @return string
    */
    function getJoinTable() {
      if(trim($this->join_table)) {
        return $this->join_table;
      } else {
        return Angie_DBA_Generator::getHABTMJoinTableName($this->getOwnerEntity(), $this->getTargetEntity());
      } // if
    } // getJoinTable
    
    /**
    * Set join_table value
    *
    * @param string $value
    * @return null
    */
    function setJoinTable($value) {
      $this->join_table = $value;
    } // setJoinTable
    
    /**
    * Get owner_key
    *
    * @param null
    * @return string
    */
    function getOwnerKey() {
      if(trim($this->owner_key)) {
        return $this->owner_key;
      } else {
        return $this->getOwnerEntity()->getName() . '_id';
      } // if
    } // getOwnerKey
    
    /**
    * Set owner_key value
    *
    * @param string $value
    * @return null
    */
    function setOwnerKey($value) {
      $this->owner_key = $value;
    } // setOwnerKey
    
    /**
    * Get target_key
    *
    * @param null
    * @return string
    */
    function getTargetKey() {
      if($this->target_key) {
        return $this->target_key;
      } else {
        return $this->getTargetEntity()->getName() . '_id';
      } // if
    } // getTargetKey
    
    /**
    * Set target_key value
    *
    * @param string $value
    * @return null
    */
    function setTargetKey($value) {
      $this->target_key = $value;
    } // setTargetKey
    
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
    * Get adder_sql
    *
    * @param null
    * @return string
    */
    function getAdderSql() {
      return $this->adder_sql;
    } // getAdderSql
    
    /**
    * Set adder_sql value
    *
    * @param string $value
    * @return null
    */
    function setAdderSql($value) {
      $this->adder_sql = $value;
    } // setAdderSql
    
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
    * Get cleaner_sql
    *
    * @param null
    * @return string
    */
    function getCleanerSql() {
      return $this->cleaner_sql;
    } // getCleanerSql
    
    /**
    * Set cleaner_sql value
    *
    * @param string $value
    * @return null
    */
    function setCleanerSql($value) {
      $this->cleaner_sql = $value;
    } // setCleanerSql
  
  } // Angie_DBA_Generator_Relationship_HasAndBelongsToMany

?>