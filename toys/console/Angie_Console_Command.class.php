<?php

  /**
  * Console command
  *
  * @package Angie.toys
  * @subpackage console
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Console_Command {
    
    /**
    * Arguments extracted from the command
    *
    * @var array
    */
    private $arguments;
    
    /**
    * Command options extracted from the command
    *
    * @var array
    */
    private $options;
  
    /**
    * Constructor
    *
    * @param array $arguments
    * @param array $options
    * @return Angie_Console_Command
    */
    function __construct($arguments, $options) {
      $this->setArguments($arguments);
      $this->setOptions($options);
    } // __construct
    
    /**
    * Return argument on a specific possition
    *
    * @param integer $position
    * @return mixed
    */
    function getArgument($position) {
      return array_var($this->arguments, $position);
    } // getArgument
    
    /**
    * Return specific option
    * 
    * It is possible to use multiple option names - first name that is found will be returned. If option exists, but 
    * there is no additional argument TRUE will be returned. If argument is present for the given option its value will 
    * be returned. 
    * 
    * In case where option is no present FALSE is returend
    *
    * @param void
    * @return mixed
    */
    function getOption() {
      $names = func_get_args();
      if(is_foreachable($names)) {
        foreach($names as $name) {
          if(isset($this->options[$name])) {
            return $this->options[$name];
          } // if
        } // foreach
      } // if
      return false;
    } // getOption
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get arguments
    *
    * @param null
    * @return array
    */
    function getArguments() {
      return $this->arguments;
    } // getArguments
    
    /**
    * Set arguments value
    *
    * @param array $value
    * @return null
    */
    private function setArguments($value) {
      $this->arguments = $value;
    } // setArguments
    
    /**
    * Get options
    *
    * @param null
    * @return array
    */
    function getOptions() {
      return $this->options;
    } // getOptions
    
    /**
    * Set options value
    *
    * @param array $value
    * @return null
    */
    private function setOptions($value) {
      $this->options = $value;
    } // setOptions
  
  } // Angie_Console_Command

?>