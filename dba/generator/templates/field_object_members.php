    
    /**
    * Return value of '<?= $field->getName() ?>' field
    *
    * @return <?= $field->getNativeType() ?> 
    */
    function <?= $field->getGetterName() ?>() {
      return $this->field_values['<?= $field->getName() ?>'];
    } // <?= $field->getGetterName() ?> 
    
    /**
    * Set value of '<?= $field->getName() ?>' field
    *
    * @param <?= $field->getNativeType() ?> $value
    */
    function <?= $field->getSetterName() ?>($value) {
<?php if(trim($field->getCastFunction())) { ?>
      $this->field_values['<?= $field->getName() ?>'] = <?= $field->castFunction() ?>($value);
<?php } else { ?>
      $this->field_values['<?= $field->getName() ?>'] = $value;
<?php } // if ?>
    } // <?= $field->getSetterName() ?> 
    