<?php

  /**
  * Logger backend interface
  * 
  * Logger backend interface need to be implemented by every logger backend in order to 
  * be pluggined into the Logger. It provide set of methods for saving single or multople
  * logger groups into a persistant storage (file, database, web service etc)
  *
  * @package Angie.toys
  * @subpackage logger
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  interface Angie_Logger_Backend {
    
    /**
    * Save set of groups into a storage
    * 
    * Save array of group into a single group set
    *
    * @param array $groups
    * @return boolean
    */
    public function saveGroupSet($groups);
    
    /**
    * Save single group into a storage
    * 
    * This function will write logger group into the persistant storage (file, database, 
    * send an email etc)
    *
    * @param Angie_Logger_Group $group
    * @return boolean
    */
    public function saveGroup(Angie_Logger_Group $group);
    
  } // Angie_Logger_Backend

?>