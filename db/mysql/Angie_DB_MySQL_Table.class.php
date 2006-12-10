<?php

  /**
  * MySQL table definition
  * 
  * This class extends basic table class with a specific MySQL properties 
  * (engine, default charset, collation etc)
  *
  * @package Angie.DB
  * @subpackage mysql
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DB_MySQL_Table extends Angie_DB_Table {
    
    /**
    * MySQL storage engine
    *
    * @var string
    */
    private $engine = null;
    
    /**
    * Default table collation
    *
    * @var string
    */
    private $collation = null;
    
    /**
    * This value is true if this table can use Memory engine
    * 
    * When table contains TEXT or BLOB fields it can't be used with memory 
    * engine.
    *
    * @var boolean
    */
    private $can_use_memory = true;
    
    // ---------------------------------------------------
    //  Utils
    // ---------------------------------------------------
    
    /**
    * Create this table
    *
    * @param Angie_DB_MySQL_Connection $connection
    * @return null
    */
    function buildTable(Angie_DB_MySQL_Connection $connection) {
      $escaped_table_name = $connection->escapeTableName($this->getPrefixedName());
      
      $mysql_version = mysql_get_client_info();
      
      $engine = trim($this->getEngine());
      if(!$engine) {
        $engine = Angie::getConfig('mysql.default_engine', 'MyISAM');
      } // if
      
      $engine = "ENGINE=$engine";
      $default_charset = '';
      $default_collation = '';
      
      if(version_compare($mysql_version, '4.1') >= 0) {
        $default_charset = Angie::getConfig('mysql.default_charset');
        if($default_charset) {
          $default_charset = " DEFAULT CHARSET=$default_charset";
        } // if
        
        $default_collation = Angie::getConfig('mysql.default_collation');
        if($default_collation) {
          $default_collation = "COLLATE=$default_collation";
        } // if
      } // if
      
      $escaped_primary_keys = array();
      foreach($this->getPrimaryKey() as $primary_key) {
        $escaped_primary_keys[] = $connection->escapeFieldName($primary_key);
      } // foreach
      $primary_key = count($escaped_primary_keys) ? ",\nPRIMARY KEY(" . implode(', ', $escaped_primary_keys) . ')' : '';
      
      $fields_code = array();
      
      foreach($this->getFields() as $field) {
        $field_name = $connection->escapeFieldName($field->getName());
        $not_null = $field->getNotNull() ? 'NOT NULL' : 'NULL';
        $default_value = $field->getDefaultValue() ? 'DEFAULT ' . $connection->escape($field->getDefaultValue()) : '';
        
        // Integer
        if($field instanceof Angie_DB_Field_Integer) {
          $unsigned = $field->getUnsigned() ? 'UNSIGNED' : '';
          $auto_increment = $field->getAutoIncrement() ? 'auto_increment' : '';
          $fields_code[] = "$field_name INT $unsigned $not_null $default_value $auto_increment";
          
        // Varchar
        } elseif($field instanceof Angie_DB_Field_String) {
          $lenght = $field->getLenght();
          
          $fields_code[] = "$field_name VARCHAR($lenght) $not_null $default_value";
          
        // Text
        } elseif($field instanceof Angie_DB_Field_Text) {
          $fields_code[] = "$field_name TEXT $not_null $default_value";
          
        // Float
        } elseif($field instanceof Angie_DB_Field_Float) {
          $unsigned = $field->getUnsigned() ? 'UNSIGNED' : '';
          
          if(!is_null($field->getLenght()) && !is_null($field->getPrecission())) {
            $lenght = $field->getLenght();
            $precission = $field->getPrecission();
            $fields_code[] = "$field_name DOUBLE($lenght, $precission) $unsigned $not_null $default_value";
          } else {
            $fields_code[] = "$field_name DOUBLE $not_null $default_value";
          } // if
          
        // Boolean
        } elseif($field instanceof Angie_DB_Field_Boolean) {
          $fields_code[] = "$field_name TINYINT(1) $not_null $default_value";
          
        // Datetime
        } elseif($field instanceof Angie_DB_Field_DateTime) {
          $fields_code[] = "$field_name DATETIME $not_null $default_value";
          
        // Enum
        } elseif($field instanceof Angie_DB_Field_Enum) {
          $escaped_possible_values = array();
          foreach($field->getPossibleValues() as $possible_value) {
            $escaped_possible_values[] = $connection->escape($possible_value);
          } // foreach
          $escaped_possible_values = implode(', ', $escaped_possible_values);
          
          $fields_code[] = "$field_name ENUM($escaped_possible_values) $not_null $default_value";
          
        // Binary
        } elseif($field instanceof Angie_DB_Field_Binary) {
          $fields_code[] = "$field_name BLOB $not_null $default_value";
          
        } else {
          throw new Angie_Core_Error_InvalidParamValue('field', $field, '$field is not supported type by this database engine');
        } // if
      } // foreach
      return $connection->execute("CREATE TABLE $escaped_table_name (\n" . implode($fields_code, ",\n") . "$primary_key\n) $engine $default_charset $default_collation");
    } // buildTable
    
    /**
    * Read field and keys description from database
    * 
    * This function uses given database connection resource to read more data 
    * about this table from the database.
    *
    * @param Angie_DB_Connection $connection
    * @return null
    * @throws Angie_DB_Error_Describe
    */
    function readDescription(Angie_DB_MySQL_Connection $connection) {
      $row = $connection->executeOne("SHOW TABLE STATUS LIKE ?", array($this->getName()));
      
      if(empty($row)) {
        throw new Angie_DB_Error_Describe($table_name);
      } // if
      
      $this->setEngine($row['Engine']);
      $this->setCollation($row['Collation']);
      
      $string_types   = array('varchar');
      $integer_types  = array('bit', 'tinyint', 'smallint', 'int', 'integer', 'mediumint', 'bigint');
      $float_types    = array('float', 'double', 'decimal');
      $text_types     = array('tinytext', 'text', 'mediumtext', 'longtext');
      $datetime_types = array('datetime', 'date', 'time', 'timestamp');
      $binary_types   = array('tinyblob', 'blog', 'mediumblob', 'longblob', 'binary', 'varbinary');
      $enum_types     = array('enum');
      
      $field_rows = $connection->executeAll('SHOW COLUMNS FROM ' . $connection->escapeTableName($this->getName()));
      $pk_fields = array();
      
      if(is_foreachable($field_rows)) {
        foreach($field_rows as $field_row) {
          $field_name = $field_row['Field'];
          
          $default_value = $field_row['Default'];
          if($default_value == 'NULL') {
            $default_value = null;
          } // if
          $not_null = $field_row['Null'] ? false : true;
          
          list($type_string, $type_params, $type_options) = $this->parseTypeString($field_row['Type']);
          
          // String field
          if(in_array($type_string, $string_types)) {
            $field = new Angie_DB_Field_String($field_name, $default_value, $not_null);
            $field->setLenght(array_var($type_params, 0, 100));
            
          // Integer field
          } elseif(in_array($type_string, $integer_types)) {
            if($type_string == 'bit' || (($type_string == 'tinyint') && (array_var($type_params, 0) == 1))) {
              $field = new Angie_DB_Field_Boolean($field_name, $default_value, $not_null);
            } else {
              $field = new Angie_DB_Field_Integer($field_name, $default_value, $not_null);
              if(in_array('unsigned', $type_options)) {
                $field->setUnsigned(true);
              } // if
              if($field_row['Extra'] == 'auto_increment') {
                $field->setAutoIncrement(true);
              } // if
            } // if
            
          // Float field
          } elseif(in_array($type_string, $float_types)) {
            $field = new Angie_DB_Field_Float($field_name, $default_value, $not_null);
            $field->setLenght(array_var($type_params, 0));
            $field->setPrecission(array_var($type_params, 1));
            if(in_array('unsigned', $type_options)) {
              $field->setUnsigned(true);
            } // if
          } elseif(in_array($type_string, $text_types)) {
            $field = new Angie_DB_Field_Text($field_name, $default_value, $not_null);
          } elseif(in_array($type_string, $datetime_types)) {
            $field = new Angie_DB_Field_DateTime($field_name, $default_value, $not_null);
          } elseif(in_array($type_string, $binary_types)) {
            $field = new Angie_DB_Field_Binary($field_name, $default_value, $not_null);
          } elseif(in_array($type_string, $enum_types)) {
            $possible_values = array();
            foreach($type_params as $type_param) {
              $possible_values[] = stripslashes(trim($type_param, "'"));
            } // foreach
            
            $field = new Angie_DB_Field_Enum($field_name, $default_value, $not_null);
            $field->setPossibleValues($possible_values);
          } else {
            throw new Angie_DB_Error_Describe($this->getName(), "Type '$type_string' is not supported");
          } // if
          
          $this->addField($field);
          
          if($field_row['Key'] == 'PRI') {
            $this->addPrimaryKey($field_name);
          } // if
        } // foreach
      } // if
    } // readDescription
    
    /**
    * Parse type string returned from the database
    * 
    * MySQL returns a type string contining details about the type and specific 
    * options (lenght, value of unsigned flag, enumerable values and so on). 
    * This function extract that data from a given string and returns an 
    * associtive array where:
    * 
    * - type string   - name of the type
    * - type params   - array if params extracted from ()
    * - type options  - array of elements that are extract from the rest of the 
    *                   type strings
    *
    * @param string $type
    * @return array
    */
    function parseTypeString($type) {
      $elements = explode(' ', strtolower($type));
      
      $type_string = '';
      $type_params = array();
      $type_options = array();
      
      $iteration = 0;
      foreach($elements as $element) {
        $iteration++;
        if($iteration == 1) {
          $type_string = $element;
        } else {
          $type_options[] = $element;
        } // if
      } // foreach
      
      if(($pos = strpos($type_string, '(')) !== false) {
        $type_params = explode(',', substr($type_string, $pos + 1, strpos($type_string, ')') - $pos - 1));
        $type_string = substr($type_string, 0, $pos);
      } // if
      
      return array($type_string, $type_params, $type_options);
    } // parseTypeString
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get engine
    *
    * @param null
    * @return string
    */
    function getEngine() {
      return $this->engine;
    } // getEngine
    
    /**
    * Set engine value
    *
    * @param string $value
    * @return null
    */
    function setEngine($value) {
      $this->engine = $value;
    } // setEngine
    
    /**
    * Get collation
    *
    * @param null
    * @return string
    */
    function getCollation() {
      return $this->collation;
    } // getCollation
    
    /**
    * Set collation value
    *
    * @param string $value
    * @return null
    */
    function setCollation($value) {
      $this->collation = $value;
    } // setCollation
    
    /**
    * Returns true if this table can use memory engine (does not have any TEXT 
    * or BLOB fields)
    *
    * @param void
    * @return boolean
    */
    function getCanUseMemory() {
      return $this->can_use_memory;
    } // getCanUseMemory
    
    /**
    * Add a single field to the table
    *
    * @param Angie_DB_Field $field
    * @return Angie_DB_Field
    */
    function addField(Angie_DB_Field $field) {
      if($this->can_use_memory && (($field instanceof Angie_DB_Field_Text) || ($field instanceof Angie_DB_Field_Binary))) {
        $this->can_use_memory = false;
      } // if
      parent::addField($field);
    } // addField
  
  } // Angie_DB_MySQL_Table

?>