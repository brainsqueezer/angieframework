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
    * List of fields without detail fields
    *
    * @var array
    */
    protected $fields_without_details = array(<?= $entity->exportFieldNamesWithoutDetails() ?>);
    
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
  
  } // <?= $entity->getBaseObjectClassName() ?>

<?= '?>' ?>