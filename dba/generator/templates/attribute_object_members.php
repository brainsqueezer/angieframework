
    /**
    * Return value of '<?= $attribute->getName() ?>' field
    *
    * @return <?= $attribute->getNativeType() ?> 
    */
    function <?= $attribute->getGetterName() ?>() {
      return $this->getFieldValue('<?= $attribute->getName() ?>');
    } // <?= $attribute->getGetterName() ?> 
    
    /**
    * Set value of '<?= $attribute->getName() ?>' field
    *
    * @param <?= $attribute->getNativeType() ?> $value
    * @return <?= $attribute->getNativeType() ?> 
    */
    function <?= $attribute->getSetterName() ?>($value) {
      return $this->setFieldValue('<?= $attribute->getName() ?>', $value);
    } // <?= $attribute->getSetterName() ?> 