<?= '<?php' ?>


  /**
  * <?= $entity->getBaseManagerClassName() ?> class
  */
  class <?= $entity->getBaseManagerClassName() ?> extends <?= $entity->getManagerExtends() ?> {
  
    /**
    * Item class name
    *
    * @var string
    */
    static public $object_class = '<?= $entity->getObjectClassName() ?>';
    
    /**
    * Table name (without prefix)
    *
    * @var string
    */
    static public $table_name = '<?= $entity->getTableName() ?>';
    
    /**
    * Array of all entity field names
    *
    * @param array
    */
    static public $fields = array(<?= $entity->exportFieldNames() ?>);
    
    /**
    * List of fields without detail fields
    *
    * @var array
    */
    static public $fields_without_details = array(<?= $entity->exportFieldNamesWithoutDetails() ?>);
    
    /**
    * List of primary key fields
    *
    * @var array
    */
    static public $primary_key = array(<?= $entity->exportPkFieldNames() ?>);
    
    /**
    * Execute query and return array of populated <?= $entity->getObjectClassName() ?> objects
    *
    * If $one is true only first row of the result will be populated and returned
    *
    * @param string $sql
    * @param boolean $one
    * @return mixed
    */
    static function findBySQL($sql, $one = false) {
      return parent::findBySQL($sql, self::$object_class, $one);
    } // findBySQL
    
    /**
    * Return single <?= $entity->getObjectClassName() ?> object that match $id
    *
    * If $full is true all object fields will be loaded; else detail fields will be skipped
    *
    * @param mixed $id
    * @param boolean $full
    * @return <?= $entity->getObjectClassName() ?> 
    */
    static function findById($id, $full = false) {
      if($full) {
        return parent::findById($id, self::$primary_key, self::$object_class, self::$table_name);
      } else {
        return parent::findById($id, self::$primary_key, self::$object_class, self::$table_name, self::$fields_without_details);
      } // if
    } // findById
    
    /**
    * Return all <?= $entity->getObjectClassName() ?> objects that match conditions given in $arguments
    *
    * Base on 'one' argument this funciton can return only the first object that matches the conditions or all objects 
    * that match them. If $full is true all object fields will be loaded; else detail fields will be skipped
    *
    * @param mixed $arguments
    * @param boolean $full
    * @return mixed
    */
    static function find($arguments = null, $full = false) {
      if($full) {
        return parent::find($arguments, self::$object_class, self::$table_name);
      } else {
        return parent::find($arguments, self::$object_class, self::$table_name, self::$fields_without_details);
      } // if
    }  // find
    
    /**
    * Return only first <?= $entity->getObjectClassName() ?> objects that matches conditions
    *
    * If $full is true all object fields will be loaded; else detail fields will be skipped
    *
    * @param mixed $arguments
    * @param boolean $full
    * @return <?= $entity->getObjectClassName() ?> 
    */
    static function findOne($arguments, $full = false) {
      if($full) {
        return parent::findOne($arguments, self::$object_class, self::$table_name);
      } else {
        return parent::findOne($arguments, self::$object_class, self::$table_name, self::$fields_without_details);
      } // if
    } // findOne
    
    /**
    * Return all <?= $entity->getObjectClassName() ?> objects that match conditions given in $arguments
    *
    * If $full is true all object fields will be loaded; else detail fields will be skipped
    *
    * @param mixed $arguments
    * @param boolean $full
    * @return array
    */
    static function findAll($arguments, $full = false) {
      if($full) {
        return parent::findAll($arguments, self::$object_class, self::$table_name);
      } else {
        return parent::findAll($arguments, self::$object_class, self::$table_name, self::$fields_without_details);
      } // if
    } // findAll
    
    /**
    * Return number of <?= $entity->getObjectClassName() ?> objects that match the $conditions
    *
    * @param mixed $conditions
    * @return integer
    */
    static function count($conditions = null) {
      return parent::count($conditions, self::$table_name, self::$primary_key);
    } // count
    
    /**
    * Delete all <?= $entity->getObjectClassName() ?> objects that match the $conditions
    *
    * @param mixed $conditions
    * @return integer
    */
    static function delete($conditions = null) {
      return parent::delete($conditions, self::$table_name);
    } // delete
    
    /**
    * Return paginated set of <?= $entity->getObjectClassName() ?> objects based on current page and number of objects per page
    *
    * First element of result is array of objects that match the conditions and page limits. Second parametar is 
    * populated Angie_Pagination object that holds the description of the pagination
    *
    * @param mixed $arguments
    * @param integer $items_per_page
    * @param integer $current_page
    * @return array
    */
    static function paginate($arguments = null, $items_per_page = 10, $current_page = 1) {
      if($full) {
        return parent::paginate($arguments, $items_per_page, $current_page, self::$object_class, self::$table_name, self::$primary_key);
      } else {
        return parent::paginate($arguments, $items_per_page, $current_page, self::$object_class, self::$table_name, self::$primary_key, self::$fields_without_details);
      } // if
    } // paginate
    
<?php if(is_foreachable($entity->getBlocks())) { ?>
<?php foreach($entity->getBlocks() as $block) { ?>
<?php $block->renderManagerMembers() ?>
<?php } // foreach ?>
<?php } // if ?>
  
  } // <?= $entity->getBaseManagerClassName() ?>

<?= '?>' ?>