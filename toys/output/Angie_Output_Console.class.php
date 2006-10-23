<?php

  /**
  * Console output object
  * 
  * This object will render message to the console
  *
  * @package Angie.toys
  * @subpackage output
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Output_Console extends Angie_Output {
  
    /**
    * Print message to the console
    *
    * @param string $message
    * @param string $type
    * @return null
    */
    function printMessage($message, $type = null) {
      print trim($type) == '' ? $message . "\n" : "$type: $message\n";
    } // printMessage
  
  } // Angie_Output_Console

?>