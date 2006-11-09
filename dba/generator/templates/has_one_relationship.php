    
    /**
    * Return <?= $target_entity->getObjectClassName() ?> object related by `<?= $relationship->getForeignKey() ?>` field
    *
    * @param boolean $reload
    * @return <?= $target_entity->getObjectClassName() ?> 
    */  
    function <?= $relationship->getGetterName() ?>($reload = false) {
      if(isset($this->cache['<?= $relationship->getName() ?>'])) {
        if($reload) {
          unset($this->cache['<?= $relationship->getName() ?>']);
        } else {
          return $this->cache['<?= $relationship->getName() ?>'];
        } // if
      } // if
      
<?php if($relationship->getFinderSql()) { ?>
      $finder_sql = Angie_DB::prepareString(<?= var_export($relationship->getFinderSql()) ?>, array($this-><?= $relationship->getForeignKeyGetterName() ?>()));
      $this->cache['<?= $relationship->getName() ?>'] = <?= $target_entity->getManagerClassName() ?>::findBySql(<?= var_export($relationship->getFinderSql()) ?>, true);
<?php } else { ?>
<?php if($relationship->getConditions()) { ?>
      $conditions = Angie_DB::prepareString(Angie_DB::getConnection()->escapeFieldName('<?= $relationship->getForeignKey() ?>') . ' = ? AND ' . <?= var_export($relationship->getConditions()) ?>, $this->getInitialPkValue());
<?php } else { ?>
      $conditions = Angie_DB::prepareString(Angie_DB::getConnection()->escapeFieldName('<?= $relationship->getForeignKey() ?>') . ' = ?', $this->getInitialPkValue());
<?php } // if ?>
      
      $this->cache['<?= $relationship->getName() ?>'] = <?= $target_entity->getManagerClassName() ?>::find(array(
        'conditions' => $conditions,
        'order' => <?= var_export($relationship->getOrder()) ?>,
        'one' => true,
      ), $full); // find
<?php } // if ?>

      return $this->cache['<?= $relationship->getName() ?>'];
    } // <?= $relationship->getGetterName() ?> 
      
    /**
    * Set related <?= $target_entity->getObjectClassName() ?> object
    *
    * $value can be a valid <?= $target_entity->getObjectClassName() ?> instance or NULL (for reseting relationship). If 
    * it is not valid Angie_Core_Error_InvalidParamValue will be thrown. If $value is new object (not saved) it will be 
    * saved before it get used
    *
    * If $save is set to true changes will be saved after they are made. If not they will be just made, but its up to 
    * the programmer to actually save them
    *
    * @param <?= $target_entity->getObjectClassName() ?> $value
    * @param boolean $save
    * @return <?= $target_entity->getObjectClassName() ?> 
    * @throws Angie_Core_Error_InvalidParamValue
    */
    function <?= $relationship->getSetterName() ?>($value, $save = true) {
      if(is_null($value)) {
        $this->removeUnsavedRelatedObject('<?= $relationship->getForeignKeySetterName() ?>', false, $value);
        $this->cache['<?= $relationship->getName() ?>'] = null;
      } elseif($value instanceof <?= $target_entity->getObjectClassName() ?>) {
        if($this->isNew()) {
          $this->addUnsavedRelatedObject($value, '<?= $relationship->getForeignKeySetterName() ?>', '<?= $relationship->getName() ?>', true, $save);
        } else {
          $value-><?= $relationship->getForeignKeySetterName() ?>($this-><?= $relationship->getEntityPrimaryKeyGetter() ?>());
          if($save) {
            $value->save();
          } // if
        } // if
        
      } else {
        throw new Angie_Core_Error_InvalidParamValue('value', $value, '$value should be a NULL or an instance of <?= $target_entity->getObjectClassName() ?> class');
      } // if
      
      return $value;
    } // <?= $relationship->getSetterName() ?> 
    
    /**
    * Drop related object without calling its delete method
    *
    * This function will return number of affected rows. Additional conditions are use in child classes to provide a 
    * simple way for programming drop methods that use additional filtering
    *
    * @param string $additional_conditions
    * @return integer
    */
    protected function <?= $relationship->getDeleterName() ?>($additional_conditions = null) {
<?php if($relationship->getDeleterSql()) { ?>
      return Angie_DB::getConnection()->execute(<?= var_export($relationship->getDeleterSql()) ?>, $this->getInitialPkValue());
<?php } else { ?>
<?php if($relationship->getConditions()) { ?>
      $conditions = Angie_DB::prepareString(Angie_DB::getConnection()->escapeFieldName('<?= $relationship->getForeignKey() ?>') . ' = ? AND ' . <?= var_export($relationship->getConditions()) ?>, $this->getInitialPkValue());
<?php } else { ?>
      $conditions = Angie_DB::prepareString(Angie_DB::getConnection()->escapeFieldName('<?= $relationship->getForeignKey() ?>') . ' = ?', $this->getInitialPkValue());
<?php } // if ?>
      if($additional_conditions) {
        $conditions = "($conditions) AND ($additional_conditions)";
      } // if
      
      return <?= $target_entity->getManagerClassName() ?>::delete($conditions);
<?php } // if ?>
    } // <?= $relationship->getDeleterName() ?> 
    
    /**
    * Reset value of foreign key to 0 for related object
    *
    * This function will return number of affected rows. Additional conditions are use in child classes to provide a 
    * simple way for programming nullify methods that use additional filtering
    *
    * @param string $additional_conditions
    * @return integer
    */
    protected function <?= $relationship->getNullifierName() ?>($additional_conditions = null) {
<?php if($relationship->getNullifierSql()) { ?>
      return Angie_DB::getConnection()->execute(<?= var_export($relationship->getNullifierSql()) ?>, $this->getInitialPkValue());
<?php } else { ?>
<?php if($relationship->getConditions()) { ?>
      $conditions = Angie_DB::prepareString(Angie_DB::getConnection()->escapeFieldName('<?= $relationship->getForeignKey() ?>') . ' = ? AND ' . <?= var_export($relationship->getConditions()) ?>, $this->getInitialPkValue());
<?php } else { ?>
      $conditions = Angie_DB::prepareString(Angie_DB::getConnection()->escapeFieldName('<?= $relationship->getForeignKey() ?>') . ' = ?', $this->getInitialPkValue());
<?php } // if ?>
      if($additional_conditions) {
        $conditions = "($conditions) AND ($additional_conditions)";
      } // if
      
      return <?= $target_entity->getManagerClassName() ?>::update(array('<?= $relationship->getForeignKey() ?>' => 0), $conditions);
<?php } // if ?>
    } // <?= $relationship->getNullifierName() ?> 
    