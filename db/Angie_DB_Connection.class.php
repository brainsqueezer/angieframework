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
    function executeQuery($sql, $arguments = null);
    
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
    * Execute query that update data (INSERT, UPDATE, DELETE)
    * 
    * This function will execute a query that updates data and return number of affected rows. If INSERT is executed 
    * last insert ID will be returned
    *
    * @param string $sql
    * @param array $arguments
    * @return integer
    */
    function executeUpdate($sql, $arguments = null);
    
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
  
  } // Angie_DB_Connection

?>