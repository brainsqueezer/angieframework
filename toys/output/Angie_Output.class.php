<?php

  /**
  * Angie output
  * 
  * This class is used in various tools that can be triggered through various inputs - 
  * console, browser, web service. Based on the input system will construct proper output 
  * so it will always return properly formated messages (simple print for console, HTML for 
  * browser etc)
  *
  * @package Angie.toys
  * @subpackage output
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_Output {
  
    /**
    * Print message to the output
    * 
    * To render one useful error message just execute:
    * <pre>
    * $output->printMessage('Something went wrong!', 'Fatal error');
    * </pre>
    *
    * @param string $message
    * @param string $type
    * @return null
    */
    abstract function printMessage($message, $type = null);
  
  } // Angie_Output

?>