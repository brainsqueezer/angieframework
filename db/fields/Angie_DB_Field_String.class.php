<?php

  /**
  * Single line string field
  * 
  * This field describes a single line string field with a given lenght property
  *
  * @package Angie.DB
  * @subpackage fields
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DB_Field_String extends Angie_DB_Field {
    
    /**
    * Number of characters
    *
    * @var integer
    */
    private $lenght;
  
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get lenght
    *
    * @param null
    * @return integer
    */
    function getLenght() {
      return $this->lenght;
    } // getLenght
    
    /**
    * Set lenght value
    *
    * @param integer $value
    * @return null
    */
    function setLenght($value) {
      $this->lenght = $value;
    } // setLenght
  
  } // Angie_DB_Field_String

?>