<?= '<?php' ?>


  /**
  * Model definition file
  *
  * @package <?= $project_name ?> 
  */
<?php foreach($tables as $table_name => $table) { ?>
<?php $entity_name = Angie_Inflector::singularize($table_name) ?>

  // ---------------------------------------------------
  //  <?= Angie_Inflector::camelize($entity_name) ?> 
  // ---------------------------------------------------
    
  $<?= $entity_name ?> = Angie_DBA_Generator::addEntity('<?= $entity_name ?>');
  
<?php foreach($table->getFields() as $field) { ?>
<?php if($table->isPrimaryKey($field) && ($field instanceof Angie_DB_Field_Integer)) { ?>
  $<?= $entity_name ?>->addIdAttribute('<?= $field->getName() ?>', <?= var_export($field->getAutoIncrement()) ?>);
<?php } elseif($field instanceof Angie_DB_Field_Integer) { ?>
  $<?= $entity_name ?>->addIntAttribute('<?= $field->getName() ?>', <?= var_export($field->getUnsigned()) ?>, <?= var_export($field->getDefaultValue()) ?>, <?= var_export($field->getNotNull()) ?>);
<?php } elseif($field instanceof Angie_DB_Field_String) { ?>
  $<?= $entity_name ?>->addStringAttribute('<?= $field->getName() ?>', <?= var_export($field->getLenght()) ?>, <?= var_export($field->getDefaultValue()) ?>, <?= var_export($field->getNotNull()) ?>);
<?php } elseif($field instanceof Angie_DB_Field_Text) { ?>
  $<?= $entity_name ?>->addTextAttribute('<?= $field->getName() ?>', <?= var_export($field->getDefaultValue()) ?>, <?= var_export($field->getNotNull()) ?>);
<?php } elseif($field instanceof Angie_DB_Field_Float) { ?>
  $<?= $entity_name ?>->addFloatAttribute('<?= $field->getName() ?>', <?= var_export($field->getLenght()) ?>, <?= var_export($field->getPrecission()) ?>, <?= var_export($field->getUnsigned()) ?>, <?= var_export($field->getDefaultValue()) ?>, <?= var_export($field->getNotNull()) ?>);
<?php } elseif($field instanceof Angie_DB_Field_Boolean) { ?>
  $<?= $entity_name ?>->addBooleanAttribute('<?= $field->getName() ?>', <?= var_export($field->getDefaultValue()) ?>, <?= var_export($field->getNotNull()) ?>);
<?php } elseif($field instanceof Angie_DB_Field_DateTime) { ?>
  $<?= $entity_name ?>->addDateTimeAttribute('<?= $field->getName() ?>', <?= var_export($field->getDefaultValue()) ?>, <?= var_export($field->getNotNull()) ?>);  
<?php } elseif($field instanceof Angie_DB_Field_Binary) { ?>
  $<?= $entity_name ?>->addBinaryAttribute('<?= $field->getName() ?>', <?= var_export($field->getDefaultValue()) ?>, <?= var_export($field->getNotNull()) ?>);  
<?php } elseif($field instanceof Angie_DB_Field_Enum) { ?>
  $<?= $entity_name ?>->addEnumAttribute('<?= $field->getName() ?>', <?= var_export($field->getPossibleValues()) ?>, <?= var_export($field->getDefaultValue()) ?>, <?= var_export($field->getNotNull()) ?>);  
<?php } // if ?>
<?php } // foreach ?>
<?php } // foreach ?>

<?= '?>' ?>