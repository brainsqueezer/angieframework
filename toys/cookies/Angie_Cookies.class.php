<?php

  /**
  * Cookie interface
  * 
  * This class abstracts cookie management and provides a simple way to add 
  * cookie prefixes and so on
  *
  * @package Angie.toys
  * @subpackage cookies
  */
  class Angie_Cookies {
    
    /**
    * Prefix cookie variables
    * 
    * Prefix used for cookie variables
    *
    * @var string
    */
    static private $cookie_prefix;
    
    /**
    * Cookie path value
    *
    * @var string
    */
    static private $cookie_path = '/';
    
    /**
    * Cookie domain
    *
    * @var string
    */
    static private $cookie_domain = '';
    
    /**
    * Secure cookie
    *
    * @var integer
    */
    static private $cookie_secure = 0;
    
    /**
    * Return value from the cookien
    *
    * @param string $name Variable name
    * @param mixed $default
    * @return mixed
    */
    static function getValue($name, $default = null) {
      return array_var($_COOKIE, self::$cookie_prefix . $name, $default);
    } // getValue
  
    /**
    * Set cookie value
    *
    * @param string $name Variable name
    * @param mixed $value
    * @param integer $expiration Number of seconds from current time when this cookie need to expire
    * @return null
    */
    static function setValue($name, $value, $expiration = null) {
      $expiration_time = Angie_DateTime::now();
      if((integer) $expiration > 0) {
        $expiration_time->advance($expiration);
      } else {
        $expiration_time->advance(3600); // one hour
      } // if
      
      $path = defined('COOKIE_PATH') ? COOKIE_PATH : '/';
      $domain = defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '';
      $secure = defined('COOKIE_SECURE') ? COOKIE_SECURE : false;
      
      setcookie(self::$cookie_prefix . $name, $value, $expiration_time->getTimestamp(), self::$cookie_path, self::$cookie_domain, self::$cookie_secure);
    } // setValue
    
    /**
    * Unset specific cookie value
    *
    * @param string $name
    * @return null
    */
    static function unsetValue($name) {
      self::setValue($name, false);
    } // unsetValue
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Set cookie prefix
    *
    * @param string $value
    * @return null
    */
    function setCookiePrefix($value) {
      self::$cookie_prefix = $value;
    } // setCookiePrefix
    
    /**
    * Set cookie path
    *
    * @param string $value
    * @return null
    */
    function setCookiePath($value) {
      self::$cookie_path = $value;
    } // setCookiePath
    
    /**
    * Set cookie domain
    *
    * @param string $value
    * @return null
    */
    function setCookieDomain($value) {
      self::$cookie_domain = $value;
    } // setCookieDomain
    
    /**
    * Set cookie secure
    *
    * @param boolean $value
    * @return null
    */
    function setCookieSecure($value) {
      self::$cookie_secure = (integer) $value;
    } // setCookieSecure
  
  } // Angie_Cookies

?>