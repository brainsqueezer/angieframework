<?php

  class BaseTestController extends Angie_Controller {
  
    function __construct($protect_self = false) {
      parent::__construct();
      if($protect_self) {
        $this->setProtectClassMethods(get_class($this));
      } // if
    } // __construct
    
    function invisible() {
      
    } // invisible
    
  } // BaseTestController

?>