<?php

  /**
  * DBA collection interator
  *
  * @package Angie.DBA
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_CollectionIterator implements Iterator {
  
    /**
    * Array that is iterated
    *
    * @var array
    */
    private $array = array();
    
    /**
    * A switch to keep track of the end of the array
    *
    * @var boolean
    */
    private $valid = false;
    
    /**
    * Construct the iterator with array that needs to be iterated
    *
    * @param array $array
    * @return Angie_DBA_CollectionIterator
    */
    function __construct($array) {
       $this->array = $array;
    } // __construct
    
    /**
    * Return the array "pointer" to the first element
    *
    * @param void
    * @return null
    */
    function rewind() {
       $this->valid = (FALSE !== reset($this->array));
    } // rewind
    
    /**
    * Return the current array element
    *
    * @param void
    * @return mixed
    */
    function current() {
       return current($this->array);
    } // current
    
    /**
    * Return the key of the current array element
    *
    * @param void
    * @return mixed
    */
    function key() {
       return key($this->array);
    } // key
    
    /**
    * Move to the next array member
    *
    * @param void
    * @return null
    */
    function next() {
       $this->valid = (false !== next($this->array));
    } // next
    
    /**
    * Check if the current element is valid?
    *
    * @param void
    * @return boolean
    */
    function valid() {
       return $this->valid;
    } // valid
    
  } // end class

?>