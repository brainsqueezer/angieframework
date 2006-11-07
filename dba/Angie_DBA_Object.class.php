<?php

  /**
  * Single DBA object
  *
  * @package Angie.DBA
  * @subpackage runtime
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Object {
    
    /**
    * Array of primary key fields
    *
    * @var array
    */
    protected $primary_key;
    
    /**
    * Array of object files
    * 
    * Populated by generator in child class
    *
    * @var array
    */
    protected $fields;
    
    /**
    * Array of detail fields
    *
    * @var array
    */
    protected $detail_fields;
    
    /**
    * Details loaded indicator
    * 
    * Value is set to true when details are loaded
    *
    * @var boolean
    */
    protected $details_loaded = false;
    
    /**
    * Name of the table where we story instances of this object
    * 
    * Populated by generator in child class
    *
    * @var string
    */
    protected $table_name;
    
    /**
    * Name of manager class
    * 
    * Populated by generator in child classes
    *
    * @var string
    */
    protected $manager_class;
    
    /**
  	* Array of protected attributes that can not be set through mass-assignment functions 
  	* (like setFromAttributes)
  	*
  	* @var array
  	*/
  	protected $attr_protected;
  	
  	/**
  	* Array of acceptable attributes (fields) that can be set through mass-assignment function 
  	* (setFromAttributes)
  	*
  	* @var array
  	*/
  	protected $attr_acceptable;
  	
  	/**
  	* Name of the auto increment field
  	*
  	* @var string
  	*/
  	protected $auto_increment_field;
  	
  	/**
  	* Array where relations can store cached values to save load time on next request
  	*
  	* @var array
  	*/
  	protected $cache = array();
  
    /**
  	* Indicates if this is new object (not saved)
  	*
  	* @var boolean
  	*/
  	private $is_new = true;
  	
  	/**
  	* Indicates if this object have been deleted from database
  	*
  	* @var boolean
  	*/
  	private $is_deleted = false;
  	
  	/**
  	* Object is loaded
  	*
  	* @var boolean
  	*/
  	private $is_loaded = false;
  	
  	/**
  	* Associative array of field values. Values are stored as native PHP values
  	*
  	* @var array
  	*/
  	private $field_values = array();
  	
  	/**
  	* Array of modified fields (if any)
  	*
  	* @var array
  	*/
  	private $modified_fields = array();
  	
  	/**
  	* Array of updated primary key fields with cached old values (used in WHERE on update 
  	* or delete)
  	*
  	* @var array
  	*/
  	private $updated_pks = array();
  	
  	/**
  	* Validate input data (usualy collected from from)
  	* 
  	* This method is called before the item is saved and can be used to fetch errors in 
  	* data before we really save it database. $errors array is populated with errors
  	*
  	* @param array $errors
  	* @return boolean
  	* @throws Angie_DBA_Error_Validation
  	*/
  	function validate($errors) {
  	  return true;
  	} // validate
  	
  	/**
  	* Set multiple attributes at the same time
  	* 
  	* Use this function to mass set field values for this object. $attributes argument is an associative array where key 
  	* is the field name and value is new value for that field. If specific field is in protected list it will be 
  	* ignored. Also, if list of accepted field is defined and field is not in that list it will be ignored.
  	*
  	* @param array $attributes
  	* @return null
  	*/
  	function set($attributes) {
  	  if(is_array($attributes)) {
  	    foreach($attributes as $k => &$v) {
  	      if(count($this->attr_protected) && in_array($k, $this->attr_protected)) {
  	        continue; // protected attribute
  	      } // if
  	      if(count($this->attr_acceptable) && !in_array($k, $this->attr_acceptable)) {
  	        continue; // not acceptable
  	      } // if
  	      $this->setFieldValue($k, $attributes[$k]); // field exists, set
  	    } // foreach
  	  } // if
  	} // set
  	
  	/**
  	* Check if specific field exists in this object
  	*
  	* @param string $field_name
  	* @return boolean
  	*/
  	function fieldExists($field_name) {
  	  return in_array($field_name, $this->fields);
  	} // fieldExists
  	
  	/**
  	* Check if specific field is part of the primary key
  	*
  	* @param string $field_name
  	* @return boolean
  	*/
  	function isPrimaryKeyField($field_name) {
  	  return in_array($field_name, $this->primary_key);
  	} // isPrimaryKeyField
  	
  	/**
  	* Returns true if $field_name is modified
  	*
  	* @param string $field_name
  	* @return boolean
  	*/
  	function isModifiedField($field_name) {
  	  return isset($this->modified_fields[$field_name]);
  	} // isModifiedField
  	
  	/**
  	* Report modified filed
  	*
  	* @param string $field_name
  	* @return null
  	*/
  	protected function addModifiedField($field_name) {
  	  if(!in_array($field_name, $this->modified_fields)) {
  	    $this->modified_fields[] = $field_name;
  	  } // if
  	} // addModifiedField
  	
  	/**
  	* Check if this field is PK and if it is modified
  	*
  	* @param string $field_name
  	* @return boolean
  	*/
  	function isModifiedPrimaryKeyField($field_name) {
  	  if($this->isPrimaryKeyField($field_name)) {
  	    return isset($this->modified_fields[$field_name]);
  	  } // if
  	  
  	  return false;
  	} // isModifiedPrimaryKeyFiled
  	
  	/**
  	* Check if $field_name is a detail field
  	*
  	* @param string $field_name
  	* @return boolean
  	*/
  	protected function isDetailField($field_name) {
  	  return in_array($field_name, $this->detail_fields);
  	} // isDetailField
  	
  	/**
  	* Return value of PK colum(s) that was initaly loaded
  	*
  	* @param void
  	* @return array
  	*/
  	function getInitialPkValue() {
			$ret = array();
			
			foreach($this->primary_key as $field_name) {
			  $ret[$field_name] = $this->isModifiedPrimaryKeyField($field_name) ?
			    $this->modified_fields[$field_name] :
			    $this->getFieldValue($field_name);
			} // foreach
			
			return $ret;
  	} // getInitialPkValue
  	
  	/**
  	* Return true if $field_name is an auto_increment field
  	*
  	* @param string $field_name
  	* @return boolean
  	*/
  	function isAutoIncrementField($field_name) {
  	  return $this->auto_increment_field == $field_name;
  	} // isAutoIncrementField
  	
  	/**
  	* Return value of specific field
  	*
  	* @param string $field_name
  	* @param mixed $default
  	* @return mixed
  	*/
  	protected function getFieldValue($field_name, $default = null) {
  	  if($this->isLoaded() && $this->isDetailField($field_name) && !$this->detailsLoaded()) {
        $this->loadDetails();
  	  } // if
  	  
  	  if(isset($this->field_values[$field_name])) {
  	    return $this->field_values[$field_name];
  	  } // if
  	  
  	  return $default;
  	} // getFieldValue
  	
  	/**
  	* Set specific field value
  	* 
  	* Set value of specific field. Value will be set only if it is different than the one we already have. This method 
  	* is also responsible for marking a field as modfied and taking care that updated primary key values get remembered 
  	* for further reference
  	* 
  	* If field $field_name does not exists invalid param exception will be thrown
  	*
  	* @param string $field_name
  	* @param mixed $value
  	* @return boolean
  	* @throws Angie_Core_Error_InvalidParamValue
  	*/
  	protected function setFieldValue($field_name, $value) {
  		if(!$this->fieldExists($field_name)) {
  		  throw new Angie_Core_Error_InvalidParamValue('field_name', $field_name, "Field '$field_name' does not exists in this object type");
  		} // if
  		
		  $old_value = $this->getFieldValue($field_name);
  		if($this->isNew() || ($old_value <> $value)) {
  		  $this->field_values[$field_name] = $value;
  		  $this->addModifiedField($field_name);
  		  
  		  // Save primary key value. Also make sure that only the first PK value is
  			// saved as old. Not to save second value on third modification ;)
  		  if($this->isPrimaryKeyField($field_name) && !isset($this->updated_pks[$field_name])) {
  		    $this->updated_pks[$field_name] = $old_value;
  		  } // if
  		} // if
  		
  		return $value;
  	} // setFieldValue
  	
  	// -------------------------------------------------------------
  	//  Top level manipulation methods
  	// -------------------------------------------------------------
  	
  	/**
  	* Save object into database (insert or update)
  	*
  	* @param void
  	* @return boolean
  	* @throws Angie_DBA_Error_Validation
  	*/
  	function save() {
  	  $errors = $this->doValidate();
  	  if(is_array($errors)) {
  	    throw new Angie_DBA_Error_Validation($this, $errors);
  	  } // if
  	  return $this->doSave();
  	} // save
  	
  	/**
  	* Delete specific object (and related objects if neccecery)
  	*
  	* @param void
  	* @return boolean
  	* @throws Angie_DB_Error_Query
  	*/
  	function delete() {
  		if($this->isNew() || $this->isDeleted()) {
  		  return false;
  		} // if
  		
  		$this->doDelete();
  		$this->setIsDeleted(true);
  		$this->setIsLoaded(false);
  		
  		return true;
  	} // delete
  	
  	/**
  	* Load data from database row
  	*
  	* @access public
  	* @param array $row Database row
  	* @return boolean
  	*/
  	function loadFromRow($row) {
  	  if(is_array($row)) {
  	    foreach ($row as $k => $v) {
  	      $this->setFieldValue($k, $v);
  	    } // foreach
  	    
  	    $this->setIsLoaded(true);
  	    $this->notModified();
  	    
  	    return true;
  	  } // if
  	  return false;
  	} // loadFromRow
  	
  	/**
  	* This function will call validate() method and handle errors
  	*
  	* @param void
  	* @return array or NULL if there are no errors
  	*/
  	private function doValidate() {
  	  $errors = array();
  	  $this->validate($errors);
  	  
  	  return count($errors) ? $errors : null;
  	} // doValidate
  	
  	/**
  	* Save data into database
  	*
  	* @param void
  	* @return integer or false
  	*/
  	private function doSave() {
  		if($this->isNew()) {
  		  $autoincrement_field = $this->auto_increment_field;
  		  $autoincrement_field_modified = $this->isModifiedField($autoincrement_field);
  			
  		  $insert_id = Angie_DB::getConnection()->execute($this->getInsertQuery());
  		  if($insert_id === false) {
  		    return false;
  		  } // if
  		  
				if(!$autoincrement_field_modified && $this->fieldExists($autoincrement_field)) {
				  $this->setFieldValue($autoincrement_field, $insert_id);
				} // if
  		} else {
  		  $sql = $this->getUpdateQuery();
  		  if(is_null($sql)) {
  		    return true; // nothing to update
  		  } // if
  		  
  		  $affected_rows = Angie_DB::getConnection()->execute($sql);
  		} // if
  		
  		$this->notModified(); // saved!
  		$this->setIsLoaded(true);
  		
  		return isset($insert_id) ? $insert_id : true; // if insert return last insert ID, else return true
  	} // doSave
  	
  	/**
  	* Delete object row from database
  	*
  	* @param void
  	* @return boolean
  	* @throws Angie_DB_Error_Query
  	*/
  	private function doDelete() {
  	  return (boolean) Angie_DB::getConnection()->execute("DELETE FROM " . $this->getTableName(true, true) . " WHERE " . $this->getConditionsById($this->getInitialPkValue()));
  	} // doDelete
  	
  	/**
  	* Prepare insert query
  	*
  	* @param void
  	* @return string
  	*/
  	private function getInsertQuery() {
  		$fields = array();
  		$values = array();
  		
  		$db_connection = Angie_DB::getConnection();
  		
  		foreach($this->modified_fields as $field) {
			  $fields[] = $db_connection->escapeFieldName($field);
			  $values[] = $db_connection->escape($this->getFieldValue($field));
  		} // foreach
  		
  		$sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", 
  		  $this->getTableName(true, true), 
  		  implode(', ', $fields), 
  		  implode(', ', $values)
  		); // sprintf
  		return $sql;
  	} // getInsertQuery
  	
  	/**
  	* Prepare update query
  	*
  	* @param void
  	* @return string
  	*/
  	private function getUpdateQuery() {
  		$fields = array();
  		
  		if(!$this->isModified()) {
  		  return null;
  		} // if
  		
  		$db_connection = Angie_DB::getConnection();
  		
  		foreach($this->modified_fields as $field) {
  			$fields[] = sprintf('%s = %s', $db_connection->escapeFieldName($field), $db_connection->escape($this->getFieldValue($field)));
  		} // foreach
  		
  		$sql = sprintf("UPDATE %s SET %s WHERE %s", 
  		  $this->getTableName(true, true), 
  		  implode(', ', $fields), 
  		  $this->getConditionsById($this->getInitialPkValue())
  		); // sprintf
  		return $sql;
  	} // getUpdateQuery
  	
  	/**
  	* Load details
  	* 
  	* Load values of detail fields and set them. If there is a value for specific field already set that value will be 
  	* skipped
  	*
  	* @param void
  	* @return null
  	*/
  	protected function loadDetails() {
  	  if($this->isNew() || $this->detailsLoaded()) {
  	    return;
  	  } // if
  	  
  	  if(count($this->detail_fields) < 1) {
  	    $this->setDetailsLoaded(true);
  	    return;
  	  } // if
  	  
  	  $sql = sprintf("SELECT %s FROM %s WHERE %s",
  	    implode(', ', $this->detail_fields), 
  	    $this->getTableName(true, true), 
  	    $this->getConditionsById($this->getInitialPkValue())
  	  ); // sprintf
  	  
  	  $row = Angie_DB::getConnection()->executeOne($sql);
  	  
  	  if(is_array($row)) {
  	    foreach($row as $k => $v) {
  	      if(!isset($this->field_values[$k])) {
  	        $this->field_values[$k] = $v;
  	      } // if
  	    } // foreach
  	  } // if
  	  
  	  $this->setDetailsLoaded(true);
  	} // loadDetails
  	
  	/**
  	* Return conditions part of the query based on ID
  	*
  	* @param mixed $id
  	* @return string
  	*/
  	private function getConditionsById($id) {
  	  $db_connection = Angie_DB::getConnection();
  	  
  	  if(count($this->primary_key) == 1) {
  	    return $db_connection->prepareString($this->primary_key[0] . ' = ?', $id);
  	  } else {
  	    $conditions = array();
  	    foreach($this->primary_key as $pk) {
  	      $conditions[] = $db_connection->escapeFieldName($pk) . ' = ' . $db_connection->escape(array_var($id, $pk));
  	    } // if
  	    return implode(' AND ', $conditions);
  	  } // if
  	} // getConditionsById
  	
  	// ---------------------------------------------------
  	//  Getters and setters, indicators
  	// ---------------------------------------------------
  	
  	/**
  	* Return array of fields that form primary key
  	*
  	* @param void
  	* @return array
  	*/
  	function getPrimaryKey() {
  	  return $this->primary_key;
  	} // getPrimaryKey
  	
  	/**
  	* Return array of object fields
  	*
  	* @param void
  	* @return array
  	*/
  	function getFields() {
  	  return $this->fields;
  	} // getFields
  	
  	/**
  	* Return array of detail fields
  	*
  	* @param void
  	* @return array
  	*/
  	function getDetailFields() {
  	  return $this->detail_fields;
  	} // getDetailFields
  	
  	/**
  	* Return table name
  	* 
  	* This function will return table name. If $prefixed is true script will read table prefix from configuration and 
  	* return table name with that prefix
  	* 
  	* If $escaped is true default database connection will be used to escape the table name for use in queries
  	*
  	* @param boolean $prefixed
  	* @param boolean $escaped
  	* @return string
  	*/
  	function getTableName($prefixed = false, $escaped = false) {
  	  static $prefix = null;
  	  if($prefixed && is_null($prefix)) {
  	    $prefix = Angie::getConfig('db.table_prefix');
  	  } // if
  	  
  	  $result_name = $prefixed ? trim($prefix) . $this->table_name : $this->table_name;
  	  if($escaped) {
  	    return Angie_DB::getConnection()->escapeTableName($result_name);
  	  } else {
  	    return $result_name;
  	  } // if
  	} // getTableName
  	
  	/**
  	* Return name of manager class
  	*
  	* @param void
  	* @return string
  	*/
  	function getManagerClass() {
  	  return $this->manager_class;
  	} // getManagerClass
  	
  	/**
  	* Name of auto increment field (only one per object supported)
  	*
  	* @param void
  	* @return string
  	*/
  	function getAutoIncrementField() {
  	  return $this->auto_increment_field;
  	} // getAutoIncrementField
  	
  	/**
  	* Return value of $is_new variable
  	*
  	* @param void
  	* @return boolean
  	*/
  	function isNew() {
  	  return (boolean) $this->is_new;
  	} // isNew
  	
  	/**
  	* Set new stamp value
  	*
  	* @param boolean $value
  	* @return void
  	*/
  	function setIsNew($value) {
  	  $this->is_new = (boolean) $value;
  	} // setIsNew
  	
  	/**
  	* Return value of $is_loaded variable
  	*
  	* @param void
  	* @return boolean
  	*/
  	function isLoaded() {
  	  return (boolean) $this->is_loaded;
  	} // isLoaded
  	
  	/**
  	* Set loaded stamp value
  	*
  	* @param boolean $value
  	* @return void
  	*/
  	function setIsLoaded($value) {
  	  $this->is_loaded = (boolean) $value;
  	  $this->setIsNew(!$this->is_loaded);
  	} // setIsLoaded
  	
  	/**
  	* Returns true if this object has modified fields
  	*
  	* @param void
  	* @return boolean
  	*/
  	function isModified() {
  	  return is_array($this->modified_fields) && (boolean) count($this->modified_fields);
  	} // isModified
  	
  	/**
  	* Return value of $is_deleted variable
  	*
  	* @param void
  	* @return boolean
  	*/
  	function isDeleted() {
  	  return (boolean) $this->is_deleted;
  	} // isDeleted
  	
  	/**
  	* Set deleted stamp value
  	*
  	* @param boolean $value New value
  	* @return void
  	*/
  	function setIsDeleted($value) {
  	  $this->is_deleted = (boolean) $value;
  	} // setIsDeleted
  	
  	/**
  	* Returns true if details for this field are loaded
  	*
  	* @param void
  	* @return boolean
  	*/
  	private function detailsLoaded() {
  	  return $this->details_loaded;
  	} // detailsLoaded
  	
  	/**
  	* Set details loaded flag value
  	*
  	* @param boolean $value
  	* @return null
  	*/
  	private function setDetailsLoaded($value) {
  	  $this->details_loaded = (boolean) $value;
  	} // setDetailsLoaded
  	
  	/**
  	* Reset modification idicators
  	* 
  	* Usefull when you use setXXX functions but you don't want to modify anything (just loading data from database in 
  	* fresh object using setFieldValue function)
  	*
  	* @param void
  	* @return void
  	*/
  	function notModified() {
  	  $this->modified_fields = array();
  	  $this->updated_pks = array();
  	} // notModified
  	
  	// ---------------------------------------------------------------
  	//  Validators
  	// ---------------------------------------------------------------
  	
  	/**
  	* Validates presence of specific field
  	* 
  	* Presence of value is determined by the empty function. If value is string $trim_string determins if value will be 
  	* trimmed before check.
  	* 
  	* From PHP manual - The following things are considered to be empty:
  	* 
  	* - "" (an empty string)
  	* - 0 (0 as an integer)
  	* - "0" (0 as a string)
  	* - NULL
  	* - FALSE
  	* - array() (an empty array)
  	* - var $var; (a variable declared, but without a value in a class)
  	*
  	* @param string $field Field name
  	* @param boolean $trim_string
  	* @return boolean
  	*/
  	function validatePresenceOf($field, $trim_string = true) {
  	  $value = $this->getFieldValue($field);
  	  if(is_string($value) && $trim_string) {
  	    $value = trim($value);
  	  } // if
  	  return !empty($value);
  	} // validatePresenceOf
  	
  	/**
  	* This validator will return true if $value is unique (there is no row with such value in that field)
  	*
  	* @access public
  	* @param string $field Filed name
  	* @param mixed $value Value that need to be checked
  	* @return boolean
  	*/
  	function validateUniquenessOf() {
  	  
  	  // Don't do COUNT(*) if we have one PK field
  	  $pk_fields = $this->getPrimaryKey();
  	  $escaped_pk = count($pk_fields) ? '*' : $pk_fields[0];
  	  
  	  $fields = func_get_args();
  	  if(!is_array($fields) || count($fields) < 1) {
  	    return true;
  	  } // if
  	  
  	  $db_connection = Angie_DB::getConnection();
  	  
  	  $where_parts = array();
  	  foreach($fields as $field) {
  	    if(!$this->fieldExists($field)) {
  	      return false;
  	    } // if
  	    
  	    $where_parts[] = $db_connection->escapeFieldName($field) . ' = ' . $db_connection->escape($this->getFieldValue($field));
  	  } // foreach
  	  
  	  // If we have new object we need to test if there is any other object with this value. Else we need to check if 
  	  // there is any other EXCEPT this one with that value
  	  if($this->isNew()) {
  	    $sql = sprintf("SELECT COUNT($escaped_pk) AS 'row_count' FROM %s WHERE %s", 
  	      $this->getTableName(true, true), 
  	      implode(' AND ', $where_parts)
  	    ); // sprintf
  	  } else {
  	    $pk_values = array();
	      foreach($pk_fields as $pk_field) {
	        $pk_values[] = $db_connection->escapeFieldName($pk_field) . ' <> ' . $db_connection->escape($this->getFieldValue($pk));
	      } // foreach

  	    $sql = sprintf("SELECT COUNT($escaped_pk) AS 'row_count' FROM %s WHERE (%s) AND (%s)", 
  	      $this->getTableName(true, true), 
  	      implode(' AND ', $where_parts), 
  	      implode(' AND ', $pk_values)
  	    ); // sprintf
  	  } // if
  	  
  	  $row = $db_connection->executeOne($sql);
  	  return array_var($row, 'row_count', 0) < 1;
  	} // validateUniquenessOf
  	
  	/**
  	* Validate max value of specific field
  	* 
  	* If that field is string time max lenght will be validated. In case of datetime field $max_value needs to be a 
  	* timestamp or Angie_DateTime object agains witch timestamp field value will be matched
  	*
  	* @param string $field_name
  	* @param integer $max
  	* @return null
  	*/
  	function validateMaxValueOf($field_name, $max) {
  	  if(!$this->fieldExists($field_name)) {
  	    return false;
  	  } // if
  	  
  	  $value = $this->getFieldValue($field_name);
  	  if(is_string($value)) {
  	    if(function_exists('mb_strlen')) {
  	      return mb_strlen($value) <= $max;
  	    } else {
  	      return strlen($value) <= $max;
  	    } // if
  	  } elseif($value instanceof Angie_DateTime) {
  	    return $max instanceof Angie_DateTime ? 
  	      $value->getTimestamp() <= $max->getTimestamp() : 
  	      $value->getTimestamp() <= $max;
  	  } else {
  	    return $value <= $max;
  	  } // if
  	} // validateMaxValueOf
  	
  	/**
  	* Valicate minimal value of specific field. If string minimal lenght is checked
  	*
  	* @access public
  	* @param string $
  	* @param integer $min Minimal value
  	* @return boolean
  	*/
  	function validateMinValueOf($field_name, $min) {
  	  if(!$this->fieldExists($field_name)) {
  	    return false;
  	  } // if
  	  
  	  $value = $this->getFieldValue($field_name);
  	  if(is_string($value)) {
  	    if(function_exists('mb_strlen')) {
  	      return mb_strlen($value) >= $min;
  	    } else {
  	      return strlen($value) >= $min;
  	    } // if
  	  } elseif($value instanceof Angie_DateTime) {
  	    return $min instanceof Angie_DateTime ? 
  	      $value->getTimestamp() >= $min->getTimestamp() : 
  	      $value->getTimestamp() >= $min;
  	  } else {
  	    return $value >= $min;
  	  } // if
  	} // validateMinValueOf
  	
  	/**
  	* This function will validate format of specified field value
  	* 
  	* Function used to match pattern is preg_match()
  	*
  	* @param string $field_name
  	* @param string $pattern
  	* @return boolean
  	*/
  	function validateFormatOf($field_name, $pattern) {
  	  if(!$this->fieldExists($field_name)) {
  	    return false;
  	  } // if
  	  
  	  return (boolean) preg_match($pattern, $this->getFieldValue($field_name));
  	} // validateFormatOf
  	
  	// ---------------------------------------------------
  	//  System
  	// ---------------------------------------------------
  	
  	/**
  	* Go to sleep (on serialization)
  	*
  	* @param void
  	* @return null
  	*/
  	function __sleep() {
  	  $this->cache = array(); // reset cache
  	} // __sleep
    
  } // Angie_DBA_Object

?>