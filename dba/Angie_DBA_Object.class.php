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
    * Array of primary key columns
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
  	* Array of updated primary key columns with cached old values (used in WHERE on update 
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
  	* Set object attributes / properties. This function will take hash and set 
  	* value of all fields that she finds in the hash
  	*
  	* @access public
  	* @param array $attributes
  	* @return null
  	*/
  	function setFromAttributes($attributes) {
  	  if(is_array($attributes)) {
  	    foreach($attributes as $k => &$v) {
  	      if(is_array($this->attr_protected) && in_array($k, $this->attr_protected)) {
  	        continue; // protected attribute
  	      } // if
  	      if(is_array($this->attr_acceptable) && !in_array($k, $this->attr_acceptable)) {
  	        continue; // not acceptable
  	      } // if
  	      if($this->columnExists($k)) {
  	        $this->setFieldValue($k, $attributes[$k]); // column exists, set
  	      } // if
  	    } // foreach
  	  } // if
  	} // setFromAttributes
  	
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
  	* Report modified column
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
  	} // isModifiedPrimaryKeyColumn
  	
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
  	} // isAutoIncrementColumn
  	
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
  	* @param string $field_name
  	* @param mixed $value
  	* @return boolean
  	*/
  	protected function setFieldValue($field_name, $value) {
  		if(!$this->fieldExists($field_name)) {
  		  return false;
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
  		
  		return true;
  	} // setColumnValue
  	
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
		  return true;
  	} // doSave
  	
  	/**
  	* Delete object row from database
  	*
  	* @param void
  	* @return boolean
  	* @throws Angie_DB_Error_Query
  	*/
  	private function doDelete() {
  	  return (boolean) Angie_DB::getConnection()->execute("DELETE FROM " . $this->getTableName(true) . " WHERE " . $this->getConditionsById($this->getInitialPkValue()));
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
  		
  		foreach($this->modified_fields as $field) {
			  $fields[] = $field;
			  $values[] = Angie_DB::getConnection()->escape($this->getFieldValue($field));
  		} // foreach
  		
  		$sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", 
  		  $this->getTableName(true), 
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
  		
  		foreach($this->modified_fields as $field) {
  			$fields[] = sprintf('%s = %s', $field, Angie_DB::getConnection()->escape($this->getFieldValue($field)));
  		} // foreach
  		
  		$sql = sprintf("UPDATE %s SET %s WHERE %s", 
  		  $this->getTableName(true), 
  		  implode(', ', $fields), 
  		  $this->getConditionsById($this->getInitialPkValue())
  		); // sprintf
  		return $sql;
  	} // getUpdateQuery
  	
  	/**
  	* Load details
  	* 
  	* Load values of detail columns and set them. If there is a value for specific field already set that value will be 
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
  	    $this->getTableName(true), 
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
  	  if(count($this->primary_key) == 1) {
  	    return Angie_DB::getConnection()->prepareString($this->primary_key[0] . ' = ?', $id);
  	  } else {
  	    $conditions = array();
  	    foreach($this->primary_key as $pk) {
  	      $conditions[] = Angie_DB::getConnection()->prepareString($pk . ' = ?', array(array_var($id, $pk)));
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
  	* @param boolean $prefixed
  	* @return string
  	*/
  	function getTableName($prefixed = false) {
  	  static $prefix = null;
  	  if($prefixed && is_null($prefix)) {
  	    $prefix = Angie::getConfig('db.table_prefix');
  	  } // if
  	  
  	  return $prefixed ? trim($prefix) . $this->table_name : $this->table_name;
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
  	* Returns true if this object has modified columns
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
  	* fresh object using setColumnValue function)
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
  	* Validates presence of specific field. Presence of value is determined 
  	* by the empty function
  	*
  	* @access public
  	* @param string $field Field name
  	* @param boolean $trim_string If value is string trim it before checks to avoid
  	*   returning true for strings like ' '.
  	* @return boolean
  	*/
  	function validatePresenceOf($field, $trim_string = true) {
  	  $value = $this->getColumnValue($field);
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
  	  // Don't do COUNT(*) if we have one PK column
      $escaped_pk = is_array($pk_columns = $this->getPkColumns()) ? '*' : DB::escapeField($pk_columns);
  	  
  	  // Get columns
  	  $columns = func_get_args();
  	  if(!is_array($columns) || count($columns) < 1) return true;
  	  
  	  // Check if we have existsing columns
  	  foreach($columns as $column) {
  	    if(!$this->columnExists($column)) return false;
  	  } // foreach
  	  
  	  // Get where parets
  	  $where_parts = array();
  	  foreach($columns as $column) {
  	    $where_parts[] = DB::escapeField($column) . ' = ' . DB::escape($this->getColumnValue($column));
  	  } // if
  	  
  	  // If we have new object we need to test if there is any other object
  	  // with this value. Else we need to check if there is any other EXCEPT
  	  // this one with that value
  	  if($this->isNew()) {
  	    $sql = sprintf("SELECT COUNT($escaped_pk) AS 'row_count' FROM %s WHERE %s", 
  	      $this->getTableName(true), 
  	      implode(' AND ', $where_parts)
  	    ); // sprintf
  	  } else {
  	    
  	    // Prepare PKs part...
  	    $pks = $this->getPkColumns();
  	    $pk_values = array();
  	    if(is_array($pks)) {
  	      foreach($pks as $pk) {
  	        $pk_values[] = sprintf('%s <> %s', DB::escapeField($pk), DB::escape($this->getColumnValue($pk)));
  	      } // foreach
  	    } else {
  	      $pk_values[] = sprintf('%s <> %s', DB::escapeField($pks), DB::escape($this->getColumnValue($pks)));
  	    } // if

  	    // Prepare SQL
  	    $sql = sprintf("SELECT COUNT($escaped_pk) AS 'row_count' FROM %s WHERE (%s) AND (%s)", $this->getTableName(true), implode(' AND ', $where_parts), implode(' AND ', $pk_values));
  	    
  	  } // if
  	  
  	  $row = DB::executeOne($sql);
  	  return array_var($row, 'row_count', 0) < 1;
  	} // validateUniquenessOf
  	
  	/**
  	* Validate max value of specific field. If that field is string time 
  	* max lenght will be validated
  	*
  	* @access public
  	* @param string $column
  	* @param integer $max Maximal value
  	* @return null
  	*/
  	function validateMaxValueOf($column, $max) {
  	  
  	  // Field does not exists
  	  if(!$this->columnExists($column)) return false;
  	  
  	  // Get value...
  	  $value = $this->getColumnValue($column);
  	  
  	  // Integer and float...
  	  if(is_int($value) || is_float($column)) {
  	    return $column <= $max;
  	    
  	  // String...
  	  } elseif(is_string($value)) {
  	    return strlen($value) <= $max;
  	    
  	  // Any other value...
  	  } else {
  	    return $column <= $max;
  	  } // if
  	  
  	} // validateMaxValueOf
  	
  	/**
  	* Valicate minimal value of specific field. If string minimal lenght is checked
  	*
  	* @access public
  	* @param string $column
  	* @param integer $min Minimal value
  	* @return boolean
  	*/
  	function validateMinValueOf($column, $min) {
  	  
  	  // Field does not exists
  	  if(!$this->columnExists($column)) return false;
  	  
  	  // Get value...
  	  $value = $this->getColumnValue($column);
  	  
  	  // Integer and float...
  	  if(is_int($value) || is_float($value)) {
  	    return $column >= $min;
  	    
  	  // String...
  	  } elseif(is_string($value)) {
  	    return strlen($value) >= $min;
  	    
  	  // Any other value...
  	  } else {
  	    return $column >= $min;
  	  } // if
  	  
  	} // validateMinValueOf
  	
  	/**
  	* This function will validate format of specified columns value
  	*
  	* @access public
  	* @param string $column Column name
  	* @param string $pattern
  	* @return boolean
  	*/
  	function validateFormatOf($column, $pattern) {
  	  if(!$this->columnExists($column)) return false;
  	  $value = $this->getColumnValue($column);
  	  return preg_match($pattern, $value);
  	} // validateFormatOf
    
  } // Angie_DBA_Object

?>