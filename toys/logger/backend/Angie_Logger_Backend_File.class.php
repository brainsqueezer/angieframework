<?php

  /**
  * Logger backand that is able to save session data into a text file
  *
  * @package Angie.toys
  * @subpackage logger.backend
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Logger_Backend_File implements Angie_Logger_Backend {
    
    /** 
    * New line
    */
    const NEW_LINE = "\r\n";
    
    /**
    * This separator is used to devide major groups of information in a log file
    */
    const SET_SEPARATOR = '===============================================================================';
    
    /**
    * This separator is used to devide minor groups of information in a log file
    */
    const GROUP_SEPARATOR = '-------------------------------------------------------------------------------';
  
    /**
    * Path of the log file
    * 
    * If file exists it need to be writable, if not parent folder need to exist and be writable
    *
    * @var string
    */
    private $log_file;
    
    /**
    * Constructor
    * 
    * On construction system need to provide a valid log file path - writable file that exists
    * or writable parent folder if file does not exist
    *
    * @param string $log_file
    * @return Angie_Logger_Backend_File
    */
    function __construct($log_file) {
      $this->setLogFile($log_file);
    } // __construct
    
    // ---------------------------------------------------
    //  Backend interface implementation and utils
    // ---------------------------------------------------
    
    /**
    * Save array of sessions into a single session set
    *
    * @param array $groups
    * @return boolean
    */
    public function saveGroupSet($groups) {
      if(!is_array($groups)) {
        return false;
      } // if
      
      $group_names = array();
      $group_outputs = array();
      foreach($groups as $group) {
        if($group instanceof Angie_logger_Group) {
          $group_names[] = $group->getName();
          $group_outputs[] = $this->renderGroupContent($group);
        } // if
      } // if
      
      if(!count($group_names) || !count($group_outputs)) {
        return false;
      } // if
      
      $output = self::SET_SEPARATOR . self::NEW_LINE . 'Group set: ' . implode(', ', $group_names) . self::NEW_LINE . self::SET_SEPARATOR;
      foreach($group_outputs as $group_output) {
        $output .= self::NEW_LINE . $group_output;
      } // foreach
      
      $output .= self::NEW_LINE . self::SET_SEPARATOR;
      return file_put_contents($this->getLogFile(), self::NEW_LINE . $output . self::NEW_LINE, FILE_APPEND);
    } // saveSessionSet
    
    /**
    * Save session object into the file
    *
    * @param Angie_Logger_Group $group
    * @return boolean
    */
    public function saveGroup(Angie_Logger_Group $group) {
      $output = $this->renderGroupContent($group);
      return file_put_contents($this->getLogFile(), self::NEW_LINE . $output . self::NEW_LINE, FILE_APPEND);
    } // saveGroupSet
    
    /**
    * Prepare session output as string
    *
    * @param Angie_Logger_Group $group
    * @return string
    */
    private function renderGroupContent(Angie_Logger_Group $group) {
      $group_executed_in = microtime(true) - $group->getCreatedOn();
      $counter = 0;
      
      $output = 'Group "' . $group->getName() . '" started at ' . date(DATE_ISO8601, floor($group->getCreatedOn())) . "\n";
      if($group->isEmpty()) {
        $output .= 'Empty group';
      } else {
        foreach($group->getEntries() as $entry) {
          $counter++;
          $output .= "#$counter " . Angie_Logger::severityToString($entry->getSeverity()) . ': ' . $entry->getFormattedMessage('    ') . "\n";
        } // foreach
      } // if
      
      $output .= "Time since start: " . $session_executed_in . " seconds\n" . self::GROUP_SEPARATOR;
      return str_replace("\n", self::NEW_LINE, $output);
    } // renderSessionContent
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get log_file
    *
    * @param null
    * @return string
    */
    function getLogFile() {
      return $this->log_file;
    } // getLogFile
    
    /**
    * Set log_file value
    *
    * @param string $value
    * @return null
    * @throws Angie_FileSystem_Error_FileNotWritable If file exists and is not writable
    * @throws Angie_FileSystem_Error_DirDnx If file does not exists and parent directory does not exists
    * @throws Angie_FileSystem_Error_DirNotWritable If file does not exists, but parent exists and is not writable
    */
    function setLogFile($value) {
      $file_path = $value;
      if(is_file($file_path)) {
        if(!file_is_writable($file_path)) {
          throw new Angie_FileSystem_Error_FileNotWritable($file_path);
        } // if
      } else {
        $folder_path = dirname($file_path);
        if(!is_dir($folder_path)) {
          throw new Angie_FileSystem_Error_DirDnx($folder_path);
        } // if
        if(!folder_is_writable($folder_path)) {
          throw new Angie_FileSystem_Error_DirNotWritable($folder_path);
        } // if
      } // if
      $this->log_file = $value;
    } // setLogFile
  
  } // Angie_Logger_Backend_File

?>