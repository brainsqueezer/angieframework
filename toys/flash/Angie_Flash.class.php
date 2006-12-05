<?php

  /**
  * Flash service
  *
  * Purpose of this service is to make some data available across pages. Flash
  * data is available on the next page but deleted when execution reach its end.
  *
  * Usual use of Flash is to make possible that current page pass some data
  * to the next one (for instance success or error message before HTTP redirect).
  *
  * Flash service as a concep is taken from Rails
  *
  * @package Angie.toys
  * @subpackage flash
  */
  class Angie_Flash {
  
    /**
    * Data that prevous page left in the Flash
    *
    * @var array
    */
    private static $previous = array();
    
    /**
    * Data that current page is saving for the next page
    *
    * @var array
    */
    private static $next = array();
    
    /**
    * Init flash
    *
    * @param void
    * @return null
    */
    static function init() {
      self::readFlash();
    } // init
    
    /**
    * Return specific variable from the flash. 
    * 
    * If value is not found $default is returned
    *
    * @param string $var
    * @param mixed $default
    * @return mixed
    */
    static function getVariable($var, $default = null) {
      return isset(self::$previous[$var]) ? self::$previous[$var] : $default;
    } // getVariable
    
    /**
    * Add specific variable to the flash
    * 
    * This variable will be available on the next page unlease removed with the 
    * removeVariable() or clear() method
    *
    * @param string $var
    * @param mixed $value
    * @return void
    */
    static function addVariable($var, $value) {
      self::$next[$var] = $value;
      self::writeFlash();
    } // addVariable
    
    /**
    * Remove specific variable for the Flash
    *
    * @param string $var Name of the variable that need to be removed
    * @return void
    */
    static function removeVariable($var) {
      if(isset(self::$next[$var])) {
        unset(self::$next[$var]);
      } // if
      self::writeFlash();
    } // removeVariable
    
    /**
    * Clear flash data
    * 
    * Note that data that previous page stored will not be deleted - just the 
    * data that this page saved for the next page
    *
    * @access public
    * @param void
    * @return void
    */
    static function clear() {
      self::$next = array();
    } // cleare
    
    /**
    * Read flash
    * 
    * This function will read flash data from the $_SESSION variable and load it 
    * into self::$previous array. When loaded into the object data is removed 
    * from the session
    *
    * @param void
    * @return void
    */
    static private function readFlash() {
      $flash_data = array_var($_SESSION, 'flash_data');
      
      if(!is_null($flash_data)) {
        if(is_array($flash_data)) {
          self::$previous = $flash_data;
        } // if
        unset($_SESSION['flash_data']);
      } // if
    } // readFlash
    
    /**
    * Save content of the self::$next array into the $_SESSION autoglobal var
    *
    * @param void
    * @return void
    */
    private static function writeFlash() {
      $_SESSION['flash_data'] = self::$next;
    } // writeFlash
    
  } // end class Flash

?>