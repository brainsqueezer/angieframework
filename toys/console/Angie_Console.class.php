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
    * Return populated console command handler
    *
    * @param Angie_Console_Command $command_handler
    * @param array $from_arguments
    * @param array $short_options
    * @param array $long_options
    * @return Angie_Console_Command
    * @throws Angie_Core_Error_InvalidParamValue
    */
    static function prepareCommand($command_handler = null, $from_arguments = null, $short_options = null, $long_options = null) {
      if(is_null($command_handler)) {
        $command_handler = new Angie_Console_Command();
      } // if
      
      if(!($command_handler instanceof Angie_Console_Command)) {
        throw new Angie_Core_Error_InvalidParamValue('command_class', $command_class, "Command class '$command_class' does not inherit Angie_Console_Command");
      } // if
      
      // If we are missing option definitions, but we have an executable command than we can extract defintiions from 
      // the handler instance...
      if((is_null($short_options) || is_null($long_options)) && ($command_handler instanceof Angie_Console_ExecutableCommand)) {
        $handlers_options = $command_handler->defineOptions();
        
        $handlers_short_options = array();
        $handlers_long_options = array();
        
        if(is_foreachable($handlers_options)) {
          foreach($handlers_options as $handlers_option) {
            list($short, $long, $help) = $handlers_option;
            if($short) {
              $handlers_short_options[] = $short;
            } // if
            if($long) {
              $handlers_long_options[] = $long;
            } // if
          } // foreach
        } // if
        
        if(is_null($short_options)) {
          $short_options = $handlers_short_options;
        } // if
        
        if(is_null($long_options)) {
          $long_options = $handlers_long_options;
        } // if
      } // if
      
      $process = self::processCommand($from_arguments, $short_options, $long_options);
      
      $command_handler->setArguments($process['arguments']);
      $command_handler->setOptions($process['options']);
      
      return $command_handler;
    } // prepareCommand
    
    /**
    * Extract data from command line argumnets
    * 
    * $arguments is an array of console arguments. If NULL it will be read using readArgv() function
    * 
    * The $options parameter may contain the following elements: individual characters, and characters followed by a 
    * colon to indicate an option argument is to follow. For example, an option string x recognizes an option -x, and an 
    * option string x: recognizes an option and argument -x argument. It does not matter if an argument has leading 
    * white space.
    * 
    * As a result this function returns an array where first element is a set of non-option arguments and the second one 
    * is the array of options
    *
    * @param array $arguments
    * @param array $short_options
    * @param array $long_options
    * @return array
    * @throws Angie_Console_Error_ArgumentRequired
    * @throws Angie_Console_Error_UnknownOption
    */
    static private function processCommand($from_arguments = null, $short_options = null, $long_options = null) {
      if(is_null($from_arguments)) {
        $arguments = self::readArgv();
      } else {
        $arguments = (array) $from_arguments;
      } // if
      
      if(!is_array($short_options)) {
        $short_options = array();
      } // if
      
      if(!is_array($long_options)) {
        $long_options = array();
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
                $options[$option_name] = self::processValue($option_value);
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
              $options[$option] = self::processValue(array_var($arguments, $k + 1, true));
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
          $non_options[] = self::processValue($argument);
        } // if
      } // foreach
      
      return array(
        'arguments' => $non_options,
        'options'   => $options,
      ); // array
    } // processCommand
    
    /**
    * Process single value
    * 
    * This function is called to process a single value (any value that is not 
    * a short or long option name)
    *
    * @param string
    * @return mixed
    */
    static function processValue($value) {
      if(is_string($value)) {
        if(str_starts_with($value, '[') && str_ends_with($value, ']')) {
          return explode(',', substr($value, 1, strlen($value) - 2));
        } else {
          return $value;
        } // if
      } else {
        return $value;
      } // if
    } // processValue
  
   
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