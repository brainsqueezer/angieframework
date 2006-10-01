<?php

  abstract class Angie_Persistable_DB_Manager implements Angie_Persistable_Manager {
  
    /**
    * Object columns
    * 
    * Associative array where key is column name and value is column type
    *
    * @var array
    */
    static protected $columns = array();
    
    /**
    * Lazy load columns
    *
    * @var array
    */
    static protected $lazy_load_columns = array();
    
    /**
    * Database table where items are saved
    *
    * @var string
    */
    static protected $table_name;
    
    /**
    * Class of items that this manager is handling
    *
    * @var string
    */
    static protected $item_class = '';
    
    // ---------------------------------------------------
    //  Abstract functions
    // ---------------------------------------------------
    
    /**
    * Return primary key column(s)
    * 
    * This function will return array of primary keys (if we have only one primary key than it will return array with 
    * one element).
    *
    * @param void
    * @return array or string
    */
    abstract function getPkColumns();
    
    /**
    * Return name of auto increment column
    * 
    * This function will return name of auto increment column. There can be only one auto increment column per table. If 
    * there is no auto increment column in database NULL is returned
    *
    * @param void
    * @return string
    */
    abstract function getAutoIncrementColumn();
    
    // ---------------------------------------------------
    //  Util methods
    // ---------------------------------------------------
    
    /**
    * Return column type
    * 
    * Return type of specific column. Default type is string
    *
    * @param string $column_name
    * @return string
    */
    static function getColumnType($column_name) {
      return isset(self::$columns[$column_name]) ? self::$columns[$column_name] : DATE_TYPE_STRING;
    } // getColumnType
    
    /**
    * Returns true if specific column ($column_name) is lazy load column
    *
    * @param string $column_name
    * @return boolean
    */
    static function isLazyLoadColumn($column_name) {
      return in_array($column_name, self::getLazyLoadColumns());
    } // isLazyLoadColumn
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Return names of columns
    * 
    * This function will return names of all object columns (only names!)
    *
    * @param boolean $exclude_lazy_load_columns
    * @return array
    */
    static function getColumns($exclude_lazy_load_columns = false) {
      $all_columns = array_keys(self::$columns);
      if($exclude_lazy_load_columns && count(self::$lazy_load_columns)) {
        $result = array();
        foreach($all_columns as $column_name) {
          if(!in_array($column_name, self::$lazy_load_columns)) {
            $result[] = $column_name;
          } // if
        } // foreach
        return $result;
      } // if
      return $all_columns;
    } // getColumns
    
    /**
    * Return names of lazy load columns
    * 
    * Lazy load columns are columns that are excluded on first load (if not stated otherwise). Instead, they are loaded 
    * only when they are requested. This function returns array of lazy load columns specific for this object
    *
    * @param void
    * @return array
    */
    static function getLazyLoadColumns() {
      return self::$lazy_load_columns;
    } // getLazyLoadColumns
    
    /**
    * Get table_name
    *
    * @param null
    * @return string
    */
    static function getTableName() {
      return self::$table_name;
    } // getTableName
    
    /**
    * Get item_class
    *
    * @param null
    * @return string
    */
    static function getItemClass() {
      return self::$item_class;
    } // getItemClass
    
  } // Angie_Persistable_DB_Manager

?>