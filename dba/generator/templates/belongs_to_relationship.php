
    /**
    * Return <?= $target_entity->getObjectClassName() ?> object related by `<?= $relationship->getForeignKey() ?>` field
    *
    * If $reload is set to true object will be refreshed - loaded from database. If false script will first try too look 
    * in the cache and laod the object only if it haven't been loaded already and cached
    *
    * Set $full to true if you wish to automatically load detail fields
    *
    * @param boolean $reload
    * @return <?= $target_entity->getObjectClassName() ?> 
    */
    function <?= $relationship->getGetterName() ?>($reload = false, $full = false) {
      if(isset($this->cache['<?= $relationship->getName() ?>'])) {
        if($reload) {
          unset($this->cache['<?= $relationship->getName() ?>']);
        } else {
          return $this->cache['<?= $relationship->getName() ?>'];
        } // if
      } // if
      
<?php if($relationship->getFinderSql()) { ?>
      $finder_sql = Angie_DB::prepareString(<?= var_export($relationship->getFinderSql()) ?>, array($this-><?= $relationship->getForeignKeyGetterName() ?>()));
      $this->cache['<?= $relationship->getName() ?>'] = <?= $target_entity->getManagerClassName() ?>::findBySql($finder_sql, true);
<?php } else { ?>
      $connection = Angie_DB::getConnection();
<?php if($relationship->getConditions()) { ?>
      $conditions = $connection->prepareString($connection->escapeFieldName('<?= $relationship->getTargetEntityPrimaryKeyName() ?>') . ' = ? AND ' . <?= var_export($relationship->getConditions()) ?>, array($this-><?= $relationship->getForeignKeyGetterName() ?>()));
<?php } else { ?>
      $conditions = $connection->prepareString($connection->escapeFieldName('<?= $relationship->getTargetEntityPrimaryKeyName() ?>') . ' = ?', array($this-><?= $relationship->getForeignKeyGetterName() ?>()));
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
    * it is not valid Angie_Core_Error_InvalidParamValue will be thrown
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
      
        // If there is a object set and it is not saved lets forget about previous arangemens (that he'll inform us 
        // when he gets saved) and put a new value
        $object_from_cache = $this->cache['<?= $relationship->getName() ?>'];
        if(($object_from_cache instanceof Angie_DBA_Object) && $object_from_cache->isNew()) {
          $object_from_cache->removeUnsavedRelatedObject('<?= $relationship->getName() ?>', false, $this);
        } // if
      
        if($this-><?= $relationship->getForeignKeyGetterName() ?>() <> 0) {
          $this-><?= $relationship->getForeignKeySetterName() ?>(0);
          $this->cache['<?= $relationship->getName() ?>'] = null;
        } // if
        
      } elseif($value instanceof <?= $target_entity->getObjectClassName() ?>) {
      
        // Set this in cache and make sure that $value informs this object when it gets saved
        if($value->isNew()) {
          $value->addUnsavedRelatedObject($this, '<?= $relationship->getForeignKeySetterName() ?>', '<?= $relationship->getName() ?>', false, $save);
          $this->cache['<?= $relationship->getName() ?>'] = $value;
          
        } else {
        
          // Set $value only if ID-s are different
          if($this-><?= $relationship->getForeignKeyGetterName() ?>() <> $value-><?= $relationship->getTargetEntityPrimaryKeyGetter() ?>()) {
            $this-><?= $relationship->getForeignKeySetterName() ?>($value-><?= $relationship->getTargetEntityPrimaryKeyGetter() ?>());
            $this->cache['<?= $relationship->getName() ?>'] = $value;
          } // if
          
        } // if
        
      } else {
        throw new Angie_Core_Error_InvalidParamValue('value', $value, '$value should be a NULL or an instance of <?= $target_entity->getObjectClassName() ?> class');
      } // if
      
      if($save) {
        if($value instanceof Angie_DBA_Object) {
          if($value->isLoaded()) {
            $this->save();
          } // if
        } else {
          $this->save();
        } // if
      } // if
      
      return $value;
    } // <?= $relationship->getSetterName() ?> 
    