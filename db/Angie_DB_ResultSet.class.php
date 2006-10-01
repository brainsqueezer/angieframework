<?php

  /**
  * Database result set
  * 
  * Base result set class. Result set returned by connections need to use subclassed result set in order to be accepted 
  * by system.
  *
  * @package Angie.DB
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_DB_ResultSet {
    
    /**
    * Connection that created this result set
    *
    * @var Angie_DB_Connection
    */
    protected $connection;
    
    /**
    * Result set resource
    *
    * @var resource
    */
    protected $resource;
    
    /**
    * Array of fetched rows
    *
    * @var array
    */
    private $rows;
    
    /**
    * Construct
    *
    * @param resource $resource
    * @param Angie_DB_Connection $connection
    * @return null
    */
    function __construct($resource, Angie_DB_Connection $connection) {
      $this->setResource($resource);
      $this->setConnection($connection);
    } // __construct
    
    /**
    * Return next row from the result and return it
    *
    * @param void
    * @return array
    */
    abstract function fetchRow();
    
    /**
    * Return number of rows in resource
    *
    * @param void
    * @return integer
    */
    abstract function numRows();
    
    /**
    * Free the resource
    *
    * @param void
    * @return null
    */
    abstract function free();
    
    // ---------------------------------------------------
    //  Util methods
    // ---------------------------------------------------
    
    /**
    * Fetch all the rows from the result and return them
    *
    * @param void
    * @return array
    */
    function fetchAll() {
      while($this->fetchRow()) {}
      return $this->rows;
    } // fetchAll
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Return connection that created this result set
    *
    * @param void
    * @return Angie_DB_Connection
    */
    function getConnection() {
      return $this->connection;
    } // getConnection
    
    /**
    * Set connection that created this result
    *
    * @param Angie_DB_Connection
    * @return null
    */
    function setConnection(Angie_DB_Connection $connection) {
      $this->connection = $connection;
    } // setConnection
    
    /**
    * Get resource
    *
    * @param null
    * @return resource
    */
    function getResource() {
      return $this->resource;
    } // getResource
    
    /**
    * Set resource value
    *
    * @param resource $value
    * @return null
    */
    function setResource($value) {
      if(is_resource($value)) {
        $this->resource = $value;
      } // if
    } // setResource
    
  } // Angie_DB_ResultSet

?>