<?php

  /**
  * Route match
  * 
  * Match object contains a reference to a matching route and all extracted data. ArrayAccess interface has been 
  * implemented so this object can be used as an array
  *
  * @package Angie.toys
  * @subpackage router
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Router_Match implements ArrayAccess {
    
    /**
    * Route that produced this match
    *
    * @var Angie_Router_Route
    */
    private $route;
    
    /**
    * Associative array of matched elements
    *
    * @var array
    */
    private $matches;
  
    /**
    * Constructor
    *
    * @param void
    * @return Angie_Router_Match
    */
    function __construct(Angie_Router_Route $route, $matches) {
      $this->setRoute($route);
      
      if(is_array($matches)) {
        $this->matches = $matches;
      } elseif($matches) {
        $this->matches = array($matches);
      } // if
    } // __construct
    
    // ---------------------------------------------------
    //  ArrayAccess implementation
    // ---------------------------------------------------
    
    /**
    * Check if specific offset exists
    *
    * @param string $offset
    * @return boolean
    */
    function offsetExists($offset) {
      return isset($this->matches[$offset]);
    } // offsetExists
 	  
    /**
    * Return param at given offset
    *
    * @param string $offset
    * @return mixed
    */
    function offsetGet($offset) {
      return isset($this->matches[$offset]) ? $this->matches[$offset] : null;
    } // offsetGet
    
    /**
    * Set value of specific key
    * 
    * This function will throw an acception because route match object is read only
    *
    * @param string $offset
    * @param mixed $value
    * @return null
    */
 	  function offsetSet($offset, $value) {
 	    throw new Angie_Error('Route match object is read only');
 	  } // offsetSet
 	  
 	  /**
 	  * Unset value of specific $offset
 	  *
 	  * This function will throw an acception because route match object is read only
 	  * 
 	  * @param string $offset
 	  * @return null
 	  */
 	  function offsetUnset($offset) {
 	    throw new Angie_Error('Route match object is read only');
 	  } // offsetUnset
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get route
    *
    * @param null
    * @return Angie_Router_Route
    */
    function getRoute() {
      return $this->route;
    } // getRoute
    
    /**
    * Set route value
    *
    * @param Angie_Router_Route $value
    * @return null
    */
    private function setRoute(Angie_Router_Route $value) {
      $this->route = $value;
    } // setRoute
    
    /**
    * Return array of matches
    *
    * @param void
    * @return array
    */
    function getMatches() {
      return $this->matches;
    } // getMatches
  
  } // Angie_Router_Match

?>