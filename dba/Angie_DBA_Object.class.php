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
    * All fields except detail fields
    *
    * @var array
    */
    protected $fields_without_details;
    
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
  	* Report modified column
  	*
  	* @param string $field_name
  	* @return null
  	*/
  	protected function addModifiedField($field_name) {
  	  if(!in_array($field_name, $this->modified_fields)) {
  	    $this->field_values[] = $field_name;
  	  } // if
  	} // addModifiedField
  	
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
  	function getFieldValue($field_name, $default = null) {
  	  if(isset($this->field_values[$column_name])) {
  	    return $this->field_values[$column_name];
  	  } // if
  	  
//  	  if(!$this->fieldExists($field_name) && $this->isLazyLoadColumn($column_name)) {
//    	  return $this->loadLazyLoadColumnValue($column_name, $default);
//  	  } // if
  	  
  	  return $default;
  	} // getFieldValue
  	
  	/**
  	* Set specific field value
  	*
  	* @param string $field_name
  	* @param mixed $value
  	* @return boolean
  	*/
  	function setFieldValue($field_name, $value) {
  		if(!$this->fieldExists($field_name)) {
  		  return false;
  		} // if
  		
  		$coverted_value = $this->rawToPHP($field_name, $value);
  		$old_value = $this->getFieldValue($field_name);
  		
  		if($this->isNew() || ($old_value <> $coverted_value)) {
  		  $this->field_value[$column] = $coverted_value;
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
  		
  		if($this->doDelete()) {
  		  $this->setDeleted(true);
  		  $this->setLoaded(false);
  		  
  		  return true;
  		} else {
  		  return false;
  		} // if
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
  	      if($this->columnExists($k)) {
  	        $this->setColumnValue($k, $v);
  	      } // if
  	    } // foreach
  	    
  	    $this->setLoaded(true);
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
  		  $autoincrement_field_modified = $this->fieldExists($autoincrement_field) && $this->isModifiedField($autoincrement_field);
  			
  		  if(!Angie_DB::getConnection()->execute($this->getInsertQuery())) {
  		    return false;
  		  } // if
  		  
				if(!$autoincrement_field_modified && $this->fieldExists($autoincrement_field)) {
				  $this->setFieldValue($autoincrement_field, Angie_DB::getConnection()->lastInsertId());
				} // if
				
				$this->setLoaded(true);
			  return true;
  		
  		} else {
  		  $sql = $this->getUpdateQuery();
  		  if(is_null($sql)) {
  		    return true; // nothing to update
  		  } // if
  		  
  		  if(!Angie_DB::getConnection()->execute($sql)) {
  		    return false;
  		  } // if
		    $this->setLoaded(true);
		    return true;
  		} // if
  	} // doSave
  	
  	/**
  	* Delete object row from database
  	*
  	* @param void
  	* @return boolean
  	* @throws Angie_DB_Error_Query
  	*/
  	private function doDelete() {
  	  return Angie_DB::getConnection()->execute("DELETE FROM " . $this->getTableName() . " WHERE " . $this->getConditionsById( $this->getInitialPkValue() ));
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
  		
  		foreach($this->fields as $field) {
  		  if(!$this->isAutoIncrementField($field) || $this->isModifiedField($field)) {
				  $fields[] = $field;
				  $values[] = Angie_DB::getConnection()->escape(
				    $this->phpToRaw($field, $this->getFieldValue($field))
				  ); // escape
  		  } // if
  		} // foreach
  		
  		return sprintf("INSERT INTO %s (%s) VALUES (%s)", 
  		  $this->getTableName(true), 
  		  implode(', ', $fields), 
  		  implode(', ', $values)
  		); // sprintf
  	} // getInsertQuery
  	
  	/**
  	* Prepare update query
  	*
  	* @param void
  	* @return string
  	*/
  	private function getUpdateQuery() {
  		$columns = array();
  		
  		if(!$this->isObjectModified()) {
  		  return null;
  		} // if
  		
  		foreach ($this->fields as $field) {
  			if($this->isModifiedField($field)) {
  			  $columns[] = sprintf('%s = %s', $field, Angie_DB::getConnection()->escape(
  			    $this->phpToRaw($field, $this->getFieldValue($field))
  			  )); // escape
  			} // if
  		} // foreach
  		
  		return sprintf("UPDATE %s SET %s WHERE %s", $this->getTableName(), implode(', ', $field), $this->getConditionsById($this->getInitialPkValue()));
  	} // getUpdateQuery
  	
  	/**
  	* Return conditions part of the query based on ID
  	*
  	* @param mixed $id
  	* @return string
  	*/
  	function getConditionsById($id) {
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
  	//  Cast methods
  	// ---------------------------------------------------
  	
  	function rawToPHP($field_name, $value) {
  	  return $value;
  	} // rawToPHP
  	
  	function phpToRaw($field_name, $value) {
  	  return $value;
  	} // phpToRaw
  	
  	// ---------------------------------------------------
  	//  Getters and setters
  	// ---------------------------------------------------
  	
  	/**
  	* Return table name
  	*
  	* @param void
  	* @return string
  	*/
  	function getTableName() {
  	  return $this->table_name;
  	} // getTableName
  	
  	// ---------------------------------------------------------------
  	//  Flags
  	// ---------------------------------------------------------------
  	
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
  	function setNew($value) {
  	  $this->is_new = (boolean) $value;
  	} // setNew
  	
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
  	function setDeleted($value) {
  	  $this->is_deleted = (boolean) $value;
  	} // setDeleted
  	
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
  	function setLoaded($value) {
  	  $this->is_loaded = (boolean) $value;
  	  $this->setNew(!$this->is_loaded);
  	} // setLoaded
  	
  	/**
  	* Check if this object is modified (one or more column value are modified)
  	*
  	* @param void
  	* @return boolean
  	*/
  	function isObjectModified() {
  	  return (boolean) count($this->modified_fields);
  	} // isObjectModified
  	
  	/**
  	* Returns true if PK column value is updated
  	*
  	* @param void
  	* @return boolean
  	*/
  	function isPkUpdated() {
  	  return count($this->updated_pks);
  	} // isPkUpdated
  	
  	/**
  	* Reset modification idicators. Usefull when you use setXXX functions
  	* but you don't want to modify anything (just loading data from database
  	* in fresh object using setColumnValue function)
  	*
  	* @param void
  	* @return void
  	*/
  	function notModified() {
  	  $this->modified_columns = array();
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
  	  if(is_string($value) && $trim_string) $value = trim($value);
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
  	    $sql = sprintf("SELECT COUNT($escaped_pk) AS 'row_count' FROM %s WHERE %s", $this->getTableName(true), implode(' AND ', $where_parts));
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