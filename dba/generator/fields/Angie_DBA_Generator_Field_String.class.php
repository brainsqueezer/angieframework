<?php

  /**
  * String field
  * 
  * This class describes single string (VARCHAR) field and makes sure that proper type is used and that lenght property 
  * is present
  *
  * @package Angie.DBA
  * @subpackage generator.fields
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Field_String extends Angie_DBA_Generator_Field {
    
    /**
    * Lenght attribute is used for multiple primary types in diferent context - varchars, floats, integers
    *
    * @var integer
    */
    private $lenght = 100;
  
    /**
    * Constructor
    *
    * Construct string filed - make sure that type and lenght are properly set
    * 
    * @param string $name
    * @param integer $lenght
    * @param mixed $properties
    * @return Angie_DBA_Generator_StringField
    */
    function __construct($name, $lenght, $properties = null) {
      parent::__construct($name, $properties);
      
      $this->setType(Angie_DBA_Generator::TYPE_VARCHAR);
      $this->setCastFunction('strval');
      
      $this->setLenght($lenght);
    } // __construct
    
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
  
  } // Angie_DBA_Generator_Field_String

?>