<?php

  interface Angie_Persistable_Manager {
    
    static public function findById($id);
    static public function find($arguments);
    static public function findOne($arguments);
    static public function findAll($arguments);
    static public function count($conditions);
    static public function delete($conditions);
    static public function paginate($arguments, $items_per_page = 10, $current_page = 1);
    
  } // Angie_Persistable_Manager

?>