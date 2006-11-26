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
    * Name of the route
    *
    * @var string
    */
    private $name;
    
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
    function __construct($name, $route, $defaults = array(), $requirements = array()) {
      $route = trim($route, '/');
      
      $this->name         = $name;
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
            'raw' => $part,
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
      
      if(!isset($values['application'])) {
        $values['application'] = Angie::engine()->getDefaultApplicationName();
      } // if
      
      if(!isset($values['controller'])) {
        $values['controller'] = Angie::engine()->getDefaultControllerName();
      } // if
      
      if(!isset($values['action'])) {
        $values['action'] = Angie::engine()->getDefaultActionName();
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
      
      return new Angie_Router_Match($this, $values);
    } // match

    /**
    * Assemle URL based on provided input data
    * 
    * This function will use input data and put it into route string. It can return relative path based on the route 
    * string or absolute URL (PROJECT_URL constant will be used as a base)
    *
    * @param array $data
    * @param string $url_base
    * @param string $query_arg_separator
    * @return string
    */
    function assemble($data, $url_base, $query_arg_separator) {
      if(!is_array($data)) {
        $data = array();
      } // if
      
      $path_parts = array();
      
      $part_names = array();
      foreach($this->parts as $key => $part) {
        if(isset($part['name'])) {
          $part_name = $part['name'];
          $part_names[] = $part_name;
          
          if(isset($data[$part_name])) {
            $path_parts[$key] = $data[$part_name];
          } elseif(isset($this->defaults[$part_name])) {
            $path_parts[$key] = $this->defaults[$part_name];
          } else {
            throw new Angie_Router_Error_Assemble($this->getRouteString(), $data, $this->getDefaults());
          } // if
        } else {
          $path_parts[$key] = $part['regex'];
        } // if
      } // foreach
      
      $query_parts = array();
      foreach($data as $k => $v) {
        if(!in_array($k, $part_names)) {
          $query_parts[$k] = $v;
        } // if
      } // foreach
      
      $url = with_slash($url_base) . implode('/', $path_parts);
      if(count($query_parts)) {
        $url .= '?' . http_build_query($query_parts, '', $query_arg_separator);
      } // if
      
      return $url;
    } // assemble
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get name
    *
    * @param null
    * @return string
    */
    function getName() {
      return $this->name;
    } // getName
    
    /**
    * Set name value
    *
    * @param string $value
    * @return null
    */
    function setName($value) {
      $this->name = $value;
    } // setName
    
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