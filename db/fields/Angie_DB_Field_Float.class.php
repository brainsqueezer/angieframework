<?php

  /**
  * Float field description
  * 
  * Float field description includes lenght and precission properties
  *
  * @package Angie.DB
  * @subpackage fields
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DB_Field_Float extends Angie_DB_Field {
    
    /**
    * Primitive field type
    *
    * @var string
    */
    protected  $type = Angie_DB::TYPE_FLOAT;
  
    /**
    * Value lenght
    *
    * @var integer
    */
    private $lenght;
    
    /**
    * Precission - number of decimal places
    *
    * @var integer
    */
    private $precission;
    
    /**
    * Unsigned field
    *
    * @var boolean
    */
    private $unsigned = false;
    
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
    
    /**
    * Get precission
    *
    * @param null
    * @return integer
    */
    function getPrecission() {
      return $this->precission;
    } // getPrecission
    
    /**
    * Set precission value
    *
    * @param integer $value
    * @return null
    */
    function setPrecission($value) {
      $this->precission = $value;
    } // setPrecission
    
    /**
    * Get unsigned
    *
    * @param null
    * @return boolean
    */
    function getUnsigned() {
      return $this->unsigned;
    } // getUnsigned
    
    /**
    * Set unsigned value
    *
    * @param boolean $value
    * @return null
    */
    function setUnsigned($value) {
      $this->unsigned = $value;
    } // setUnsigned
  
  } // Angie_DB_Field_Float

?>