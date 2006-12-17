<?php

  /**
  * Angie extension of exceptions 
  * 
  * Angie exception is regular exception with additional functions that let developer define per exception specific data
  *
  * @package Angie.core
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Error extends Exception {
    
    /**
    * Return error params (name -> value pairs)
    * 
    * General params are file and line and any specific error have their own 
    * params (returned through getAdditionalParams() method)...
    *
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