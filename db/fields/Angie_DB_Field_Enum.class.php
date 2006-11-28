<?php

  /**
  * Enumerable database field
  * 
  * This class descirbes enumerable database field - a field that can have only 
  * one value from a given list of possible values
  *
  * @package Angie.DB
  * @subpackage fields
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DB_Field_Enum extends Angie_DB_Field {
    
    /**
    * Array of possible string values
    *
    * @var array
    */
    private $possible_values = array();
  
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get possible_values
    *
    * @param null
    * @return array
    */
    function getPossibleValues() {
      return $this->possible_values;
    } // getPossibleValues
    
    /**
    * Set possible_values value
    *
    * @param array $value
    * @return null
    */
    function setPossibleValues($value) {
      if(is_array($value)) {
        $this->possible_values = $value;
      } else {
        $this->possible_values = array();
      } // if
    } // setPossibleValues
  
  } // Angie_DB_Field_Enum

?>