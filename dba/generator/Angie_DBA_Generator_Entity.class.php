<?php

  /**
  * Generator entity
  *
  * This class is used to describe single model entity - its attributes, relationships and some additional settings 
  * (field protection, auto-setters etc)
  * 
  * @package Angie.DBA
  * @subpackage generator
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Entity {
    
    /**
    * Entity name
    *
    * @var string
    */
    private $name;
    
    /**
    * Name of the generated object class
    *
    * @var string
    */
    private $object_class_name = null;
    
    /**
    * Name of the generated manager class
    *
    * @var string
    */
    private $manager_class_name = null;
    
    /**
    * Base object class name
    * 
    * If NULL class name with Base prefix will be returned
    *
    * @var string
    */
    private $base_object_class_name = null;
    
    /**
    * Base manager class name
    * 
    * If NULL manager class name with Base prefix will be returned
    *
    * @var string
    */
    private $base_manager_class_name = null;
    
    /**
    * Name of the output directory
    * 
    * If NULL plural of entity name will be used
    *
    * @var string
    */
    private $output_dir = null;
    
    /**
    * Base object class will extend this class
    *
    * @var string
    */
    private $object_extends = 'Angie_DBA_Object';
    
    /**
    * Base manager class will extend this class
    *
    * @var string
    */
    private $manager_extends = 'Angie_DBA_Manager';
    
    /**
    * Name of the generated table (without prefix)
    *
    * @var string
    */
    private $table_name = null;
    
    /**
    * Primary key for this field
    * 
    * This is array of fields that form primary key for this entity. DBA supports composite 
    * keys, but most common scenario is key on one single field
    *
    * @var array
    */
    private $primary_key = array();
    
    /**
    * Array of all entity fields populated as we add new blocks to the entity
    *
    * @var array
    */
    private $fields = null;
    
    /**
    * Array of building blocks
    *
    * @var array
    */
    private $blocks = array();
    
    /**
    * Array of entity attributes
    *
    * @var array
    */
    private $attributes = array();
    
    /**
    * Array of entity relations
    *
    * @var array
    */
    private $relations = array();
    
    /**
    * Array of protected fields
    *
    * @var array
    */
    private $protected_fields = array();
    
    /**
    * Array of allowed fields
    *
    * @var array
    */
    private $allowed_fields = array();
    
    /**
    * Array of details fields
    *
    * @var array
    */
    private $detail_fields = array();
    
    /**
    * Array of entity auto setters
    *
    * @var array
    */
    private $auto_setters = array();
  
    /**
    * Constructor
    *
    * @param void
    * @return Angie_DBA_Generator_Entity
    */
    function __construct($name) {
      $this->setName($name);
      
      $this->addAutoSetter('created_on', 'get_mysql_now', Angie_DBA_Generator::ON_INSERT);
      $this->addAutoSetter('updated_on', 'get_mysql_now', Angie_DBA_Generator::ON_UPDATE);
    } // __construct
    
    /**
    * Last call before we start process of building classes based on description
    * 
    * When generate() method of generator is triggered it will call all entities to prepare 
    * if they have something left to do.
    *
    * @param void
    * @return null
    */
    function prepare() {
      
    } // preapre
    
    /**
    * Generate classes
    *
    * @param Angie_Output $output
    * @param string $output_dir
    * @param mixed $additional_options
    * @return null
    */
    function generate(Angie_Output $output, $output_dir, $additional_options = null) {
      $base_dir = with_slash($output_dir) . 'base';
      
      if(is_dir($base_dir)) {
        $output->printMessage("Directory '" . Angie_DBA_Generator::relativeToOutput($base_dir) . "' exists", 'skip');
      } else {
        if(mkdir($base_dir)) {
          $output->printMessage("Directory '" . Angie_DBA_Generator::relativeToOutput($base_dir) . "' created", '+');
        } else {
          throw new Angie_FileSystem_Error_DirNotWritable($output_dir);
        } // if
      } // if
      
      if(!folder_is_writable($base_dir)) {
        throw new Angie_FileSystem_Error_DirNotWritable($base_dir);
      } // if
      
      Angie_DBA_Generator::assignToView('entity', $this);
      
      $this->generateBaseObject($output, $base_dir, $additional_options);
      $this->generateBaseManager($output, $base_dir, $additional_options);
      $this->generateObject($output, $output_dir, $additional_options);
      $this->generateManager($output, $output_dir, $additional_options);
    } // generate
    
    private function generateBaseObject(Angie_Output $output, $output_dir, $additional_options = null) {
      $output_file = with_slash($output_dir) . $this->getBaseObjectClassName() . '.class.php';
      file_put_contents($output_file, Angie_DBA_Generator::fetchView('base_object_class'));
    } // generateBaseObject
    
    private function generateBaseManager(Angie_Output $output, $output_dir, $additional_options = null) {
      $output_file = with_slash($output_dir) . $this->getBaseManagerClassName() . '.class.php';
      file_put_contents($output_file, Angie_DBA_Generator::fetchView('base_manager_class'));
    } // generateBaseManager
    
    private function generateObject(Angie_Output $output, $output_dir, $additional_options = null) {
      
    } // generateObject
    
    private function generateManager(Angie_Output $output, $output_dir, $additional_options = null) {
      
    } // generateManager
    
    // ---------------------------------------------------
    //  Generation methods
    // ---------------------------------------------------
    
    /**
    * Return preapre array (as string) of all primary key fields
    *
    * @param void
    * @return string
    */
    function exportPkFieldNames() {
      $field_names = array_keys($this->getPrimaryKey());
      if(is_foreachable($field_names)) {
        foreach($field_names as $k => $v) {
          $field_names[$k] = "'$v'";
        } // foreach
      } // if
      return implode(', ', $field_names);
    } // exportPkFieldNames
    
    /**
    * Return all field names so they can be easily printed into templates
    *
    * @param void
    * @return string
    */
    function exportFieldNames() {
      $field_names = array_keys($this->getFields());
      if(is_foreachable($field_names)) {
        foreach($field_names as $k => $v) {
          $field_names[$k] = "'$v'";
        } // foreach
      } // if
      return implode(', ', $field_names);
    } // exportFieldNames
    
    /**
    * Return names of field names without detail fields
    *
    * @param void
    * @return string
    */
    function exportFieldNamesWithoutDetails() {
      $all_field_names = array_keys($this->getFields());
      $field_names = array();
      if(is_foreachable($all_field_names)) {
        foreach($all_field_names as $k => $v) {
          if(!in_array($v, $this->detail_fields)) {
            $field_names[] = "'$v'";
          } // if
        } // foreach
      } // if
      return implode(', ', $field_names);
    } // exportFieldNamesWithoutDetails
    
    /**
    * return names of protected fields so they can be easiliy printed in templates
    *
    * @param void
    * @return string
    */
    function exportProtectedFields() {
      $field_names = $this->getProtectedFields();
      if(is_foreachable($field_names)) {
        foreach($field_names as $k => $v) {
          $field_names[$k] = "'$v'";
        } // foreach
      } // if
      return implode(', ', $field_names);
    } // exportProtectedFields
    
    /**
    * Export names of allowed fields so they can be easiliy printed in templates
    *
    * @param void
    * @return string
    */
    function exportAllowedFields() {
      $field_names = $this->getAllowedFields();
      if(is_foreachable($field_names)) {
        foreach($field_names as $k => $v) {
          $field_names[$k] = "'$v'";
        } // foreach
      } // if
      return implode(', ', $field_names);
    } // exportAllowedFields
    
    /**
    * This function will return the name of auto increment field if that field exists
    *
    * @param void
    * @return string
    */
    function getAutoIncrementField() {
      $fields = $this->getFields();
      if(is_foreachable($fields)) {
        foreach($fields as $field) {
          if($field instanceof Angie_DBA_Generator_IntegerField && $field->getIsAutoIncrement()) {
            return $field->getName();
          } // if
        } // foreah
      } // if
      return null;
    } // getAutoIncrementField
    
    // ---------------------------------------------------
    //  Helper methods / Attributes
    // ---------------------------------------------------
    
    /**
    * Add ID attribute to this entity
    *
    * @param string $name
    * @param string $size
    * @param boolean $is_auto_increment
    * @param boolean $is_primary_key
    * @return Angie_DBA_Generator_IdAttribute
    */
    function addIdAttribute($name, $size = null, $is_auto_increment = true, $is_primary_key = false) {
      $attribute = $this->addAttribute(new Angie_DBA_Generator_IdAttribute($this, $name, $size, $is_auto_increment));
      if($is_primary_key) {
        $this->addToPrimaryKey($attribute->getFields());
      } // if
      return $attribute;
    } // addIdAttribute
    
    /**
    * Add integer attribute to this entity
    *
    * @param string $name
    * @param string $size
    * @param integer $lenght
    * @param boolean $is_unsigned
    * @return Angie_DBA_Generator_IntegerAttribute
    */
    function addIntAttribute($name, $size = null, $lenght = null, $is_unsigned = false) {
      return $this->addAttribute(new Angie_DBA_Generator_IntegerAttribute($this, $name, $size, $lenght, $is_unsigned, false));
    } // addIntAttribute
    
    /**
    * Add string attribute (varchar) to this entity
    *
    * @param string $name
    * @param integer $lenght
    * @return Angie_DBA_Generator_StringAttribute
    */
    function addStringAttribute($name, $lenght) {
      return $this->addAttribute(new Angie_DBA_Generator_StringAttribute($this, $name, $lenght));
    } // addStringAttribute
    
    /**
    * Add text (multiline, long string) to this entity
    *
    * @param string $name
    * @param string $size
    * @return Angie_DBA_Generator_TextAttribute
    */
    function addTextAttribute($name, $size) {
      return $this->addAttribute(new Angie_DBA_Generator_TextAttribute($this, $name, $size));
    } // addTextAttribute
    
    /**
    * Add date time attribute to this entity
    *
    * @param string $name
    * @return Angie_DBA_Generator_DateTimeAttribute
    */
    function addDateTimeAttribute($name) {
      return $this->addAttribute(new Angie_DBA_Generator_DateTimeAttribute($this, $name));
    } // addDateTimeAttribute
    
    // ---------------------------------------------------
    //  Helper methods / Relationships
    // ---------------------------------------------------
    
    /**
    * Add belongs to relationshipt to this entity
    *
    * @param Angie_DBA_Generator_Entity $target_entity
    * @param array $options
    * @return Angie_DBA_Generator_Relationship_BelongsTo
    */
    function belongsTo($target_entity, $options = null) {
      if($target_entity instanceof Angie_DBA_Generator_Entity) {
        $target = $target_entity;
      } else {
        $target = Angie_DBA_Generator::getEntity($target_entity);
      } // if
      
      return $this->addRelationship(new Angie_DBA_Generator_Relationship_BelongsTo($this, $target, $options));
    } // belongsTo
    
    /**
    * Add has many relationship to this entity
    *
    * @param Angie_DBA_Generator_Entity $target_entity
    * @param array $options
    * @return Angie_DBA_Generator_Relationship_HasMany
    */
    function hasMany($target_entity, $options = null) {
      if($target_entity instanceof Angie_DBA_Generator_Entity) {
        $target = $target_entity;
      } else {
        $target = Angie_DBA_Generator::getEntity($target_entity);
      } // if
      
      return $this->addRelationship(new Angie_DBA_Generator_Relationship_HasMany($this, $target, $options));
    } // hasMany
    
    /**
    * Add has one relationship to this entity
    *
    * @param Angie_DBA_Generator_Entity $target_entity
    * @param array $options
    * @return Angie_DBA_Generator_Relationship_HasMany
    */
    function hasOne($target_entity, $options = null) {
      if($target_entity instanceof Angie_DBA_Generator_Entity) {
        $target = $target_entity;
      } else {
        $target = Angie_DBA_Generator::getEntity($target_entity);
      } // if
      
    } // hasOne
    
    /**
    * Add has many and belongs to many relationship to this entry
    *
    * @param Angie_DBA_Generator_Entity $target_entity
    * @param array $options
    * @return Angie_DBA_Generator_Relationship_HasAndBelongsToMany
    */
    function hasAndBelongsToMany($target_entity, $options = null) {
      if($target_entity instanceof Angie_DBA_Generator_Entity) {
        $target = $target_entity;
      } else {
        $target = Angie_DBA_Generator::getEntity($target_entity);
      } // if
      
    } // hasAndBelongsToMany
    
    // ---------------------------------------------------
    //  Helper methods / Attribute settings
    // ---------------------------------------------------
    
    /**
    * Return array of protected entity fields
    *
    * @param void
    * @return array
    */
    function getProtectedFields() {
      return $this->protected_fields;
    } // getProtectedFields
    
    /**
    * Return array of allowed fields
    *
    * @param void
    * @return array
    */
    function getAllowedFields() {
      return $this->allowed_fields;
    } // getAllowedFields
    
    /**
    * Return array of detail fields
    *
    * @param void
    * @return array
    */
    function getDetailFields() {
      return $this->detail_fields;
    } // getDetailFields
    
    /**
    * Protect fields
    * 
    * Protected fields can't be populated through mass population methods (mostly used 
    * on form submissions)
    * 
    * Example:
    * <pre>
    * $user->protectFields('created_on', 'created_by_id', 'updated_on', 'updated_by_id');
    * </pre>
    *
    * @param mixed
    * @return null
    */
    function protectFields() {
      $arguments = func_get_args();
      if(is_foreachable($arguments)) {
        foreach($arguments as $value) {
          if(!in_array($value, $this->protected_fields)) {
            $this->protected_fields[] = $value;
          } // if
        } // foreach
      } // if
    } // protectFields
    
    /**
    * Allow fields
    *
    * @param mixed
    * @return null
    */
    function allowFields() {
      $arguments = func_get_args();
      if(is_foreachable($arguments)) {
        foreach($arguments as $value) {
          if(!in_array($value, $this->allowed_fields)) {
            $this->allowed_fields[] = $value;
          } // if
        } // foreach
      } // if
    } // allowFields
    
    /**
    * Defined detail fields
    * 
    * Defail fields are fields that are loaded only on request or on full load call to save 
    * some query time. Some typical details fields are long text fields that are used only 
    * for display, not for any kind of model calculations - body text of comments and messages, 
    * slug values, descriptions...
    * 
    * Primary field can't be marked as detail field!
    *
    * @param mixed
    * @return null
    */
    function detailFields() {
      $arguments = func_get_args();
      if(is_foreachable($arguments)) {
        foreach($arguments as $value) {
          if(!in_array($value, $this->detail_fields) && !in_array($value, array_keys($this->getPrimaryKey()))) {
            $this->detail_fields[] = $value;
          } // if
        } // foreach
      } // if
    } // detailFields
    
    // ---------------------------------------------------
    //  Helpers / Primary key
    // ---------------------------------------------------
    
    /**
    * Return primary key
    * 
    * This function will return array of fields that form primary key for this entity.
    *
    * @param void
    * @return array
    */
    function getPrimaryKey() {
      return $this->primary_key;
    } // getPrimaryKey
    
    /**
    * Add field to primary key
    * 
    * This function will add a single field or an array of fields to the array of fields 
    * that form primary key and return it on success.
    * 
    * Examples:
    * <pre>
    * $entity->addToPrimaryKey(field);
    * $entity->addToPrimaryKey(field, field, field, ...);
    * $entity->addToPrimaryKey(field_name, field_name, field_name, ...);
    * $entity->addToPrimaryKey(array(field, field, field, ...));
    * $entity->addToPrimaryKey(array(field_name, field_name, field_name, ...));
    * </pre>
    *
    * @param mixed
    * @return null
    */
    function addToPrimaryKey() {
      $arguments = func_get_args();
      if(is_foreachable($arguments)) {
        foreach($arguments as $argument) {
          
          // Array of fields or field names
          if(is_foreachable($argument)) {
            foreach($argument as $field) {
              $this->addToPrimaryKey($field);
            } // foreach
            
          // Single field instance
          } elseif($argument instanceof Angie_DBA_Generator_Field) {
            if($this->fieldExists($argument->getName())) {
              $this->primary_key[$argument->getName()] = $argument;
            } // if 
            
          // Single field name
          } elseif(is_string($argument)) {
            $field = $this->getField($argument);
            if($field instanceof Angie_DBA_Generator_Field) {
              $this->primary_key[$field->getName()] = $field;
            } // if
          } // if
        } // foreach
      } // if
    } // addToPrimaryKey
    
    /**
    * Remove field from primary key
    * 
    * Remove single field (by name - $field_name) from array of fields that form primary key
    *
    * @param string $field_name
    * @return null
    */
    function removeFromPrimaryKey($field_name) {
      if(isset($this->primary_key[$field_name])) {
        unset($this->primary_key[$field_name]);
      } // if
    } // removeFromPrimaryKey
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get name
    *
    * @param null
    * @return string
    */
    function getName() {
      return $this->name;
    } // getName
    
    /**
    * Set name value
    *
    * @param string $value
    * @return null
    */
    function setName($value) {
      $this->name = $value;
    } // setName
    
    /**
    * Return object class name
    *
    * @param void
    * @return string
    */
    function getObjectClassName() {
      if(is_null($this->object_class_name)) {
        return Angie_Inflector::camelize($this->getName());
      } // if
      return $this->object_class_name;
    } // getObjectClassName
    
    /**
    * Set object class name
    *
    * @param string $value
    * @return null
    */
    function setObjectClassName($value) {
      $this->object_class_name = $value;
    } // setObjectClassName
    
    /**
    * Get manager_class_name
    *
    * @param null
    * @return string
    */
    function getManagerClassName() {
      if(is_null($this->manager_class_name)) {
        return Angie_Inflector::camelize(Angie_Inflector::pluralize($this->getName()));
      } // if
      return $this->manager_class_name;
    } // getManagerClassName
    
    /**
    * Get base_object_class_name
    *
    * @param null
    * @return string
    */
    function getBaseObjectClassName() {
      if(trim($this->base_object_class_name) == '') {
        return 'Base' . $this->getObjectClassName();
      } // if
      
      return $this->base_object_class_name;
    } // getBaseObjectClassName
    
    /**
    * Set base_object_class_name value
    *
    * @param string $value
    * @return null
    */
    function setBaseObjectClassName($value) {
      $this->base_object_class_name = $value;
    } // setBaseObjectClassName
    
    /**
    * Get base_manager_class_name
    *
    * @param null
    * @return string
    */
    function getBaseManagerClassName() {
      if(trim($this->base_manager_class_name) == '') {
        return 'Base' . $this->getManagerClassName();
      } // if
      
      return $this->base_manager_class_name;
    } // getBaseManagerClassName
    
    /**
    * Set base_manager_class_name value
    *
    * @param string $value
    * @return null
    */
    function setBaseManagerClassName($value) {
      $this->base_manager_class_name = $value;
    } // setBaseManagerClassName
    
    /**
    * Get output_dir
    *
    * @param null
    * @return string
    */
    function getOutputDir() {
      if(trim($this->output_dir) == '') {
        return Angie_Inflector::pluralize($this->getName());
      } // if
      
      return $this->output_dir;
    } // getOutputDir
    
    /**
    * Set output_dir value
    *
    * @param string $value
    * @return null
    */
    function setOutputDir($value) {
      $this->output_dir = $value;
    } // setOutputDir
    
    /**
    * Get object_extends
    *
    * @param null
    * @return string
    */
    function getObjectExtends() {
      return $this->object_extends;
    } // getObjectExtends
    
    /**
    * Set object_extends value
    *
    * @param string $value
    * @return null
    */
    function setObjectExtends($value) {
      $this->object_extends = $value;
    } // setObjectExtends
    
    /**
    * Get manager_extends
    *
    * @param null
    * @return string
    */
    function getManagerExtends() {
      return $this->manager_extends;
    } // getManagerExtends
    
    /**
    * Set manager_extends value
    *
    * @param string $value
    * @return null
    */
    function setManagerExtends($value) {
      $this->manager_extends = $value;
    } // setManagerExtends
    
    /**
    * Get table_name
    *
    * @param null
    * @return string
    */
    function getTableName() {
      if(is_null($this->table_name)) {
        return Angie_Inflector::pluralize($this->getName());
      } // if
      return $this->table_name;
    } // getTableName
    
    /**
    * Set table_name value
    *
    * @param string $value
    * @return null
    */
    function setTableName($value) {
      $this->table_name = $value;
    } // setTableName
    
    /**
    * Set manager_class_name value
    *
    * @param string $value
    * @return null
    */
    function setManagerClassName($value) {
      $this->manager_class_name = $value;
    } // setManagerClassName
    
    /**
    * Return array of registered blocks
    *
    * @param void
    * @return array
    */
    function getBlocks() {
      return $this->blocks;
    } // getBlocks
    
    /**
    * Return all entity fields
    * 
    * Array of fields is collected by build() method from all available attributes 
    * and relationships
    *
    * @param void
    * @return array
    */
    function getFields() {
      return $this->fields;
    } // getFields
    
    /**
    * Return entity field by name
    *
    * @param void
    * @return Angie_DBA_Generator_Field
    */
    function getField($name) {
      return array_var($this->fields, $name);
    } // getField
    
    /**
    * Add field to the fields list
    *
    * @param Angie_DBA_Generator_Field $field
    * @return Angie_DBA_Generator_Field
    */
    protected function addField(Angie_DBA_Generator_Field $field) {
      $this->fields[$field->getName()] = $field;
      return $field;
    } // addField
    
    /**
    * This function will return true if field $field_name exists in this entity
    *
    * @param string $field_name
    * @return boolean
    */
    function fieldExists($field_name) {
      return is_array($this->fields) && isset($this->fields[$field_name]);
    } // fieldExists
    
    /**
    * Return all entity attributes
    *
    * @param void
    * @return array
    */
    function getAttributes() {
      return $this->attributes;
    } // getAttributes
    
    /**
    * Return single specific attribute, by name
    *
    * @param string $name
    * @return Angie_DBA_Generator_Attribute
    */
    function getAttribute($name) {
      return array_var($this->attributes, $name);
    } // getAttribute
    
    /**
    * Add attribute to the entity
    *
    * @param Angie_DBA_Generator_Attribute $attribute
    * @return Angie_DBA_Generator_Attribute
    */
    function addAttribute(Angie_DBA_Generator_Attribute $attribute) {
      $this->blocks[] = $attribute;
      $this->attributes[$attribute->getName()] = $attribute;
      
      $fields = $attribute->getFields();
      if(is_foreachable($fields)) {
        foreach($fields as $field) {
          $this->addField($field);
        } // foreach
      } elseif($fields instanceof Angie_DBA_Generator_Field) {
        $this->addField($fields);
      } // if
      
      return $attribute;
    } // addAttribute
    
    /**
    * Return array of entity relationships
    *
    * @param void
    * @return array
    */
    function getRelationships() {
      return $this->relations;
    } // getRelationships
    
    /**
    * Add single relationship to the entity
    *
    * @param Angie_DBA_Generator_Relationship $relationship
    * @return Angie_DBA_Generator_Relationship
    */
    function addRelationship(Angie_DBA_Generator_Relationship $relationship) {
      $this->blocks[] = $relationship;
      $this->relations[] = $relationship;
      
      $fields = $relationship->getFields();
      if(is_foreachable($fields)) {
        foreach($fields as $field) {
          $this->addField($field);
        } // foreach
      } elseif($fields instanceof Angie_DBA_Generator_Field) {
        $this->addField($fields);
      } // if
      
      return $relationship;
    } // addRelationship
    
    /**
    * Return array of auto-setters
    *
    * @param void
    * @return array
    */
    function getAutoSetters() {
      return $this->auto_setters;
    } // getAutoSetters
    
    /**
    * Add auto-setter to this entity
    *
    * @param string $field
    * @param string $callback
    * @param string $call_on
    * @return Angie_DBA_Generator_AutoSetter
    */
    function addAutoSetter($field, $callback, $call_on = Angie_DBA_Generator::ON_SAVE) {
      if($field instanceof Angie_DBA_Generator_Field) {
        $field_name = $field->getName();
      } else {
        $field_name = $field;
      } // if
      
      $setter = new Angie_DBA_Generator_AutoSetter($field, $callback, $call_on);
      $setter->setEntity($this);
      
      $this->auto_setters[$field_name] = $setter;
      
      return $setter;
    } // addAutoSetter
  
  } // Angie_DBA_Generator_Entity

?>