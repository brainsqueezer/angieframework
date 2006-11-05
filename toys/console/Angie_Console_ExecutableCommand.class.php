<?php

  /**
  * Executable console command
  * 
  * Executable commands inherit console command options extraction and add methods that describe the command and make 
  * it executable. It is used as base for most of Angie console commands because it provides methods that make listing 
  * and implementing commands run through console pretty easy:
  * 
  * - defineOptions() - Returns the array of possible command options with relations and short option help
  * - defineDescription() - Returns the desciription of the command and is usually used when we need to list more 
  *   commands at once with a short description
  * - defineHelp() - Returns command help that is rendered on request. By default help will be made out of description 
  *   and options
  * - execute() - Called in order to execute the command. Output object is provided so command can notify user on 
  *   progress
  *
  * @package Angie.toys
  * @subpackage console
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_Console_ExecutableCommand extends Angie_Console_Command {
  
    /**
    * Execute the command
    * 
    * Trigger this function to exeucte the command based on the input arguments
    *
    * @param Angie_Output $output
    * @return null
    */
    abstract function execute(Angie_Output $output);
    
    /**
    * Return options definition array
    * 
    * Single element in options definition array consists of three elements. First element is a short option (one letter 
    * plus optional colon saying that this option requires an argument), long option name with option colon and help
    *
    * @param void
    * @return array
    */
    abstract function defineOptions();
    
    /**
    * Return command description
    *
    * @param void
    * @return string
    */
    abstract function defineDescription();
    
    /**
    * Return help string for this option
    * 
    * This function automatically creates a help for the command based on the description and the list of given options. 
    * Override this method in childclasses to override the default behavior
    *
    * @param void
    * @return string
    */
    function defineHelp() {
      $result = $this->defineDescription() . "\n\nOptions:\n\n";
      $options = $this->defineOptions();
      
      if(is_array($options)) {
        $longest_long = 0;
        foreach($options as $option) {
          $long = $option[1];
          if($long && (strlen($long) > $longest_long)) {
            $longest_long = strlen($long);
          } // if
        } // foreach
        
        foreach($options as $option) {
          if(!is_array($option)) {
            $result .= "Invalid option... Skipped\n";
            continue;
          } // if
          
          list($short, $long, $help) = $option;
          
          if($short) {
            $result .= "  -$short, ";
          } else {
            $result .= '      ';
          } // if
          
          if($long) {
            $result .= "--$long";
          } // if
          
          for($i = strlen($long); $i < ($longest_long + 4); $i++) {
            $result .= ' ';
          } // for
          
          $result .= $help . "\n";
        } // foeach
      } // if
      
      return $result;
    } // defineHelp
  
  } // Angie_Console_ExecutableCommand

?>