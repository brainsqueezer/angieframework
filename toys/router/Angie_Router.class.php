<?php

  /**
  * Angie router
  * 
  * Router provides support for canonical, pretty URL-s out of box. Reuqest is matched with set of routes mapped by the 
  * user; when router finds first match it will use data collected from it and match process will be stoped. Routes are 
  * matched in reveresed order so make sure that general routes are on top of the map list.
  *
  * @package Angie.toys
  * @subpackage router
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  final class Angie_Router {
    
    /**
    * Array of mapped routes
    *
    * @var array
    */
    private static $routes = array();
    
    /**
    * Cached match object
    *
    * @var Angie_Router_Match
    */
    private static $match;
    
    /**
    * Regiter a new route
    * 
    * This function will create a new route based on route string, default values and additional requirements and save 
    * it under specific name. Name is used so you can access the route when assembling URL based on a given route. Name 
    * needs to be unique (if route with a given name is already registered it will be overwriten).
    *
    * @param string $name
    * @param string $route
    * @param array $defaults
    * @param array $requirements
    * @return Angie_Router_Route
    */
    static function map($name, $route, $defaults = null, $requirements = null) {
      self::$routes[$name] = new Angie_Router_Route($name, $route, $defaults, $requirements);
      return self::$routes[$name];
    } // map
    
    /**
    * Match request string agains array of mapped routes
    * 
    * This function will loop request string agains array of mapped routes. As soon as request string is matched looping 
    * is stopped and result of route match method is returned (array of name => value pairs). In case that none of the 
    * mapped routes does not match request string Angie_Router_Error_Match will be thrown
    *
    * @param string $str
    * @param string $query_string
    * @return array
    * @throws Angie_Router_Error_Match
    */
    static function match($str, $query_string) {
      $routes = array_reverse(self::$routes);
      
      foreach($routes as $route_name => $route) {
        $match = $route->match($str, $query_string);
        if($match instanceof Angie_Router_Match) {
          self::$match = $match;
          return $match;
        } // if
      } // foreach
      
      throw new Angie_Router_Error_Match($str);
    } // match
    
    /**
    * Assemble URL
    *
    * This function will use route saved under the $name to form an URL based on provided array of params. If $absolute 
    * is true absolute URL will be return (PROJECT_URL variable must be set)
    * 
    * @param string $name
    * @param array $data
    * @param string $url_base
    * @param string $query_arg_separator
    * @return string
    * @throws Angie_Router_Error_Assemble
    */
    static function assemble($name, $data = array(), $url_base = '', $query_arg_separator = '&') {
      $route = array_var(self::$routes, $name);
      if(!($route instanceof Angie_Router_Route)) {
        throw new Angie_Core_Error_InvalidParamValue('name', $name, "Route '$name' is not mapped");
      } // if
      
      return $route->assemble($data, $url_base, $query_arg_separator);
    } // assemble
    
    // ---------------------------------------------------
    //  Utils
    // ---------------------------------------------------
    
    /**
    * Clean up router
    *
    * @param void
    * @return null
    */
    static function cleanUp() {
      self::$routes = array();
      self::$match = null;
    } // cleanUp
    
    /**
    * Return matched route, if route is matched
    *
    * @param void
    * @return Angie_Router_Route
    */
    static function getMatchedRoute() {
      if(self::$match instanceof Angie_Router_Match) {
        return self::$match->getRoute();
      } // if
      return null;
    } // getMatchedRoute
    
    /**
    * Return name of matched route, if route is matched
    *
    * @param void
    * @return string
    */
    static function getMatchedRouteName() {
      $route = self::getMatchedRoute();
      
      if($route instanceof Angie_Router_Route) {
        return $route->getName();
      } // if
      
      return null;
    } // getMatchedRouteName
  
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Returns array of mapped routes
    *
    * @param void
    * @return array
    */
    static function getRoutes() {
      return self::$routes;
    } // getRoutes
  
  } // Angie_Router

?>