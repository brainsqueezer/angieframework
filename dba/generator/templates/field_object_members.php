    
    /**
    * Return value of '<?= $field->getName() ?>' field
    *
    * @return <?= $field->getNativeType() ?> 
    */
    function <?= $field->getGetterName() ?>() {
      return $this->getFieldValue('<?= $field->getName() ?>');
    } // <?= $field->getGetterName() ?> 
    
    /**
    * Set value of '<?= $field->getName() ?>' field
    *
    * @param <?= $field->getNativeType() ?> $value
    */
    function <?= $field->getSetterName() ?>($value) {
      $this->setFieldValue('<?= $field->getName() ?>', $value);
    } // <?= $field->getSetterName() ?> 
    