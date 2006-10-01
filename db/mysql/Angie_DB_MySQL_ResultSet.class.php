<?php

  /**
  * Simple MySQL DB implementation result object
  * 
  * Result object used by Simple MySQL DB implementation
  *
  * @package Angie.DB
  * @subpackage mysql
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DB_MySQL_ResultSet extends Angie_DB_ResultSet {
  
    /**
    * Return next row from the result and return it
    *
    * @param void
    * @return array
    */
    function fetchRow() {
      return mysql_fetch_row($this->resource);
    } // fetchRow
    
    /**
    * Return number of rows in resource
    *
    * @param void
    * @return integer
    */
    function numRows() {
      return mysql_num_rows($this->resource);
    } // numRows
    
    /**
    * Free the resource
    *
    * @param void
    * @return null
    */
    function free() {
      return mysql_free_result($this->resource);
    } // free
  
  } // Angie_DB_MySQL_ResultSet

?>