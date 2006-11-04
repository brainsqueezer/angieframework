<?php

  /**
  * Console interface
  * 
  * This class is used for easy parsing of CLI (Command Line Interface) requests and optaining data from CLI. As a 
  * result of processing this class returns Angie_Console_Command instance than be used for interaction with arguments 
  * and options of the input command.
  *
  * @package Angie.toys
  * @subpackage console
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Console {
  
    /**
    * Return command based on input arguments and options string
    * 
    * $arguments is an array of console arguments. If NULL it will be read using readArgv() function
    * 
    * The $options parameter may contain the following elements: individual characters, and characters followed by a 
    * colon to indicate an option argument is to follow. For example, an option string x recognizes an option -x, and an 
    * option string x: recognizes an option and argument -x argument. It does not matter if an argument has leading 
    * white space.
    *
    * @param array $arguments
    * @param array $short_options
    * @param array $long_options
    * @return Angie_Console_Command
    */
    static function prepareCommand($from_arguments = null, $short_options = array(), $long_options = array()) {
      if(is_null($from_arguments)) {
        $arguments = self::readArgv();
      } else {
        $arguments = (array) $from_arguments;
      } // if
      
      $options = array();
      $non_options = array();
      
      $skip_next = false;
      foreach($arguments as $k => $argument) {
        if($skip_next) {
          $skip_next = false;
          continue;
        } // if
        
        // Long
        if(str_starts_with($argument, '--')) {
          $option = substr($argument, 2);
          
          // Present, but without arguments
          if(in_array($option, $long_options)) {
            $options[$option] = true;
          
          } else {
            
            // Requires an argument
            if(($pos = strpos($option, '=')) !== false) {
              list($option_name, $option_value) = explode('=', $option);
              if(in_array($option_name . ':', $long_options)) {
                $options[$option_name] = $option_value;
              } else {
                throw new Angie_Console_Error_ArgumentRequired($option);
              } // if
              
            // Unknown option
            } else {
              throw new Angie_Console_Error_UnknownOption($option);
            } // if
          } // if
          
        // Short
        } elseif(str_starts_with($argument, '-')) {
          $option = substr($argument, 1);
          
          // Present, but without arguments
          if(in_array($option, $short_options)) {
            $options[$option] = true;
            
          // Requires an argument
          } elseif(in_array($option . ':', $short_options)) {
            if(isset($arguments[$k + 1])) {
              $options[$option] = array_var($arguments, $k + 1, true);
              $skip_next = true;
            } else {
              throw new Angie_Console_Error_ArgumentRequired($option);
            } // if
            
          // Unknown option
          } else {
            throw new Angie_Console_Error_UnknownOption($option);
          } // if
          
        // Argument
        } else {
          $non_options[] = $argument;
        } // if
      } // foreach
      
      return new Angie_Console_Command($non_options, $options);
    } // prepareCommand
    
    /**
    * Safely read the $argv PHP array across different PHP configurations
    * .
    * Will take care on register_globals and register_argc_argv ini directives
    *
    * @param void
    * @return mixed the $argv
    * @throws Angie_Getopt_Error_ReadArguments
    */
    static function readArgv() {
      global $argv;
      if(!is_array($argv)) {
        if(!@is_array($_SERVER['argv'])) {
          if(!@is_array($GLOBALS['HTTP_SERVER_VARS']['argv'])) {
            throw new Angie_Console_Error_ReadArguments();
          } // if
          return $GLOBALS['HTTP_SERVER_VARS']['argv'];
        } // if
        return $_SERVER['argv'];
      } // if
      return $argv;
    } // readArgv
  
  } // Angie_Console

?>