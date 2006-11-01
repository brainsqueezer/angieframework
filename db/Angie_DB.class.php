<?php

  /**
  * Database interface
  * 
  * This class is used to provide interface to database library. It is here for compatibility reasons and to better 
  * integrate Creole with Angie (statement preparation, additional data types like Angie_DateTime support etc). Also, 
  * this class support usage of multiple database connections through same interface
  *
  * @package Angie.DB
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DB {
    
    /**
    * Default database connection
    *
    * @var Connection
    */
    static private $default_connection;
  
    /**
    * Named, additional connections
    *
    * @var array
    */
    static private $additional_connections = array();
    
    // ---------------------------------------------------
    //  Interface methods
    // ---------------------------------------------------
    
    /**
    * Execute SQL command
    * 
    * Use this function to execute any SQL command on given connection (if $connection_name is NULL default connection 
    * will be used). Possible results:
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
    * @param string $connection_name
    * @return mixed
    * @throws Angie_DB_Error_Query
    */
    static function execute($sql, $arguments = null, $connection_name = null) {
      return self::getConnection($connection_name)->execute($sql, $arguments);
    } // execute
    
    /**
    * Execute query that returns result set (SELECT, SHOW etc), but return only first row
    * 
    * If $arguments is array than they will be used to with $sql to preapre a query, else raw $sql value will be used. 
    * Optional $connection_name let you select what connection will be used to execute this query
    * 
    * This method will limit execution to only one row
    *
    * @param string $sql
    * @param array $arguments
    * @param string $connection_name
    * @return array
    * @throws SQLException
    */
    static function executeOne($sql, $arguments = null, $connection_name = null) {
      return self::getConnection($connection_name)->executeOne($sql, $arguments);
    } // executeOne
    
    /**
    * Execute query that returns result set (SELECT, SHOW etc) and return all rows
    * 
    * If $arguments is array than they will be used to with $sql to preapre a query, else raw $sql value will be used. 
    * Optional $connection_name let you select what connection will be used to execute this query
    * 
    * This method will return rows as associative array
    *
    * @access public
    * @param string $sql
    * @return array
    * @throws DBQueryError
    */
    static function executeAll($sql, $arguments = null, $connection_name = null) {
      return self::getConnection($connection_name)->executeAll($sql, $arguments);
    } // executeAll
    
    /**
    * Start transaction
    *
    * @param string $connection_name
    * @return boolean
    * @throws DBQueryError
    */
    static function beginWork($connection_name = null) {
      return self::getConnection($connection_name)->begin();
    } // beginWork
    
    /**
    * Commit transaction
    *
    * @param string 
    * @return boolean
    * @throws DBQueryError
    */
    static function commit($connection_name = null) {
      return self::getConnection($connection_name)->commit();
    } // commit
    
    /**
    * Rollback transaction
    *
    * @access public
    * @param void
    * @return boolean
    * @throws DBQueryError
    */
    static function rollback($connection_name = null) {
      return self::getConnection($connection_name)->rollback();
    } // rollback
    
    /**
    * Escape string
    *
    * @param string $unescaped
    * @param string $connection_name
    * @return string
    */
    static function escape($unescaped, $connection_name = null) {
      return self::getConnection($connection_name)->escape($unescaped);
    } // escape
    
    /**
    * Return escaped field name
    *
    * @param string $unescaped
    * @param string $connection_name
    * @return string
    */
    static function escapeFieldName($unescaped, $connection_name = null) {
      return self::getConnection($connection_name)->escapeFieldName($unescaped);
    } // escapeFieldName
    
    /**
    * Escape table name
    *
    * @param string $unescaped
    * @param string $connection_name
    * @return string
    */
    static function escapeTableName($unescaped, $connection_name = null) {
      return self::getConnection($connection_name)->escapeTableName($unescaped);
    } // escapeTableName
    
    /**
    * Prepare string
    * 
    * This function will use $string as base and replace every ? with properly escaped argument. Example:
    * <pre>
    * Angie_DB::prepareString('username = ? AND homepage = ?', array('Ilija', 'http://www.ilija.biz/'));
    * // For MySQL it will return: username = 'Ilija' AND homepage = 'http://www.ilija.biz/'
    * </pre>
    * 
    * This function supports all types that are supported by DBA including DateTime, booleans etc.
    *
    * @param string $string
    * @param array $arguments
    * @param string $connection_name
    * @return string
    */
    static function prepareString($string, $arguments, $connection_name = null) {
      return self::getConnection($connection_name)->prepareString($string, $arguments);
    } // prepareString
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get connection
    * 
    * If $connection_name is present named (additional) connection will be returned. Else, default connection will be 
    * returned
    *
    * @param null
    * @return Angie_DB_Connection
    */
    static function getConnection($connection_name = null) {
      if(is_null($connection_name)) {
        return self::$default_connection;
      } else {
        $connection = array_var(self::$additional_connections, $connection_name);
        if($connection instanceof Angie_DB_Connection) {
          return $connection;
        } else {
          throw new Angie_DB_Error_ConnectionDnx($connection_name);
        } // if
      } // if
    } // getConnection
    
    /**
    * Set connection value
    * 
    * If $connection_name value is present we will set additional, named connection. Else we will set default connection
    *
    * @param Angie_DB_Connection $value
    * @param string $connection_name
    * @return null
    */
    static function setConnection(Angie_DB_Connection $connection, $connection_name = null) {
      $trimmed = trim($connection_name);
      if($trimmed) {
        self::$additional_connections[$trimmed] = $connection;
      } else {
        self::$default_connection = $connection;
      } // if
    } // setConnection
  
  } // Angie_DB

?>