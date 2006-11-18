<?php

  /**
  * Angie route
  *
  * @package Angie.toys
  * @subpackage router
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  final class Angie_Router_Route {
  
    /**
    * URL variables are prefixed with this character
    */
    const URL_VARIABLE = ':';
    const REGEX_DELIMITER = '#';
    const DEFAULT_REGEX = '([a-z0-9\-\._]+)';
    const QUERY_STRING_SWITCH = '*';
    
    /**
    * Input route string that is parsed into parts on construction
    *
    * @var string
    */
    private $route_string;
    
    /**
    * Route string parsed into associative array of param name => regular expression
    *
    * @var array
    */
    private $parts;
    
    /**
    * Default values for specific params
    *
    * @var array
    */
    private $defaults = array();
    
    /**
    * Regular expressions that force specific expressions for specific params
    *
    * @var array
    */
    private $requirements = array();

    /**
    * Construct route
    * 
    * This function will parse route string and populate $this->parts with rules that need to be matched
    *
    * @param string $route
    * @param array $defaults
    * @param array $requirements
    * @return Angie_Route
    */
    function __construct($route, $defaults = array(), $requirements = array()) {
      $route = trim($route, '/');
      
      $this->route_string = $route;
      $this->defaults     = (array) $defaults;
      $this->requirements = (array) $requirements;

      foreach(explode('/', $route) as $pos => $part) {
        if(substr($part, 0, 1) == self::URL_VARIABLE) {
          $name = substr($part, 1);
          $regex = (isset($requirements[$name]) ? '(' . $requirements[$name] . ')' : self::DEFAULT_REGEX);
          $this->parts[$pos] = array(
            'name'  => $name, 
            'regex' => $regex
          ); // array
        } else {
          $this->parts[$pos] = array(
            'regex' => preg_quote($part, self::REGEX_DELIMITER)
          ); // array
        } // if
      } // foreach

    } // __construct

    /**
    * Match $path with this route
    * 
    * Break down $path in part and compare with parsed route (rules are collected in $this->parts). This function will 
    * return associative array of matched parts
    *
    * @param string $path
    * @param string $query_string
    * @return boolean
    */
    function match($path, $query_string = null) {
      $values = $this->defaults;
      
      $parameters = array();
      $regex = array();
      foreach($this->parts as $part) {
        $regex[] = $part['regex'];
        if(isset($part['name'])) {
          $parameters[] = $part['name'];
        } // if
      } // foreach
      
      $regex = '/^' . implode('\/', $regex) . '$/';
      $matches = null;
      if(preg_match($regex, trim($path, '/'), $matches)) {
        $index = 0;
        foreach($parameters as $parameter_name) {
          $index++;
          $values[$parameter_name] = $matches[$index];
        } // foreach
      } else {
        return false;
      } // if
      
      if(!isset($values['controller'])) {
        $values['controller'] = Angie::DEFAULT_CONTROLLER_NAME;
      } // if
      
      if(!isset($values['action'])) {
        $values['action'] = Angie::DEFAULT_ACTION_NAME;
      } // if
      
      if($query_string) {
        $query_string_parameters = array();
        parse_str($query_string, $query_string_parameters);
        
        if(is_foreachable($query_string_parameters)) {
          foreach($query_string_parameters as $parameter_name => $parameter_value) {
            if(!isset($values[$parameter_name])) {
              $values[$parameter_name] = $parameter_value;
            } // if
          } // foreach
        } // if
        
      } // if
      
      return $values;
      
//      $path = explode('/', trim($path, '/'));
//      if(count($path) <> count($this->parts)) {
//        return false;
//      } // if
//      
//      foreach($this->parts as $pos => $part) {
//        $name = isset($part['name']) ? $part['name'] : null;
//        
//        if(!isset($path[$pos])) {
//          if(is_null($name)) {
//            return false;
//          } elseif(!array_key_exists($name, $this->defaults)) {
//            return false;
//          } // if
//        } // if
//        
//        $regex = self::REGEX_DELIMITER . '^' . $part['regex'] . '$' . self::REGEX_DELIMITER . 'i';
//        if(preg_match($regex, $path[$pos])) {
//          if($name) {
//            $values[$name] = $path[$pos];
//          } // if
//        } // if
//        
//      } // foreach
    } // match

    /**
    * Assemle URL based on provided input data
    * 
    * This function will use input data and put it into route string. It can return relative path based on the route 
    * string or absolute URL (PROJECT_URL constant will be used as a base)
    *
    * @param array $data
    * @param boolean
    * @return string
    */
//    function assemble($data = array(), $absolute_url = true) {
//      $url = array();
//      
//      foreach($this->parts as $key => $part) {
//        if(isset($part['name'])) {
//          if (isset($data[$part['name']])) {
//            $url[$key] = $data[$part['name']];
//          } elseif (isset($this->defaults[$part['name']])) {
//            $url[$key] = $this->defaults[$part['name']];
//          } else {
//            throw new Angie_Router_Error_Assemble($this->getRouteString(), $data, $this->getDefaults());
//          } // if
//        } else {
//          $url[$key] = $part['regex'];
//        } // if
//      } // foreach
//      
//      $base = '';
//      if($absolute_url) {
//        $base = PROJECT_URL . '/';
//      } // if
//      
//      return $base . implode('/', $url);
//    } // assemble
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get route_string
    *
    * @param null
    * @return string
    */
    function getRouteString() {
      return $this->route_string;
    } // getRouteString
    
    /**
    * Set route_string value
    *
    * @param string $value
    * @return null
    */
    function setRouteString($value) {
      $this->route_string = $value;
    } // setRouteString
    
    /**
    * Return defaults value
    *
    * @param void
    * @return array
    */
    function getDefaults() {
      return $this->defaults;
    } // getDefaults
    
    /**
    * Return requirements value
    *
    * @param void
    * @return array
    */
    function getRequirements() {
      return $this->requirements;
    } // getRequirements
  
  } // Angie_Router_Route

?>