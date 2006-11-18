<?php

  /**
  * Simple MySQL connection
  * 
  * Connection object that implements Angie DB interface to use native mysql interaction functions. This implementation 
  * is light, but still provides full implementaiton of database interface
  *
  * @package Angie.DB
  * @subpackage mysql
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DB_MySQL_Connection implements Angie_DB_Connection {
    
    /**
    * Name of the database host
    *
    * @var string
    */
    private $hostname;
    
    /**
    * Connection username
    *
    * @var string
    */
    private $username;
    
    /**
    * Connection password
    *
    * @var string
    */
    private $password;
    
    /**
    * Name of the selected database
    *
    * @var string
    */
    private $database_name;
    
    /**
    * Link resource
    *
    * @var resource
    */
    private $link;
    
    /**
    * Construct connection
    * 
    * If $params value is present (not NULL) constructor will also try to connect to database
    *
    * @param array $params
    * @return Angie_DB_MySQL_Connection
    */
    function __construct($params = null) {
      if(!is_null($params)) {
        $this->connect($params);
      } // if
    } // __construct
  
    /**
    * Connect to the database
    * 
    * This function will use input $params and try to connect to the database. Params are array and can be interpreted 
    * differently by different connection implementations. Common set of params:
    * 
    * - hostname - name of database host, usually localhost
    * - username - connection username
    * - password - connection password
    * - name - name of the database we'd like to use
    * - persist - open a persistant connection
    *
    * @param array $params
    * @return null
    */
    function connect($params) {
      $host     = array_var($params, 'hostname', '');
      $user     = array_var($params, 'username', '');
      $pass     = array_var($params, 'password', '');
      $database = array_var($params, 'name', '');
      $persist  = array_var($params, 'persist', false);
      
      $link = $persist ? @mysql_pconnect($host, $user, $pass) : @mysql_connect($host, $user, $pass);
        
      if(!is_resource($link)) {
        throw new Angie_DB_Error_Connect($host, $user, $database);
      } // if
      
      if(!@mysql_select_db($database, $link)) {
        throw new Angie_DB_Error_Connect($host, $user, $database);
      } // if
      
      $this->setHostname($host);
      $this->setUsername($user);
      $this->setPassword($pass);
      $this->setDatabaseName($database);
      
      $this->setLink($link);
    } // connect
    
    /**
    * Execute SQL command
    * 
    * Use this function to execute SQL command. Possible results:
    * 
    * - commands that return some kind of result (SELECT, SHOW...) return a populated Angie_DB_ResultSet object
    * - DELETE or UPDATE commants return number of affected rows
    * - INSERT commants return last insert ID
    * - other commants return TRUE on success
    * 
    * In case of any error Angie_DB_Error_Query will be thrown.
    *
    * @param string $sql
    * @param mixed $arguments
    * @return mixed
    * @throws Angie_DB_Error_Query
    */
    function execute($sql, $arguments = null) {
      $for_execution = is_array($arguments) ? $this->prepareString($sql, $arguments) : $sql;
      $result = @mysql_query($for_execution, $this->link);
      
      if($result === false) {
        throw new Angie_DB_Error_Query($for_execution, mysql_error($this->link));
      } // if
      
      if(is_resource($result)) {
        return new Angie_DB_MySQL_ResultSet($result, $this);
      } else {
        $lowercased_query = strtolower($for_execution);
        if(str_starts_with($lowercased_query, 'insert')) {
          return mysql_insert_id($this->link);
        } elseif(str_starts_with($lowercased_query, 'update') || str_starts_with($lowercased_query, 'delete')) {
          return mysql_affected_rows($this->link);
        } else {
          return true;
        } // if
      } // if
    } // execute
    
    /**
    * Execute query and return first result as associative array
    * 
    * Execute query, but return only the first row from the result. If $arguments is present $sql will be prepared.
    * This function returns NULL if there is no rows that match the request.
    *
    * @param string $sql
    * @param array $arguments
    * @return array
    */
    function executeOne($sql, $arguments = null) {
      $result = $this->execute($sql, $arguments);
      
      if($result instanceof Angie_DB_ResultSet) {
        $row = $result->fetchRow();
        $result->free();
        return $row;
      } else {
        return null;
      } // if
    } // executeOne
    
    /**
    * Execute query and return all rows
    * 
    * This function is used to execute query and return all rows as an array. If $arguments is present $sql will be 
    * prepared. This function returns NULL if there is no rows that match the request.
    *
    * @param string $sql
    * @param array $arguments
    * @return array
    */
    function executeAll($sql, $arguments = null) {
      $result = $this->execute($sql, $arguments);
      
      if($result instanceof Angie_DB_ResultSet) {
        $rows = $result->fetchAll();
        $result->free();
        return $rows;
      } else {
        return null;
      } // if
    } // executeAll
    
    /**
    * Begin work
    * 
    * Start a transaction
    *
    * @param void
    * @return null
    */
    function begin() {
      return $this->execute('BEGIN WORK');
    } // begin
    
    /**
    * Commit
    * 
    * Commits statements in a transaction
    *
    * @param void
    * @return null
    */
    function commit() {
      return $this->execute('COMMIT');
    } // commit
    
    /**
    * Rollback
    * 
    * Rollback changes in a transaction
    *
    * @param void
    * @return null
    */
    function rollback() {
      return $this->execute('ROLLBACK');
    } // rollback
    
    // ---------------------------------------------------
    //  Util methods
    // ---------------------------------------------------
    
    /**
    * Prepare string
    * 
    * This function will walk through string and replace every ? with proper escaped argument
    *
    * @param string $string
    * @param array $arguments
    * @return string
    */
    function prepareString($string, $arguments) {
      if(is_array($arguments) && count($arguments)) {
        foreach($arguments as $argument) {
          $string = str_replace_first('?', $this->escape($argument), $string);
        } // foreach
      } // if
      return $string;
    } // prepareString
    
    /**
    * Escape value
    * 
    * This function will prepare and escape value so it can be safly used in query
    *
    * @param mixed $unescaped
    * @return string
    */
    function escape($unescaped) {
      if(is_null($unescaped)) {
        return 'NULL';
      } elseif(is_bool($unescaped)) {
        return $unescaped ? "'1'" : "'0'";
      } elseif(is_array($unescaped)) {
        $escaped_array = array();
        foreach($unescaped as $unescaped_value) {
          $escaped_array[] = $this->escape($unescaped_value);
        } // if
        return implode(', ', $escaped_array);
      } elseif(is_object($unescaped) && ($unescaped instanceof Angie_DateTime)) {
        return "'" . mysql_real_escape_string($unescaped->toMySQL(), $this->link) . "'";
      } else {
        return "'" . mysql_real_escape_string($unescaped, $this->link) . "'";
      } // if
    } // escapeString
    
    /**
    * Escape field name
    *
    * @param string $unescaped
    * @return string
    */
    function escapeFieldName($unescaped) {
      return '`' . $unescaped . '`';
    } // escapeFieldName
    
    /**
    * Escape table name
    *
    * @param string $unescaped
    * @return string
    */
    function escapeTableName($unescaped) {
      return '`' . $unescaped . '`';
    } // escapeTableName
    
    /**
    * Return last insert ID
    *
    * @param void
    * @return integer
    */
    function lastInsertId() {
      return mysql_insert_id($this->link);
    } // lastInsertId
    
    /**
    * Return number of rows affected by the last query
    *
    * @param void
    * @return integer
    */
    function affectedRows() {
      return mysql_affected_rows($this->link);
    } // affectedRows
    
    /**
    * Return array of all tables in the selected database
    *
    * @param void
    * @return array
    */
    function listTables() {
      $key = 'Tables_in_' . $this->getDatabaseName();
      
      $rows = $this->executeAll("SHOW TABLES");
      
      $tables = array();
      if(is_foreachable($rows)) {
        foreach($rows as $row) {
          $tables[] = $row[$key];
        } // foreach
      } // if
      
      return $tables;
    } // listTables
    
    /**
    * Return array of fields in a specific table
    *
    * @param string $table_name
    * @return array
    */
    function listFilds($table_name) {
      $fields = array();
      
      $rows = $this->executeAll('SHOW FIELDS FROM ' . $this->escapeTableName($table_name));
      if(is_foreachable($rows)) {
        foreach($rows as $row) {
          $field_name = array_var($row, 'Field');
          if($field_name) {
            $fields[] = $field_name;
          } // if
        } // foreach
      } // if
      
      return $fields;
    } // listFilds
    
    /**
    * Drop a specific table from database
    *
    * @param string $table_name
    * @param boolean $only_if_exists
    * @return boolean
    */
    function dropTable($table_name, $only_if_exists = false) {
      $if_exists = '';
      if($only_if_exists) {
        $if_exists = "IF EXISTS";
      } // if
      
      return $this->execute("DROP TABLE $if_exists " . $this->escapeTableName($table_name));
    } // dropTable
    
    /**
    * Syncronise existing table with generator table description
    *
    * @param Angie_DBA_Generator_Table $table
    * @param string $table_prefix
    * @return null
    */
    function syncTable(Angie_DBA_Generator_Table $table, $table_prefix = '') {
      
    } // syncTable
    
    /**
    * Createa a new table based on a generator table description
    *
    * @param Angie_DBA_Generator_Table $table
    * @param string $table_prefix
    * @return null
    */
    function buildTable(Angie_DBA_Generator_Table $table, $table_prefix = '') {
      $escaped_table_name = $this->escapeTableName($table_prefix . $table->getName());
      
      $mysql_version = mysql_get_client_info();
      $engine = 'ENGINE=' . Angie::getConfig('mysql.default_engine', 'MyISAM');
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
      foreach($table->getPrimaryKey() as $primary_key) {
        $escaped_primary_keys[] = $this->escapeFieldName($primary_key);
      } // foreach
      $escaped_primary_keys = implode(', ', $escaped_primary_keys);
      
      $primary_key = "PRIMARY KEY($escaped_primary_keys)";
      
      $fields_code = array();
      
      foreach($table->getFields() as $field) {
        $field_name = $this->escapeFieldName($field->getName());
        
        // Integer
        if($field instanceof Angie_DBA_Generator_Field_Integer) {
          $unsigned = $field->getIsUnsigned() ? 'UNSIGNED' : '';
          $auto_increment = $field->getIsAutoIncrement() ? 'auto_increment' : '';
          switch($field->getTypeSize()) {
            case Angie_DBA_Generator::SIZE_TINY:
              $type = 'TINYINT';
              break;
            case Angie_DBA_Generator::SIZE_SMALL:
              $type = 'SMALLINT';
              break;
            case Angie_DBA_Generator::SIZE_MEDIUM:
              $type = 'MEDIUMINT';
              break;
            case Angie_DBA_Generator::SIZE_BIG:
              $type = 'BIGINT';
              break;
            default:
              $type = 'INT';
          } // switch
          
          $fields_code[] = "$field_name $type $unsigned NOT NULL $auto_increment";
          
        // Varchar
        } elseif($field instanceof Angie_DBA_Generator_Field_String) {
          $lenght = $field->getLenght();
          
          $fields_code[] = "$field_name VARCHAR($lenght) NOT NULL";
          
        // Text
        } elseif($field instanceof Angie_DBA_Generator_Field_Text) {
          switch($field->getTypeSize()) {
            case Angie_DBA_Generator::SIZE_TINY:
              $type = 'TINYTEXT';
              break;
            case Angie_DBA_Generator::SIZE_SMALL:
              $type = 'TEXT';
              break;
            case Angie_DBA_Generator::SIZE_MEDIUM:
              $type = 'MEDIUMTEXT';
              break;
            case Angie_DBA_Generator::SIZE_BIG:
              $type = 'LONGTEXT';
              break;
            default:
              $type = 'TEXT';
          } // swtich
          
          $fields_code[] = "$field_name $type NOT NULL";
          
        // Datetime
        } elseif($field instanceof Angie_DBA_Generator_Field_DateTime) {
          $fields_code[] = "$field_name DATETIME NOT NULL";
          
        // Enum
        } elseif($field instanceof Angie_DBA_Generator_Field_Enum) {
          $escaped_possible_values = array();
          foreach($field->getPossibleValues() as $possible_value) {
            $escaped_possible_values[] = "'" . str_replace(array('\\', "'"), array('\\\\', "\'"), $possible_value) . "'";
          } // foreach
          $escaped_possible_values = implode(', ', $escaped_possible_values);
          
          $default_value = "'" . str_replace(array('\\', "'"), array('\\\\', "\'"), $field->getDefaultValue()) . "'";
          
          $fields_code[] = "$field_name ENUM($escaped_possible_values) DEFAULT $default_value NOT NULL";
        } else {
          throw new Angie_Core_Error_InvalidParamValue('field', $field, '$field is not supported type by this database engine');
        } // if
      } // foreach
      
      return $this->execute("CREATE TABLE $escaped_table_name (\n" . implode($fields_code, ",\n") . ",\n$primary_key\n) $engine $default_charset $default_collation");
    } // buildTable
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get link
    *
    * @param null
    * @return resource
    */
    function getLink() {
      return $this->link;
    } // getLink
    
    /**
    * Set link value
    *
    * @param resource $value
    * @return null
    */
    private function setLink($value) {
      $this->link = $value;
    } // setLink
    
    /**
    * Get hostname
    *
    * @param null
    * @return string
    */
    function getHostname() {
      return $this->hostname;
    } // getHostname
    
    /**
    * Set hostname value
    *
    * @param string $value
    * @return null
    */
    function setHostname($value) {
      $this->hostname = $value;
    } // setHostname
    
    /**
    * Get username
    *
    * @param null
    * @return string
    */
    function getUsername() {
      return $this->username;
    } // getUsername
    
    /**
    * Set username value
    *
    * @param string $value
    * @return null
    */
    function setUsername($value) {
      $this->username = $value;
    } // setUsername
    
    /**
    * Get password
    *
    * @param null
    * @return string
    */
    function getPassword() {
      return $this->password;
    } // getPassword
    
    /**
    * Set password value
    *
    * @param string $value
    * @return null
    */
    function setPassword($value) {
      $this->password = $value;
    } // setPassword
    
    /**
    * Get database_name
    *
    * @param null
    * @return string
    */
    function getDatabaseName() {
      return $this->database_name;
    } // getDatabaseName
    
    /**
    * Set database_name value
    *
    * @param string $value
    * @return null
    */
    function setDatabaseName($value) {
      $this->database_name = $value;
    } // setDatabaseName
  
  } // Angie_DB_MySQL_Connection

?>