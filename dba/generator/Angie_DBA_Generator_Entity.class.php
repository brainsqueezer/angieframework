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
      
      $this->addAutoSetter('created_on', 'Angie_DateTime::now', Angie_DBA_Generator::ON_INSERT);
      $this->addAutoSetter('updated_on', 'Angie_DateTime::now', Angie_DBA_Generator::ON_UPDATE);
    } // __construct
    
    /**
    * Generate classes
    *
    * @param Angie_Output $output
    * @param string $output_dir
    * @param mixed $additional_options
    * @return null
    */
    function generate(Angie_Output $output, $output_dir, $additional_options = null) {
      $quiet = array_var($additional_options, 'quiet');
      $force = array_var($additional_options, 'force');
      
      $base_dir = with_slash($output_dir) . 'base';
      
      if(is_dir($base_dir)) {
        if(!$quiet) {
          $output->printMessage("Directory '" . Angie_DBA_Generator::relativeToOutput($base_dir) . "' exists. Continue.");
        } // if
      } else {
        if(mkdir($base_dir)) {
          if(!$quiet) {
            $output->printMessage("Directory '" . Angie_DBA_Generator::relativeToOutput($base_dir) . "' created");
          } // if
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
    
    /**
    * Generate base object class
    *
    * @param Angie_Output $output
    * @param string $output_dir
    * @param mixed $additional_options
    * @return null
    */
    private function generateBaseObject(Angie_Output $output, $output_dir, $additional_options = null) {
      $quiet = array_var($additional_options, 'quiet');
      
      $output_file = with_slash($output_dir) . $this->getBaseObjectClassName() . '.class.php';
      $file_exists = file_exists($output_file);
      
      file_put_contents($output_file, Angie_DBA_Generator::fetchView('base_object_class'));
      
      if(!$quiet) {
        if($file_exists) {
          $output->printMessage("File '" . Angie_DBA_Generator::relativeToOutput($output_file) . "' exists. Overwrite.");
        } else {
          $output->printMessage("File '" . Angie_DBA_Generator::relativeToOutput($output_file) . "' created");
        } // if
      } // if
    } // generateBaseObject
    
    /**
    * Generate base manager class
    *
    * @param Angie_Output $output
    * @param string $output_dir
    * @param mixed $additional_options
    * @return null
    */
    private function generateBaseManager(Angie_Output $output, $output_dir, $additional_options = null) {
      $quiet = array_var($additional_options, 'quiet');
      
      $output_file = with_slash($output_dir) . $this->getBaseManagerClassName() . '.class.php';
      $file_exists = file_exists($output_file);
      
      file_put_contents($output_file, Angie_DBA_Generator::fetchView('base_manager_class'));
      
      if(!$quiet) {
        if($file_exists) {
          $output->printMessage("File '" . Angie_DBA_Generator::relativeToOutput($output_file) . "' exists. Overwrite.");
        } else {
          $output->printMessage("File '" . Angie_DBA_Generator::relativeToOutput($output_file) . "' created");
        } // if
      } // if
    } // generateBaseManager
    
    /**
    * Generate object class
    *
    * @param Angie_Output $output
    * @param string $output_dir
    * @param mixed $additional_options
    * @return null
    */
    private function generateObject(Angie_Output $output, $output_dir, $additional_options = null) {
      $quiet = array_var($additional_options, 'quiet');
      $force = array_var($additional_options, 'force');
      
      $output_file = with_slash($output_dir) . $this->getObjectClassName() . '.class.php';
      
      if(file_exists($output_file)) {
        if($force) {
          if(!$quiet) {
            $output->printMessage("File '" . Angie_DBA_Generator::relativeToOutput($output_file) . "' exists. Overwrite.");
          } // if
        } else {
          if(!$quiet) {
            $output->printMessage("File '" . Angie_DBA_Generator::relativeToOutput($output_file) . "' exists. Skip.");
          } // if
          return; // skip here...
        } // if
      } else {
        if(!$quiet) {
          $output->printMessage("File '" . Angie_DBA_Generator::relativeToOutput($output_file) . "' created");
        } // if
      } // if
      
      file_put_contents($output_file, Angie_DBA_Generator::fetchView('object_class'));
    } // generateObject
    
    /**
    * Generate manager class
    *
    * @param Angie_Output $output
    * @param string $output_dir
    * @param mixed $additional_options
    * @return null
    */
    private function generateManager(Angie_Output $output, $output_dir, $additional_options = null) {
      $quiet = array_var($additional_options, 'quiet');
      $force = array_var($additional_options, 'force');
      
      $output_file = with_slash($output_dir) . $this->getManagerClassName() . '.class.php';
      
      if(file_exists($output_file)) {
        if($force) {
          if(!$quiet) {
            $output->printMessage("File '" . Angie_DBA_Generator::relativeToOutput($output_file) . "' exists. Overwrite.");
          } // if
        } else {
          if(!$quiet) {
            $output->printMessage("File '" . Angie_DBA_Generator::relativeToOutput($output_file) . "' exists. Skip.");
          } // if
          return; // skip here...
        } // if
      } else {
        if(!$quiet) {
          $output->printMessage("File '" . Angie_DBA_Generator::relativeToOutput($output_file) . "' created");
        } // if
      } // if
      
      file_put_contents($output_file, Angie_DBA_Generator::fetchView('manager_class'));
    } // generateManager
    
    // ---------------------------------------------------
    //  Helper methods / Attributes
    // ---------------------------------------------------
    
    /**
    * Add ID attribute to this entity
    *
    * ID attribute is basicly tables primary key. It is formed out of one field that will use $name as its name. Default 
    * size is NORMAL with auto_increment set to true (can be changed through function parametars).
    * 
    * @param string $name
    * @param string $size
    * @param boolean $is_auto_increment
    * @return Angie_DBA_Generator_IdAttribute
    */
    function addIdAttribute($name, $auto_increment = true) {
      $attribute = new Angie_DBA_Generator_Attribute_Id($this, $name, $auto_increment);
      
      $this->addAttribute($attribute);
      
      $this->addToPrimaryKey($attribute->getFields());
      return $attribute;
    } // addIdAttribute
    
    /**
    * Add foreign key attribute to this entity
    *
    * @param string $name
    * @param mixed $default_value
    * @return Angie_DBA_Generator_Attribute_ForeignKey
    */
    function addFkAttribute($name, $default_value = null) {
      $attribute = new Angie_DBA_Generator_Attribute_ForeignKey($this, $name, $default_value);
      return $this->addAttribute($attribute);
    } // addFkAttribute
    
    /**
    * Add integer attribute to this entity
    *
    * @param string $name
    * @param mixed $default_value
    * @param boolean $required
    * @param boolean $unsigned
    * @return Angie_DBA_Generator_IntegerAttribute
    */
    function addIntAttribute($name, $unsigned = false, $default_value = null, $required = false) {
      $attribute = new Angie_DBA_Generator_Attribute_Integer($this, $name, $default_value, $required, $unsigned, false);
      return $this->addAttribute($attribute);
    } // addIntAttribute
    
    /**
    * Add string attribute (varchar) to this entity
    *
    * @param string $name
    * @param integer $lenght
    * @param mixed $default_value
    * @param boolean $required
    * @return Angie_DBA_Generator_StringAttribute
    */
    function addStringAttribute($name, $lenght, $default_value = null, $required = false) {
      $attribute = new Angie_DBA_Generator_Attribute_String($this, $name, $default_value, $required, $lenght);
      return $this->addAttribute($attribute);
    } // addStringAttribute
    
    /**
    * Add text (multiline, long string) to this entity
    *
    * @param string $name
    * @param mixed $default_value
    * @param boolean $required
    * @return Angie_DBA_Generator_TextAttribute
    */
    function addTextAttribute($name, $default_value = null, $required = false) {
      $attribute = new Angie_DBA_Generator_Attribute_Text($this, $name, $default_value, $required);
      return $this->addAttribute($attribute);
    } // addTextAttribute
    
    /**
    * Add date time attribute to this entity
    *
    * @param string $name
    * @param mixed $default_value
    * @param boolean $required
    * @return Angie_DBA_Generator_DateTimeAttribute
    */
    function addDateTimeAttribute($name, $default_value = null, $required = false) {
      $attribute = new Angie_DBA_Generator_Attribute_DateTime($this, $name, $default_value, $required);
      return $this->addAttribute($attribute);
    } // addDateTimeAttribute
    
    /**
    * Add enumerable attribute to the list of attributes
    * 
    * This function will add an attribute that can have one of specified values. $valid_values is an array of valid 
    * values and default value is default value use if no value is provided
    *
    * @param string $name
    * @param array $possible_values
    * @param string $default_value
    * @param boolean $required
    * @return Angie_DBA_Generator_Attribute_Enum
    */
    function addEnumAttribute($name, $possible_values, $default_value = null, $required = false) {
      $attribute = new Angie_DBA_Generator_Attribute_Enum($this, $name, $default_value , $required, $possible_values);
      return $this->addAttribute($attribute);
    } // addEnumAttribute
    
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
      
      if(!($target instanceof Angie_DBA_Generator_Entity)) {
        throw new Angie_Core_Error_InvalidParamValue('target_entity', $target_entity, '$target_entity should be a name of defined entity or entity instance');
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
      
      if(!($target instanceof Angie_DBA_Generator_Entity)) {
        throw new Angie_Core_Error_InvalidParamValue('target_entity', $target_entity, '$target_entity should be a name of defined entity or entity instance');
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
      
      if(!($target instanceof Angie_DBA_Generator_Entity)) {
        throw new Angie_Core_Error_InvalidParamValue('target_entity', $target_entity, '$target_entity should be a name of defined entity or entity instance');
      } // if
      
      return $this->addRelationship(new Angie_DBA_Generator_Relationship_HasOne($this, $target, $options));
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
      
      if(!($target instanceof Angie_DBA_Generator_Entity)) {
        throw new Angie_Core_Error_InvalidParamValue('target_entity', $target_entity, '$target_entity should be a name of defined entity or entity instance');
      } // if
      
      return $this->addRelationship(new Angie_DBA_Generator_Relationship_HasAndBelongsToMany($this, $target, $options));
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
    //  Generation methods
    // ---------------------------------------------------
    
    /**
    * Return preapre array (as string) of all primary key fields
    *
    * @param void
    * @return string
    */
    function exportPkFieldNames() {
      $field_names = $this->getPrimaryKeyFieldNames();
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
    * Export detail field names so they can be easily printed in templates
    *
    * @param void
    * @return string
    */
    function exportDetailFieldNames() {
      $field_names = $this->getDetailFields();
      if(is_foreachable($field_names)) {
        foreach($field_names as $k => $v) {
          $field_names[$k] = "'$v'";
        } // foreach
      } // if
      return implode(', ', $field_names);
    } // exportDetailFieldNames
    
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
          if($field instanceof Angie_DB_Field && $field->getAutoIncrement()) {
            return $field->getName();
          } // if
        } // foreah
      } // if
      return null;
    } // getAutoIncrementField
    
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
    * Return names of fields that make the primary key
    *
    * @param void
    * @return array
    */
    function getPrimaryKeyFieldNames() {
      return array_keys($this->primary_key);
    } // getPrimaryKeyFieldNames
    
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
          } elseif($argument instanceof Angie_DB_Field) {
            if($this->fieldExists($argument->getName())) {
              $this->primary_key[$argument->getName()] = $argument;
            } // if 
            
          // Single field name
          } elseif(is_string($argument)) {
            $field = $this->getField($argument);
            if($field instanceof Angie_DB_Field) {
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
      $result = array();
      
      if(is_array($this->getAttributes())) {
        $result = array_merge($result, $this->getAttributes());
      } // if
      
      if(is_array($this->getRelationships())) {
        $result = array_merge($result, $this->getRelationships());
      } // if
      
      return $result;
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
      $fields = array();
      if(is_foreachable($this->getAttributes())) {
        foreach($this->getAttributes() as $attribute) {
          $attribute_fields = $attribute->getFields();
          if(is_array($attribute_fields)) {
            foreach($attribute_fields as $attribute_field) {
              $fields[$attribute_field->getName()] = $attribute_field;
            } // if
          } elseif($attribute_fields instanceof Angie_DB_Field) {
            $fields[$attribute_fields->getName()] = $attribute_fields;
          } // if
        } // foreach
      } // if
      return $fields;
    } // getFields
    
    /**
    * Return entity field by name
    *
    * @param void
    * @return Angie_DB_Field
    */
    function getField($name) {
      return array_var($this->getFields(), $name);
    } // getField
    
    /**
    * This function will return true if field $field_name exists in this entity
    *
    * @param string $field_name
    * @return boolean
    */
    function fieldExists($field_name) {
      return $this->getField($field_name) instanceof Angie_DB_Field;
    } // fieldExists
    
    /**
    * Add a single attribute to the list of attributes
    *
    * @param Angie_DBA_Generator_Attribute $attribute
    * @return Angie_DBA_Generator_Attribute
    */
    function addAttribute(Angie_DBA_Generator_Attribute $attribute) {
      $this->attributes[$attribute->getName()] = $attribute;
      return $attribute;
    } // addAttribute
    
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
      $this->relations[] = $relationship;
      return $relationship;
    } // addRelationship
    
    /**
    * Return array of auto-setters
    * 
    * $filter can have four possible values:
    * 
    * - null - all auto setters will be returned
    * - Angie_DBA_Generator::ON_SAVE - only on save auto setters will be returned
    * - Angie_DBA_Generator::ON_INSERT - only on insert auto setters will be returned
    * - Angie_DBA_Generator::ON_UPDATE - only on update auto setters will be returned
    * 
    * If $only_valid is set to true this function will return just setters for existing fields
    *
    * @param string $filter
    * @param boolean $only_valid
    * @return array
    */
    function getAutoSetters($filter = null, $only_valid = false) {
      if(is_null($filter)) {
        return $this->auto_setters;
      } else {
        $result = array();
        if(is_foreachable($this->auto_setters)) {
          foreach($this->auto_setters as $auto_setter) {
            if($auto_setter->getCallOn() == $filter) {
              if($only_valid && !($auto_setter->getAttribute() instanceof Angie_DBA_Generator_Attribute)) {
                continue;
              } // if
              $result[] = $auto_setter;
            } // if
          } // foreach
        } // if
        return count($result) ? $result : null;
      } // if
    } // getAutoSetters
    
    /**
    * Add auto-setter to this entity
    *
    * @param string $attribute
    * @param string $callback
    * @param string $call_on
    * @param boolean $pass_caller
    * @return Angie_DBA_Generator_AutoSetter
    */
    function addAutoSetter($attribute, $callback, $call_on = Angie_DBA_Generator::ON_SAVE, $pass_caller = false) {
      if($attribute instanceof Angie_DBA_Generator_Attribute) {
        $attribute_name = $attribute->getName();
      } else {
        $attribute_name = $attribute;
      } // if
      
      $setter = new Angie_DBA_Generator_AutoSetter($attribute_name, $callback, $call_on, $pass_caller);
      $setter->setEntity($this);
      
      $this->auto_setters[$attribute_name] = $setter;
      //var_dump($setter->getFieldName());
      
      return $setter;
    } // addAutoSetter
  
  } // Angie_DBA_Generator_Entity

?>