<?php

  /**
  * Small logging library
  * 
  * This logger library supports logging messages into multiple groups and saving them into
  * multiple backends (files, database etc).
  * 
  * To log a message into a default group use:
  * <pre>
  * Angie_Logger::log('message', Angie_Logger::DEBUG);
  * </pre>
  * 
  * To log a message into a specific group use:
  * <pre>
  * Angie_Logger::log('message', Angie_Logger::DEBUG, 'group_name');
  * </pre>
  * 
  * Group 'group_name' needs to be added to the logger before you can use it that way:
  * <pre>
  * Angie_Logger::setGroup(new Angie_Logger_Group(), 'group_name');
  * </pre>
  * 
  * @package Angie.toys
  * @subpackage logger
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Logger {
    
    /**
    *  Default group name
    */
    const DEFAULT_GROUP_NAME = 'default';
    
    /**
    * Severity
    */
    const DEBUG   = 0;
    const INFO    = 1;
    const WARNING = 2;
    const ERROR   = 3;
    const FATAL   = 4;
    const UNKNOWN = 5;
  
    /**
    * Default backend
    *
    * @var Angie_Logger_Backend
    */
    static private $default_backend;
    
    /**
    * Array of additional, named backends
    *
    * @var array
    */
    static private $additional_backends = array();
    
    /**
    * Default logger group
    *
    * @var Angie_Logger_Group
    */
    static private $default_group;
    
    /**
    * Array of additional group instances indexed by name
    *
    * @var array
    */
    static private $additional_groups = array();
    
    /**
    * Logger is enabled
    *
    * @var boolean
    */
    static private $enabled = true;
    
    // ---------------------------------------------------
    //  Utils
    // ---------------------------------------------------
    
    /**
    * Log a message
    * 
    * This function will add $message to the specific group (if $group_name is NULL default group will 
    * be used). If $message is an exception it will be converted to string using its __toString() method.
    * 
    * When done this function will return an instance of new logger entry
    *
    * @param string $message
    * @param integer $severity
    * @param string $group_name
    * @return Angie_Logger_Entry
    * @throws Angie_Core_Error_InvalidParamValue If we don't get session by $session_name
    */
    static function log($message, $severity = Angie_Logger::DEBUG, $group_name = null) {
      if(!self::$enabled) {
        return false;
      } // if
      
      if($message instanceof Exception) {
        $message_to_log = $message->__toString();
      } else {
        $message_to_log = (string) $message;
      } // if
      
      $group = self::getGroup($group_name);
      if(!($group instanceof Angie_Logger_Group)) {
        throw new Angie_Core_Error_InvalidParamValue('group_name', $group_name, "There is no group matching this name (null for default group): " . var_export($group_name, true));
      } // if
      
      return $group->addEntry(new Angie_Logger_Entry($message_to_log, $severity));
    } // log
    
    /**
    * Seve single group into specific backend
    *
    * @param string $group_name
    * @param string $backend_name
    * @return boolean
    * @throws Angie_Core_Error_InvalidParamValue If session $group_name does not exists
    * @throws Angie_Core_Error_InvalidParamValue If backend $backend_name does not exists
    */
    static function saveGroup($group_name = null, $backend_name = null) {
      if(!self::$enabled) {
        return false;
      } // if
      
      $group = self::getGroup($group_name);
      if(!($group instanceof Angie_Logger_Group)) {
        throw new Angie_Core_Error_InvalidParamValue('group_name', $group_name, "There is no group matching this name (null for default group): " . var_export($group_name, true));
      } // if
      
      $backend = self::getBackend($backend_name);
      if(!($backend instanceof Angie_Logger_Backend)) {
        throw new Angie_Core_Error_InvalidParamValue('backend_name', $backend_name, 'There is no backend matching this name (null for default backend): ' . var_export($session_name, true));
      } // if
      
      return $backend->saveGroup($group);
    } // saveGroup
    
    /**
    * Save all groups into a specific backend
    *
    * @param string $backend_name Backend name, NULL for default
    * @return boolean
    * @throws Angie_Core_Error_InvalidParamValue If backedn $backend_name does not exists
    */
    static function saveAll($backend_name = null) {
      if(!self::$enabled) {
        return false;
      } // if
      
      $backend = self::getBackend($backend_name);
      if(!($backend instanceof Angie_Logger_Backend)) {
        throw new Angie_Core_Error_InvalidParamValue('backend_name', $backend_name, 'There is no backend matching this name (null for default backend): ' . var_export($session_name, true));
      } // if
      
      return $backend->saveGroupSet(self::getAllGroups());
    } // saveAll
    
    /**
    * Convert sverity to string. If $severity is not recognized UNKNOWN is returned
    *
    * @param integer $severity
    * @return string
    */
    static function severityToString($severity) {
      switch($severity) {
        case Angie_Logger::DEBUG:
          return 'DEBUG';
        case Angie_Logger::INFO:
          return 'INFO';
        case Angie_Logger::WARNING:
          return 'WARNING';
        case Angie_Logger::ERROR:
          return 'ERROR';
        case Angie_Logger::FATAL:
          return 'FATAL';
        default:
          return 'UNKNOWN';
      } // switch
    } // severityToString
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Return specific backend. If $name is not specific default backend will be returned
    *
    * @param string $name Backend name, leave blank to use default backend
    * @return Angie_Logger_Backend
    */
    static function getBackend($name = null) {
      return is_null($name) ? self::$default_backend : array_var(self::$additional_backends, $name);
    } // getBackend
    
    /**
    * Set backend. If $name is NULL default backend will be set
    *
    * @param Angie_Logger_Backend $backend
    * @param string $name Leave blank to set default backend
    * @return Angie_Logger_Backend
    */
    static function setBackend(Angie_Logger_Backend $backend, $name = null) {
      if(is_null($name)) {
        self::$default_backend = $backend;
      } else {
        self::$additional_backends[$name] = $backend;
      } // if
      return $backend;
    } // setBackend
    
    /**
    * Return group by $group_name
    * 
    * If $group_name is NULL default group will be returned. Else function will try to find it in
    * $additional_groups array
    *
    * @param string $group_name
    * @return Angie_Logger_Group
    */
    static function getGroup($group_name = null) {
      return is_null($group_name) ? self::$default_group : array_var(self::$additional_groups, $group_name);
    } // getGroup
    
    /**
    * Set group value
    * 
    * If $name is null $group will be set as default logger group. Else it will be set under the given
    * group name in $additional_groups array
    *
    * @param Angie_Logger_Group $group
    * @param string $name
    * @return $group
    */
    static function setGroup(Angie_Logger_Group $group, $name = null) {
      if(is_null($name)) {
        self::$default_group = $group;
      } else {
        $group->setName($name);
        self::$additional_groups[$name] = $group;
      } // if
      return $group;
    } // setGroup
    
    /**
    * Return all logger groups
    * 
    * This function will return all logger groups - default one + additional groups if available
    *
    * @param void
    * @return array
    */
    static function getAllGroups() {
      $result = array();
      
      if(self::$default_group instanceof Angie_Logger_Group) {
        $result[] = self::$default_group;
      } // if
      
      if(count(self::$additional_groups)) {
        return array_merge($result, self::$additional_groups);
      } else {
        return $result;
      } // if
    } // getAllGroups
    
    /**
    * Get enabled
    *
    * @param null
    * @return boolean
    */
    static function getEnabled() {
      return self::$enabled;
    } // getEnabled
    
    /**
    * Set enabled value
    *
    * @param boolean $value
    * @return null
    */
    static function setEnabled($value) {
      self::$enabled = (boolean) $value;
    } // setEnabled
  
  } // Logger

?>