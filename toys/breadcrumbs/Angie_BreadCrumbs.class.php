<?php

  /**
  * Collection of breadcrumbs that can be accessed globaly (static class)
  *
  * @package Angie.toys
  * @subpackage breadcrumbs
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  final class Angie_BreadCrumbs {
  
    /**
    * Array of crumbs
    *
    * @var array
    */
    static private $crumbs;
    
    /**
    * This function is used to add bread crumbs based on params passed to the function
    *
    * @param array $args Params collecected by func_get_args() in some function
    * @return null
    */
    static function addByFunctionArguments($args) {
      
      // First param is array, add multiple crumbs
      if(is_array($args[0])) {
        
        foreach($args[0] as $arg) {
          if(is_array($arg)) {
            $crumb = self::getCrumbFromArray($arg);
            if($crumb instanceof Angie_BreadCrumb) {
              self::addCrumb($crumb);
            } // if
          } elseif(is_string($arg)) {
            self::addCrumb($arg);
          } // if
        } // foreach
        
      // First param is valid crumb instance
      } elseif($args[0] instanceof Angie_BreadCrumb) {
        self::addCrumb($args[0]);
        
      // First param is string, add a single crumb
      } elseif(is_string($args[0])) {
        $crumb = self::getCrumbFromArray($args);
        if($crumb instanceof Angie_BreadCrumb) {
          self::addCrumb($title, $url, $attributes);
        } // if
      } // if
      
    } // addByFunctionArguments
    
    /**
    * This function will create a new crumb from an array that is usualy an input for most of 
    * breadcrumb function. It will extract title, URL and attributres, and if required data is 
    * present (title) than it will return new bread crumb object
    *
    * @param array $input_array
    * @return Angie_BreadCrumb
    */
    private static function getCrumbFromArray($input_array) {
      $title      = array_var($input_array, 0, false);
      $url        = array_var($input_array, 1, null);
      $attributes = array_var($input_array, 2, null);
      
      return trim($title) == '' ? null : new Angie_BreadCrumb($title, $url, $attributes);
    } // getCrumbFromArray
    
    // ---------------------------------------------------
    //  Getters and seters
    // ---------------------------------------------------
    
    /**
    * Return all crumbs
    *
    * @access public
    * @param void
    * @return array
    */
    function getCrumbs() {
      return self::$crumbs;
    } // getCrumbs
    
    /**
    * Add a single crumb. Two sets of params are possible:
    * 
    * 1. First (and only) param is Angie_BreadCrumb object that need to be added
    * 2. Three params - title, url and additional attributes. Only title is required
    *
    * @param void
    * @return Angie_BreadCrumb
    */
    static function addCrumb() {
      $args = func_get_args();
      if(!is_array($args) || !count($args)) {
        return null;
      } // if
      
      if(array_var($args, 0) instanceof Angie_BreadCrumb) {
        $crumb = array_var($args, 0);
      } else {
        $crumb = self::getCrumbFromArray($args);
      } // if
      
      if($crumb instanceof Angie_BreadCrumb) {
        self::$crumbs[] = $crumb;
        return $crumb;
      } else {
        return false;
      } // if
    } // addCrumb
    
  } // Angie_BreadCrumbs

?>