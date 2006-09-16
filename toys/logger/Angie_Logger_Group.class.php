<?php

  /**
  * Logger group
  * 
  * This class groups related log messages (for instance, there could be gorups such as 'system', 'mysql', 
  * 'rendering' etc)
  *
  * @package Angie.toys
  * @subpackage logger
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Logger_Group {
  
    /**
    * Name of the group
    *
    * @var string
    */
    private $name;
    
    /**
    * Minimal severity that will be logged. In debug mode it would be Angie_Logger::DEBUG, in 
    * production it should be Angie_Logger::FATAL
    *
    * @var integer
    */
    private $min_severity;
    
    /**
    * Time when this groups has been constructed
    *
    * @var float
    */
    private $created_on;
    
    /**
    * Array of log entries
    *
    * @var array
    */
    private $entries = array();
    
    /**
    * Constructor
    *
    * @param string $name
    * @param integer $severity
    * @return Angie_Logger_Group
    */
    function __construct($name = Angie_Logger::DEFAULT_GROUP_NAME, $severity = Angie_Logger::DEBUG) {
      $this->setName($name);
      $this->setMinSeverity($severity);
      $this->setCreatedOn(microtime(true));
    } // __construct
    
    /**
    * Return true if this session is empty
    *
    * @param void
    * @return boolean
    */
    function isEmpty() {
      return count($this->entries) < 1;
    } // isEmpty
    
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
    * Get min_severity
    *
    * @param null
    * @return integer
    */
    function getMinSeverity() {
      return $this->min_severity;
    } // getMinSeverity
    
    /**
    * Set min_severity value
    *
    * @param integer $value
    * @return null
    */
    function setMinSeverity($value) {
      $this->min_severity = $value;
    } // setMinSeverity
    
    /**
    * Get created_on
    *
    * @param null
    * @return float
    */
    function getCreatedOn() {
      return $this->created_on;
    } // getCreatedOn
    
    /**
    * Set created_on value
    *
    * @param float $value
    * @return null
    */
    private function setCreatedOn($value) {
      $this->created_on = $value;
    } // setCreatedOn
    
    /**
    * Return entries
    *
    * @param void
    * @return array
    */
    function getEntries() {
      return $this->entries;
    } // getEntries
    
    /**
    * Add entry to the group
    *
    * @param Angie_Logger_Entry $entry
    * @return Angie_Logger_Entry
    */
    function addEntry(Angie_Logger_Entry $entry) {
      if($entry->getSeverity() >= $this->getMinSeverity()) {
        $this->entries[] = $entry;
        return $entry;
      } // if
    } // addEntry
  
  } // Angie_Logger_Group

?>