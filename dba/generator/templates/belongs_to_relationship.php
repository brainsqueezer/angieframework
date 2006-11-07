
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
      $this->cache['<?= $relationship->getName() ?>'] = <?= $target_entity->getManagerClassName() ?>::findBySql(<?= var_export($relationship->getFinderSql()) ?>, true);
<?php } else { ?>
      $conditions = array_merge(
<?php if($relationship->getConditions()) { ?>
        array(Angie_DB::getConnection()->escape('<?= $relationship->getTargetEntityPrimaryKeyName() ?>') . ' = ? AND ' . <?= var_export($relationship->getConditions()) ?>),
<?php } else { ?>
        array(Angie_DB::getConnection()->escape('<?= $relationship->getTargetEntityPrimaryKeyName() ?>') . ' = ?'),
<?php } // if ?>
        $this-><?= $relationship->getForeignKeyGetterName() ?>()
      ); // array_merge
      
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
      if($value instanceof <?= $target_entity->getObjectClassName() ?>) {
        if($this-><?= $relationship->getForeignKeyGetterName() ?>() <> $value-><?= $relationship->getTargetEntityPrimaryKeyGetter() ?>()) {
          $this-><?= $relationship->getForeignKeySetterName() ?>($value-><?= $relationship->getTargetEntityPrimaryKeyGetter() ?>());
          $this->cache['<?= $relationship->getName() ?>'] = $value;
        } // if
      } elseif(is_null($value)) {
        if($this-><?= $relationship->getForeignKeyGetterName() ?>() <> 0) {
          $this-><?= $relationship->getForeignKeySetterName() ?>(0);
          $this->cache['<?= $relationship->getName() ?>'] = null;
        } // if
      } else {
        throw new Angie_Core_Error_InvalidParamValue('value', $value, '$value should be a NULL or an instance of <?= $target_entity->getObjectClassName() ?> class');
      } // if
      
      if($save) {
        $this->save();
      } // if
      
      return $value;
    } // <?= $relationship->getSetterName() ?> 
    