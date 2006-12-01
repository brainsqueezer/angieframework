<?php

  /**
  * Binary field definition
  * 
  * Binary large object field definition
  *
  * @package Angie.DB
  * @subpackage fields
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DB_Field_Binary extends Angie_DB_Field {
  
    /**
    * Primitive field type
    *
    * @var string
    */
    protected  $type = Angie_DB::TYPE_BINARY;
    
  } // Angie_DB_Field_Binary

?>