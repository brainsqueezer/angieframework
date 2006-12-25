<?php

  /**
  * Base DBA manager class
  *
  * @package Angie.DBA
  * @subpackage runtime
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Manager {
    
    /**
    * Return single instance by ID
    * 
    * This function will try to load a single instance based on ID. If row is not found NULL 
    * is returned.
    * 
    * When primary key is made out of more than one field $id parametar needs to be an associative 
    * array where key is the field name and proper value is value we are looking for. 
    *
    * @param mixed $id
    * @param array $primary_key
    * @param string $object_class
    * @param string $table_name
    * @param array $fields
    * @return Angie_DBA_Object
    */
    static function findById($id, $primary_key, $object_class, $table_name, $fields = null) {
      $connection = Angie_DB::getConnection();
      
      $conditions = array();
      if(count($primary_key) == 1) {
        $conditions = $connection->prepareString($connection->escapeFieldName($primary_key[0]) . ' = ?', array($id));
        
        //$conditions = Angie_DB::getConnection()->prepareString($primary_key[0] . ' = ?', array($id));
      } elseif(count($primary_key) > 1) {
        $conditions = array();
        foreach($primary_key as $pk) {
          $conditions[] = $connection->prepareString($connection->escapeFieldName($pk) . ' = ?', array(array_var($id, $pk)));
        } // foreach
        $conditions = implode(' AND ', $conditions);
      } // if
      
      return self::find(array(
        'conditions' => $conditions,
        'one' => true,
      ), $object_class, $table_name, $fields);
    } // name
    
    /**
    * Return array of objects or first object that match $sql
    *
    * @param string $sql
    * @param string $object_class
    * @param boolean $one
    * @return mixed
    */
    static function findBySQL($sql, $object_class, $one) {
      $rows = Angie_DB::getConnection()->executeAll($sql);
      
      if(!is_foreachable($rows)) {
        return $one ? null : array();
      } // if
      
      if($one) {
        return self::loadFromRow($rows[0], $object_class);
      } else {
        //print_r($rows);
        $objects = array();
        foreach($rows as $row) {
          $object = self::loadFromRow($row, $object_class);
          if($object instanceof $object_class) {
            $objects[] = $object;
          } // if
        } // foreach
        return count($objects) ? $objects : array();
      } // if
    } // findBySQL
    
    /**
    * Do a SELECT query over database with specified arguments
    * 
    * This function has three possible results:
    * 
    * - array of object or empty array if result set is empty
    * - object instance if 'one' is set to true and object is found
    * - null if 'one' is set to true and object is not found
    * 
    * Function arguments:
    * 
    * - one        - select first row and return object
    * - conditions - query conditions
    * - order      - order by string
    * - offset     - limit offset, valid only if limit is present
    * - limit
    * 
    * If 'conditions' argument is an array than first element will be used as a base and 
    * other paramethars will be used as arguments for prepareString function.
    * 
    * Examples:
    * <pre>
    * $root_user = Users::find(array(
    *   'conditions' => "username = 'root'",
    *   'one' => true
    * ));
    * 
    * $administrators = Users::find(array(
    *   'conditions' => array('is_administrator = ? AND is_activated = ?', true, true),
    *   'order' => 'username'
    * )); // array
    * </pre>
    *
    * @param array $arguments
    * @param string $object_class
    * @param string $table_name
    * @param mixed $fields
    * @return array
    */
    static function find($arguments, $object_class, $table_name, $fields = null) {
      $table_prefix = trim(Angie::getConfig('db.table_prefix'));
      
      $one        = (boolean) array_var($arguments, 'one', false);
      $conditions = self::prepareConditions(array_var($arguments, 'conditions', ''));
      $order_by   = array_var($arguments, 'order', '');
      $offset     = (integer) array_var($arguments, 'offset', 0);
      $limit      = (integer) array_var($arguments, 'limit', 0);
      
      $connection = Angie_DB::getConnection();
      
      $fields_list = '*';
      if(is_array($fields)) {
        $fields_list = array();
        foreach($fields as $field) {
          $fields_list[] = $connection->escapeFieldName($field);
        } // foreach
        $fields_list = implode(', ', $fields_list);
      } // if
      
      $where_string = trim($conditions) == '' ? '' : "WHERE $conditions";
      $order_by_string = trim($order_by) == '' ? '' : "ORDER BY $order_by";
      $limit_string = $limit > 0 ? "LIMIT $offset, $limit" : '';
      
      $escaped_table_name = $connection->escapeTableName($table_prefix . $table_name);
      return self::findBySQL("SELECT $fields_list FROM $escaped_table_name $where_string $order_by_string $limit_string", $object_class, $one);
    }  // find
    
    /**
    * Do the SELECT query based on arguments and return first row
    * 
    * This function does the same job as find() except it makes sure that 'one' argument is set 
    * to true. @see find() for more details.
    *
    * @param mixed $arguments
    * @param string $object_class
    * @param string $table_name
    * @param mixed $fields
    * @return Angie_DBA_Object
    */
    static function findOne($arguments, $object_class, $table_name, $fields = null) {
      if(!is_array($arguments)) {
        $arguments = array();
      } // if
      $arguments['one'] = true;
      return self::find($arguments, $object_class, $table_name, $fields);
    } // findOne
    
    /**
    * Do the SELECT query based on arguments and return all rows
    * 
    * This function does the same job as find() except it makes sure that 'one' argument is set 
    * to fa;se. @see find() for more details.
    *
    * @param mixed $arguments
    * @param string $object_class
    * @param string $table_name
    * @param mixed $fields
    * @return array
    */
    static function findAll($arguments, $object_class, $table_name, $fields = null) {
      if(!is_array($arguments)) {
        $arguments = array();
      } // if
      $arguments['one'] = false;
      return self::find($arguments, $object_class, $table_name, $fields);
    } // findAll
    
    /**
    * Return number of rows in database that match the $conditions
    *
    * @param string $conditions
    * @param array $primary_key
    * @return integer
    */
    static function count($conditions, $table_name, $primary_key) {
      $table_prefix = trim(Angie::getConfig('db.table_prefix'));
      
      $fields = count($primary_key) == 1 ? $primary_key[0] : '*';
      
      $conditions = self::prepareConditions($conditions);
      $where_string = trim($conditions) == '' ? '' : "WHERE $conditions";
      
      $db_connection = Angie_DB::getConnection();
      $escaped_table_name = $db_connection->escapeTableName($table_prefix . $table_name);
      
      $row = $db_connection->executeOne("SELECT COUNT($fields) AS 'row_count' FROM $escaped_table_name $where_string");
      return (integer) array_var($row, 'row_count', 0);
    } // count
    
    /**
    * Delete rows from database that match the $conditions
    *
    * @param string $conditions
    * @param string $table_name
    * @return integer
    */
    static function delete($conditions, $table_name) {
      $table_prefix = trim(Angie::getConfig('db.table_prefix'));
      
      $db_connection = Angie_DB::getConnection();
      $escaped_table_name = $db_connection->escapeTableName($table_prefix . $table_name);
      
      $conditions = self::prepareConditions($conditions);
      $where_string = trim($conditions) == '' ? '' : "WHERE $conditions";
      return $db_connection->execute("DELETE FROM $escaped_table_name $where_string");
    } // delete
    
    /**
    * Update searies of fields in rows that match $conditions
    * 
    * $fields is an associative array where key is field and value is new value that needs to be set
    *
    * @param array $fields
    * @param mixed $conditions
    * @param string $table_name
    * @return integer
    */
    static function update($fields, $conditions, $table_name) {
      if(!is_foreachable($fields)) {
        return 0;
      } // if
      
      $table_prefix = trim(Angie::getConfig('db.table_prefix'));
      
      $db_connection = Angie_DB::getConnection();
      $escaped_table_name = $db_connection->escapeTableName($table_prefix . $table_name);
      
      $conditions = self::prepareConditions($conditions);
      $where_string = trim($conditions) == '' ? '' : "WHERE $conditions";
      
      $prepared = array();
      foreach($fields as $field_name => $new_value) {
        $prepared[] = $db_connection->escapeFieldName($field_name) . ' = ' . $db_connection->escape($new_value);
      } // foreach
      $prepared = implode(', ', $prepared);
      
      return $db_connection->execute("UPDATE $escaped_table_name SET $prepared $where_string");
    } // update
    
    /**
    * This function will return paginated result
    * 
    * Result is array where first element is array of returned object and second populated 
    * pagination object that can be used for obtaining and rendering pagination data using 
    * various helpers.
    * 
    * Items and pagination array vars are indexed with 0 for items and 1 for pagination
    * because you can't use associative indexing with list() construct
    * 
    * Arguments are the same as for find. This function will take care about limit, offset 
    * and one argument
    *
    * @param array $arguments
    * @param integer $items_per_page
    * @param integer $current_page
    * @param string $object_class
    * @param string $table_name
    * @param array $primary_key
    * @param mixed $fields
    * @return array
    */
    static function paginate($arguments, $items_per_page, $current_page, $object_class, $table_name, $primary_key, $fields = null) {
      if(!is_array($arguments)) {
        $arguments = array();
      } // if
      $conditions = array_var($arguments, 'conditions');
      $pagination = new Angie_Pagination(
        self::count($conditions, $table_name, $primary_key), 
        $items_per_page, 
        $current_page
      ); // Angie_Pagination
      
      $arguments['one']    = false;
      $arguments['offset'] = $pagination->getLimitStart();
      $arguments['limit']  = $items_per_page;
      
      $items = self::find($arguments, $object_class, $table_name, $fields);
      return array($items, $pagination);
    } // paginate
    
    // ---------------------------------------------------
    //  Util methods
    // ---------------------------------------------------
    
    /**
    * Create new item instance and set properties from row
    *
    * @param array $row
    * @parma string $item_class
    * @return Angie_DBA_Object
    */
    static private function loadFromRow($row, $object_class) {
      $item = new $object_class();
      if(!($item instanceof Angie_DBA_Object)) {
        return null;
      } // if
      
      if($item->loadFromRow($row) && $item->isLoaded()) {
        return $item;
      } // if
      
      return null;
    } // loadFromRow
    
    /**
    * Convert conditions argument to a string
    * 
    * @param mixed $conditions
    * @return string
    */
    static private function prepareConditions($conditions) {
      if(is_array($conditions)) {
        $conditions_sql = array_shift($conditions);
        $conditions_arguments = count($conditions) ? $conditions : null;
        return Angie_DB::getConnection()->prepareString($conditions_sql, $conditions_arguments);
      } // if
      return $conditions;
    } // prepareConditions
  
  } // Angie_DBA_Manager

?>