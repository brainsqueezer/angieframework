<?php

  /**
  * Angie extension of exceptions with methods to extract and display additional data
  * about errors through error specific params
  *
  * @package Angie.core
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Error extends Exception {
    
    /**
    * Return error params (name -> value pairs). General params are file and line
    * and any specific error have their own params...
    *
    * @access public
    * @param void
    * @return array
    */
    function getParams() {
      $base = array(
        'file' => $this->getFile(),
        'line' => $this->getLine()
      ); // array
      
      $additional = $this->getAdditionalParams();
      
      return is_array($additional) ? array_merge($base, $additional) : $base;
    } // getParams
    
    /**
    * Return additional error params
    *
    * @param void
    * @return array
    */
    function getAdditionalParams() {
      return null;
    } // getAdditionalParams
  
  } // Angie_Error

?>