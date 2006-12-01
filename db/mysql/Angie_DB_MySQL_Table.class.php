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
    
    // ---------------------------------------------------
    //  Utils
    // ---------------------------------------------------
    
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
  
  } // Angie_DB_MySQL_Table

?>