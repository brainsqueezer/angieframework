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
  class Angie_DB_MySQL_Connection {
    
    /**
    * Link resource
    *
    * @var resource
    */
    private $link;
  
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
      
      $this->setLink($link);
    } // connect
  
    /**
    * Execute query that returns result set
    * 
    * This method is used to execute a query that does not do any data manipulation but returns data from the database 
    * (SELECT, SHOW etc). Result of this function is ResultSet object. If $arguments is present $sql will be prepared. 
    * This function returns NULL if there is no rows that match the request.
    *
    * @param string $sql
    * @param array $arguments
    * @return Angie_DB_ResultSet
    */
    function executeQuery($sql, $arguments = null) {
      $for_execution = is_array($arguments) ? $this->prepareString($sql, $arguments) : $sql;
      $result = @mysql_query($for_execution, $this->link);
      
      if($result === false) {
        throw new Angie_DB_Error_Query($for_execution, mysql_error($this->link));
      } // if
      
      if(is_resource($result)) {
        return new Angie_DB_MySQL_ResultSet($result, $this);
      } else {
        return null;
      } // if
    } // executeQuery
    
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
      $result = $this->executeQuery($sql, $arguments);
      
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
      $result = $this->executeQuery($sql, $arguments);
      
      if($result instanceof Angie_DB_ResultSet) {
        $rows = $result->fetchAll();
        $result->free();
        return $rows;
      } else {
        return null;
      } // if
    } // executeAll
    
    /**
    * Execute query that update data (INSERT, UPDATE, DELETE)
    * 
    * This function will execute a query that updates data and return number of affected rows. If INSERT is executed 
    * last insert ID will be returned
    *
    * @param string $sql
    * @param array $arguments
    * @return integer
    */
    function executeUpdate($sql, $arguments = null) {
      $for_execution = trim(is_array($arguments) ? $this->prepareString($sql, $arguments) : $sql);
      
      $result = @mysql_query($for_execution, $this->link);
      if($result === false) {
        throw new Angie_DB_Error_Query($for_execution, mysql_error($this->link));
      } // if
      
      if(str_starts_with(strtolower($for_execution), 'insert')) {
        return mysql_insert_id($this->link);
      } else {
        return mysql_affected_rows($this->link);
      } // if
    } // executeUpdate
    
    /**
    * Begin work
    * 
    * Start a transaction
    *
    * @param void
    * @return null
    */
    function begin() {
      return mysql_query('BEGIN WORK', $this->link);
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
      return mysql_query('COMMIT', $this->link);
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
      return mysql_query('ROLLBACK', $this->link);
    } // rollback
    
    // ---------------------------------------------------
    //  Util methods
    // ---------------------------------------------------
    
    /**
    * Prepare string
    * 
    * This function will walk through string and replace every ? with proper escaped argument
    *
    * @param string $sql
    * @param array $arguments
    * @return string
    */
    function prepareString($sql, $arguments) {
      if(is_array($arguments) && count($arguments)) {
        foreach($arguments as $argument) {
          $sql = str_replace_first('?', $this->escape($argument), $sql);
        } // foreach
      } // if
      return $sql;
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
      } elseif(is_object($unescaped) && ($unescaped instanceof DateTimeValue)) {
        return "'" . mysql_real_escape_string($unescaped->toMySQL(), $this->link) . "'";
      } else {
        return "'" . mysql_real_escape_string($unescaped, $this->link) . "'";
      } // if
    } // escapeString
    
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
  
  } // Angie_DB_MySQL_Connection

?>