<?= '<?php' ?>


  /**
  * <?= $entity->getBaseObjectClassName() ?> class
  */
  class <?= $entity->getBaseObjectClassName() ?> extends <?= $entity->getObjectExtends() ?> {
  
    /**
    * List of primary key fields
    *
    * @var array
    */
    protected $primary_key = array(<?= $entity->exportPkFieldNames() ?>);
  
    /**
    * List of all object fields
    *
    * @var array
    */
    protected $fields = array(<?= $entity->exportFieldNames() ?>);
    
    /**
    * Array of detail fields
    *
    * @var array
    */
    protected $detail_fields = array(<?= $entity->exportDetailFieldNames() ?>);
    
    /**
    * Name of the table where we persist this objec type
    *
    * @var string
    */
    protected $table_name = '<?= $entity->getTableName() ?>';
    
    /**
    * Name of the manager class
    *
    * @var string
    */
    protected $manager_class = '<?= $entity->getManagerClassName() ?>';
    
    /**
  	* Array of protected attributes that can not be set through mass-assignment functions 
  	* (like setFromAttributes)
  	*
  	* @var array
  	*/
  	protected $attr_protected = array(<?= $entity->exportProtectedFields() ?>);
  	
  	/**
  	* Array of acceptable attributes (fields) that can be set through mass-assignment function 
  	* (setFromAttributes)
  	*
  	* @var array
  	*/
  	protected $attr_acceptable = array(<?= $entity->exportAllowedFields() ?>);
  	
  	/**
  	* Name of the auto increment field
  	*
  	* @var string
  	*/
  	protected $auto_increment_field = <?= var_export($entity->getAutoIncrementField(), true) ?>;
  	
  	/**
  	* Set value for specific field and make sure that it is casted to proper type
  	*
  	* @param string
  	* @param mixed $value
  	* @return null
  	*/
  	function setFieldValue($field_name, $value) {
  	  switch($field_name) {
<?php foreach($entity->getFields() as $field) { ?>
        case '<?= $field->getName() ?>':
<?php if($field->getCastFunction()) { ?>
          $to_set = <?= $field->getCastFunction() ?>($value);
<?php } else { ?>
          $to_set = $value;
<?php } // if ?>
          break;
<?php } // foreach ?>
  	  } // switch
  	  return parent::setFieldValue($field_name, $to_set);
  	} // setValue
  
<?php if(is_foreachable($entity->getFields())) { ?>
<?php foreach($entity->getFields() as $field) { ?>
<?php $field->renderObjectMembers() ?>
<?php } // foreach ?>
<?php } // if ?>
  
<?php if(is_foreachable($entity->getBlocks())) { ?>
<?php foreach($entity->getBlocks() as $block) { ?>
<?php $block->renderObjectMembers() ?>
<?php } // foreach ?>
<?php } // if ?>

<?php if(is_array($entity->getAutoSetters()) && count($entity->getAutoSetters())) { ?>
    /**
  	* Save object into database (insert or update)
  	*
  	* @param void
  	* @return boolean
  	* @throws Angie_DBA_Error_Validation
  	*/
    function save() {
<?php
  $setters_on_save = $entity->getAutoSetters(Angie_DBA_Generator::ON_SAVE, true);
  $setters_on_insert = $entity->getAutoSetters(Angie_DBA_Generator::ON_INSERT, true);
  $setters_on_update = $entity->getAutoSetters(Angie_DBA_Generator::ON_UPDATE, true);
?>
<?php if(is_foreachable($setters_on_save)) { ?>
      // On save auto setters...
<?php foreach($setters_on_save as $setter) { ?>
      if(!$this->isModifiedField('<?= $setter->getFieldName() ?>')) {
        $this->setFieldValue('<?= $setter->getFieldName() ?>', <?= $setter->getCallback() ?>(<?php if($setter->getPassCaller()) { ?>$this<?php } ?>));
      } // if
<?php } // foreach?>
<?php } // if ?>
<?php if(is_foreachable($setters_on_insert) || is_foreachable($setters_on_update)) { ?>
      if($this->isNew()) {
<?php if(is_foreachable($setters_on_insert)) { ?>
        // On insert auto setters...
<?php foreach($setters_on_insert as $setter) { ?>
        if(!$this->isModifiedField('<?= $setter->getFieldName() ?>')) {
          $this->setFieldValue('<?= $setter->getFieldName() ?>', <?= $setter->getCallback() ?>(<?php if($setter->getPassCaller()) { ?>$this<?php } ?>));
        } // if
<?php } // foreach?>
<?php } // if ?>
      } else {
<?php if(is_foreachable($setters_on_update)) { ?>
        // On update auto setters...
<?php foreach($setters_on_update as $setter) { ?>
        if(!$this->isModifiedField('<?= $setter->getFieldName() ?>')) {
          $this->setFieldValue('<?= $setter->getFieldName() ?>', <?= $setter->getCallback() ?>(<?php if($setter->getPassCaller()) { ?>$this<?php } ?>));
        } // if
<?php } // foreach?>
<?php } // if ?>
      } // if
<?php } // if  ?>

      // Inherit...
      return parent::save();
    } // save
<?php } // if ?>
  
  } // <?= $entity->getBaseObjectClassName() ?>

<?= '?>' ?>