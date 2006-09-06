<?php

  /**
  * Abstract engine - this class provides stub function and partial implementation 
  * of default behaviour. Purpose of engine is to tie rest of the system together - 
  * to know how to access controllers, how to build models, how to init application 
  * etc. Every Angie project can override default behaviour and implement things 
  * specific for that project without hacking the rest of the system
  *
  * @package Angie.engines
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_Engine {
    
    // ---------------------------------------------------
    //  Abstract functions
    // ---------------------------------------------------
  
    abstract function init();
    abstract function execute(Angie_Request $request);
    abstract function close();
    
  } // Angie_Engine

?>