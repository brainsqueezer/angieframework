<?php

  class Angie_Doctrine_Record extends Doctrine_Record {
    
    /**
    * Returns true if we have new, unsaved record
    * 
    * This function will return true if this object is new and haven't been 
    * saved into database
    *
    * @param void
    * @return boolean
    */
    function isNew() {
      $state = $this->getState();
      return $state == self::STATE_TCLEAN || $state == self::STATE_TDIRTY;
    } // isNew
    
    /**
    * Returns true if this object is loaded from database
    *
    * @param void
    * @return boolean
    */
    function isLoaded() {
      return !$this->isNew();
    } // isLoaded
  
  } // Angie_Doctrine_Record

?>