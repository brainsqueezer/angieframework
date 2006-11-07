    
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
    * @return <?= $field->getNativeType() ?> 
    */
    function <?= $field->getSetterName() ?>($value) {
      return $this->setFieldValue('<?= $field->getName() ?>', $value);
    } // <?= $field->getSetterName() ?> 
    