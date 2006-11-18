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
    * Name of the matched route
    *
    * @var string
    */
    private static $matched_route_name;
    
    /**
    * Instance of matched route
    *
    * @var Angie_Router_Route
    */
    private static $matched_route;
    
    /**
    * Map route
    * 
    * This function will create a new route based on route string, default values and additional requirements and save 
    * it under specific name. Name is used so you can access the route when assembling URL based on data
    *
    * @param string $name
    * @return null
    */
    static function map($route, $defaults = null, $requirements = null, $name = null) {
      $route = new Angie_Router_Route($route, $defaults, $requirements);
      if($name) {
        self::$routes[$name] = $route;
      } else {
        self::$routes[] = $route;
      } // if
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
        $values = $route->match($str, $query_string);
        if($values !== false) {
          self::$matched_route_name = $route_name;
          self::$matched_route = $route;
          
          return $values;
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
    * @param boolean $absolute
    * @return string
    * @throws Angie_Router_Error_Assemble
    */
//    static function assemble($name, $data, $absolute = true) {
//      $route = array_var(self::$routes, $name);
//      if(!($route instanceof Angie_Router_Route)) {
//        throw new Angie_Core_Error_InvalidParamValue('name', $name, "Route '$name' is not mapped");
//      } // if
//      
//      return $route->assemble($data, $absolute);
//    } // assemble
  
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Returns array of mapped routes
    *
    * @param void
    * @return array
    */
    function getRoutes() {
      return self::$routes;
    } // getRoutes
    
    /**
    * Return name of the matched route
    *
    * @param void
    * @return string
    */
    function getMatchedRoute() {
      return self::$matched_route_name;
    } // getMatchedRoute
  
  } // Angie_Router

?>