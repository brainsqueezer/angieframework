<?php

  /**
  * Database connection interface
  * 
  * Every database connection used by Angie need to implement this interface. That enable us to build applications that 
  * use different abstraction layers or libraries and still have everything under control
  *
  * @package Angie.DB
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  interface Angie_DB_Connection {
    
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
    function connect($params);
  
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
    function execute($sql, $arguments = null);
    
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
    function executeOne($sql, $arguments = null);
    
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
    function executeAll($sql, $arguments = null);
    
    /**
    * Begin work
    * 
    * Start a transaction
    *
    * @param void
    * @return null
    */
    function begin();
    
    /**
    * Commit
    * 
    * Commits statements in a transaction
    *
    * @param void
    * @return null
    */
    function commit();
    
    /**
    * Rollback
    * 
    * Rollback changes in a transaction
    *
    * @param void
    * @return null
    */
    function rollback();
    
    /**
    * Escape string before we use it a query
    *
    * @param string $unescaped
    * @return string
    */
    function escape($unescaped);
    
    /**
    * Escape field name
    *
    * @param string $unescaped
    * @return string
    */
    function escapeFieldName($unescaped);
    
    /**
    * Escape table name
    *
    * @param string $unescaped
    * @return string
    */
    function escapeTableName($unescaped);
    
    /**
    * Prepare string (replace every ? with proper arguemnt value)
    *
    * @param string $string
    * @param array $arguments
    * @return string
    */
    function prepareString($string, $arguments);
    
    /**
    * Return last insert ID
    *
    * @param void
    * @return integer
    */
    function lastInsertId();
    
    /**
    * Return number of rows affected by the last query
    *
    * @param void
    * @return integer
    */
    function affectedRows();
  
  } // Angie_DB_Connection

?>