<?php

  /**
  * Doctrine interface for Angie
  *
  * @package Angie.toys
  * @subpackage doctrine
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Doctrine {
  
    /**
    * Doctrine manager object
    *
    * @var Doctrine_Manager
    */
    static public $manager;
    
    /**
    * Init doctrine interface
    *
    * @param void
    * @return null
    */
    static function init() {
      self::$manager =  Doctrine_Manager::getInstance();
    } // init
    
    /**
    * Open a new connection
    *
    * @param Doctrine_Adapter $adapter
    * @param string $name
    * @return Doctrine_Connection
    */
    static function openConnection($adapter, $name = null) {
      return self::$manager->openConnection($adapter, $name);
    } // openConnection
    
    /**
    * Return table object with a given name
    * 
    * This function assumes that doctrine has a valid connection to the database
    *
    * @param string $name
    * @return Doctrine_Table
    */
    static function getTable($name) {
      return self::$manager->getCurrentConnection()->getTable($name);
    } // getTable
    
    /**
    * Return array of connection tables
    *
    * @param void
    * @return array
    */
    static function getTables() {
      return self::$manager->getCurrentConnection()->getTables();
    } // getTables
    
  } // Angie_Doctrine

?>