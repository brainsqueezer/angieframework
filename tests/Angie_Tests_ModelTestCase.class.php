<?php

  /**
  * Model test case class
  *
  * @package Angie.tests
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Tests_ModelTestCase extends UnitTestCase {
    
    /**
    * Array of insert IDs for specific fixtures. It is associavite array like 
    * array[fixture][object_name] => $value
    *
    * @var array
    */
    private $fixture_insert_ids;
    
    /**
    * Setup model test case
    * 
    * This function will truncate all tables from database table. The whole 
    * procces is about 4 times faster than complete recreatin of table.
    *
    * @param void
    * @return null
    */
    function setUp() {
      global $development_tables;
      
      $connection = Angie_DB::getConnection();
      
      if(is_foreachable($development_tables)) {
        foreach($development_tables as $development_table) {
          $connection->clearTable($development_table->getName());
        } // foreach
      } // if
    } // setUp
    
    /**
    * Use fixtures
    * 
    * This function will import array of fixture files before every test 
    * execution.
    *
    * @param void
    * @return null
    */
    function useFixtures() {
      $connection = Angie_DB::getConnection();
      $table_prefix = Angie::getConfig('db.table_prefix');
      
      $fixture_file_names = func_get_args();
      if(is_foreachable($fixture_file_names)) {
        
        // We will collect reference for rows that need to be updated when all 
        // data is inserted in this array. Sometimes we need to have all 
        // fixtures imported in order to resolve connections and insert proper 
        // inser ID-s
        $updates = array();
        
        foreach($fixture_file_names as $fixture_file_name) {
          
          $fixture_file_path = Angie::engine()->getDevelopmentPath("tests/fixtures/$fixture_file_name.ini");
          if(!is_file($fixture_file_path)) {
            throw new Angie_FileSystem_Error_FileDnx($fixture_file_path);
          } // if
          
          $table_name = $connection->escapeTableName("$table_prefix$fixture_file_name");
          
          $data = parse_ini_file($fixture_file_path, true);
          
          if(is_foreachable($data)) {
            foreach($data as $object_name => $object_data) {
              $escaped_object_data = array();
              $update_fields = false; // log this fixture for update when we insert all object (set to false by default)
              foreach($object_data as $field => $field_value) {
                
                // If we match the fixture insert ID value than use it. If we fail to retrive fixture ID
                // log the row so it can be updated when we have all insert IDs logged
                $is_insert_id = $this->isInsertIdValue($field_value);
                
                if(is_array($is_insert_id) && count($is_insert_id) == 2) {
                  
                  $value = $this->getFixtureInsertId($is_insert_id[0], $is_insert_id[1], null);
                  
                  // ID not logged yet? Keep reference for future update
                  if(is_null($value)) {
                    if(!is_array($update_fields)) {
                      $update_fields = array();
                    } // if
                    $update_fields[$field] = array(
                      'fixture_name' => $is_insert_id[0],
                      'object_name' => $is_insert_id[1]
                    ); // array
                    
                    $value = 0; // set default value
                    
                  } // if
                  
                } else {
                  $value = $field_value;
                } // if
                
                $escaped_object_data[$connection->escapeFieldName($field)] = $connection->escape($value);
              } // foreach
              $connection->execute($sql = 'INSERT INTO ' . $table_name . ' (' . implode(', ', array_keys($escaped_object_data)) . ') VALUES (' . implode(', ', array_values($escaped_object_data)) . ')');
              
              $insert_id = $connection->lastInsertId();
              if($insert_id) {
                $this->addFixtureInsertId($fixture_file_name, $object_name, $insert_id);
                if($update_fields) {
                  if(!isset($updates[$table_name]) || !is_array($updates[$table_name])) {
                    $updates[$table_name] = array();
                  } // if
                  
                  $updates[$table_name][] = array(
                    'update_fields' => $update_fields,
                    'row_id'        => $insert_id
                  ); // array
                } // if
              } // if
            } // foreach
          } // if
        } // foreach
        
        //var_dump($updates);
        
        // Do the update
        if(isset($updates) && is_foreachable($updates)) {
          foreach($updates as $table => $update_rows) {
            foreach($update_rows as $update_data) {
              $fields = array();
              foreach($update_data['update_fields'] as $field_name => $fixture_info) {
                $value = $this->getFixtureInsertId($fixture_info['fixture_name'], $fixture_info['object_name'], null);
                if(is_null($value)) {
                  throw new Angie_Error("Fixture $fixture_info[fixture_name]:$fixture_info[object_name] not loaded!");
                } // if
              } // foreach
              $fields[] = $connection->escapeFieldName($field_name) . ' = ' . $connection->escape($value);
              $connection->execute("UPDATE $table SET " .  implode(', ', $fields) . ' WHERE `id` = ' . $connection->escape($update_data['row_id']));
            } // foreach
          } // foreach
        } // if
        
      } // if
    } // useFixtures
    
    /**
    * Check if value is specific foreign key value
    * 
    * This function will check if value of specific object field is request for 
    * specific insert ID
    *
    * @param string $value
    * @return boolean
    */
    protected function isInsertIdValue($value) {
      if(preg_match("/^([a-zA-Z0-9_]+)\.([a-zA-Z0-9_]+)$/", $value, $matches)) {
        return array($matches[1], $matches[2]);
      } else {
        return false;
      } // if
    } // isInsertIdValue
    
    /**
    * Return value of specific fixture.object insert ID value
    *
    * @param string $fixture_name
    * @param string $object_name
    * @param mixed $default Default value is returned if insert ID is not found
    * @return integer or NULL if ID was not found
    */
    function getFixtureInsertId($fixture_name, $object_name, $default = null) {
      if(!isset($this->fixture_insert_ids[$fixture_name]) || !is_array($this->fixture_insert_ids[$fixture_name])) {
        return $default;
      } // if
      if(!isset($this->fixture_insert_ids[$fixture_name][$object_name])) {
        return $default;
      } // if
      return $this->fixture_insert_ids[$fixture_name][$object_name];
    } // getFixtureInsertId
    
    /**
    * Add insert ID for specific fixture object insertation
    *
    * @param string $fixture_name
    * @param string $object_name
    * @param integer $value
    * @return null
    */
    private function addFixtureInsertId($fixture_name, $object_name, $value) {
      if(!isset($this->fixture_insert_ids[$fixture_name]) || !is_array($this->fixture_insert_ids[$fixture_name])) {
        $this->fixture_insert_ids[$fixture_name] = array();
      } // if
      if(!isset($this->fixture_insert_ids[$fixture_name][$object_name]) || !is_array($this->fixture_insert_ids[$fixture_name][$object_name])) {
        $this->fixture_insert_ids[$fixture_name][$object_name] = array();
      } // if
      $this->fixture_insert_ids[$fixture_name][$object_name] = $value;
    } // addFixtureInsertId
    
    /**
    * Clear insert IDs
    *
    * @param void
    * @return null
    */
    private function clearFixtureInsertIds() {
      $this->fixture_insert_ids = array();
    } // clearFixtureInsertIds
  
  } // Angie_Tests_ModelTestCase

?>