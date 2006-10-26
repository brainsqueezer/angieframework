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
    
    static function findById($id, $full = false) {
      if($full) {
        return parent::findById($id, self::$primary_key, self::$object_class, self::$table_name);
      } else {
        return parent::findById($id, self::$primary_key, self::$object_class, self::$table_name, self::$fields_without_details);
      } // if
    } // findById
    
    static function find($arguments = null, $full = false) {
      if($full) {
        return parent::find($arguments, self::$object_class, self::$table_name);
      } else {
        return parent::find($arguments, self::$object_class, self::$table_name, self::$fields_without_details);
      } // if
    }  // find
    
    static function findOne($arguments, $full = false) {
      if($full) {
        return parent::findOne($arguments, self::$object_class, self::$table_name);
      } else {
        return parent::findOne($arguments, self::$object_class, self::$table_name, self::$fields_without_details);
      } // if
    } // findOne
    
    static function findAll($arguments, $full = false) {
      if($full) {
        return parent::findAll($arguments, self::$object_class, self::$table_name);
      } else {
        return parent::findAll($arguments, self::$object_class, self::$table_name, self::$fields_without_details);
      } // if
    } // findAll
    
    static function count($conditions = null) {
      return parent::count($conditions, self::$table_name, self::$primary_key);
    } // count
    
    static function delete($conditions = null) {
      return parent::delete($conditions, self::$table_name);
    } // delete
    
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