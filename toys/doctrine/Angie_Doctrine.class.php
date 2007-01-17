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
    * Reference to Doctrine manager instance
    *
    * @var Doctrine_Manager
    */
    static private $manager = null;
    
    /**
    * Return Doctrine_Connection for a given connection name
    *
    * @param string $connection
    * @return Doctrine_Connection
    */
    function connection($connection) {
      if(self::$manager === null) {
        self::$manager = Doctrine_Manager::getInstance();
      } // if
      return $manager->getConnection($connection);
    } // connection
  
    /**
    * Return a Doctrine_Table instance for a given component
    *
    * @param string $component
    * @param string $connection
    * @return Doctrine_Table
    */
    static function table($component, $connection = null) {
      if(self::$manager === null) {
        self::$manager = Doctrine_Manager::getInstance();
      } // if
      return self::$manager->getTable($component);
    } // table
    
    /**
    * Return query object for a given component
    *
    * @param string $component
    * @param string $connection
    * @return Doctrine_Query
    */
    static function query($component, $connection = null) {
      if(self::$manager === null) {
        self::$manager = Doctrine_Manager::getInstance();
      } // if
      return self::$manager->getTable($component)->createQuery();
    } // query
    
  } // Angie_Doctrine

?>