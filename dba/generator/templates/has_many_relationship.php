
    /**
    * Return an array of <?= $target_entity->getObjectClassName() ?> object that belong to this object
    *
    * @param boolean $reload
    * @param boolean $full
<?php if(!$relationship->getFinderSql()) { ?>
    * @param string $additional_conditions
<?php } // if ?>
    * @return array
    */
    function <?= $relationship->getGetterName() ?>($reload = false, $full = false<?php if(!$relationship->getFinderSql()) { ?>, $additional_conditions = null<?php } // if ?>) {
<?php if($relationship->getFinderSql()) { ?>
      $cache_key = '<?= $relationship->getName() ?>';
<?php } else { ?>
      $cache_key = '<?= $relationship->getName() ?>' . (string) $additional_conditions;
<?php } // if ?>
    
      if(isset($this->cache[$cache_key])) {
        if($reload) {
          unset($this->cache[$cache_key]);
        } else {
          return $this->cache[$cache_key];
        } // if
      } // if
      
<?php if($relationship->getFinderSql()) { ?>
      $this->cache[$cache_key] = <?= $target_entity->getManagerClassName() ?>::findBySql(<?= var_export($relationship->getFinderSql()) ?>);
<?php } else { ?>
      // Get conditions string
<?php if($relationship->getConditions()) { ?>
      $conditions = Angie_DB::prepareString(Angie_DB::getConnection()->escape('<?= $relationship->getForeignKey() ?>') . ' = ? AND ' . <?= var_export($relationship->getConditions()) ?>, $this->getInitialPkValue());
<?php } else { ?>
      $conditions = Angie_DB::prepareString(Angie_DB::getConnection()->escape('<?= $relationship->getForeignKey() ?>') . ' = ?', $this->getInitialPkValue());
<?php } // if ?>
      if($additional_conditions) {
        $conditions = "($conditions) AND ($additional_conditions)";
      } // if
      
      $this->cache[$cache_key] = <?= $target_entity->getManagerClassName() ?>::find(array(
        'conditions' => $conditions,
        'order' => <?= var_export($relationship->getOrder()) ?>,
      ), $full); // find
<?php } // if ?>

      return $this->cache[$cache_key];
    } // <?= $relationship->getGetterName() ?> 
    
    /**
    * Return number of <?= $target_entity->getObjectClassName() ?> object that belong to this object
    *
    * @param boolean $reload
<?php if(!$relationship->getFinderSql()) { ?>
    * @param string $additional_conditions
<?php } // if ?>
    * @return integer
    */
    function <?= $relationship->getCounterName() ?>($reload = false<?php if(!$relationship->getFinderSql()) { ?>, $additional_conditions = null<?php } // if ?>) {
<?php if($relationship->getFinderSql()) { ?>
      $cache_key = '<?= $relationship->getName() ?>_count';
<?php } else { ?>
      $cache_key = '<?= $relationship->getName() ?>_count' . (string) $additional_conditions;
<?php } // if ?>
      
      if(isset($this->cache[$cache_key])) {
        if($reload) {
          unset($this->cache[$cache_key]);
        } else {
          return $this->cache[$cache_key];
        } // if
      } // if
      
<?php if($relationship->getCounterSql()) { ?>
      $row = Angie_DB::getConnection()->executeOne(<?= var_export($relationship->getCounterSql()) ?>);
      $this->cache[$cache_key] = array_var($row, 'row_count', 0);
<?php } else { ?>
      // Get conditions string
<?php if($relationship->getConditions()) { ?>
      $conditions = Angie_DB::prepareString(Angie_DB::getConnection()->escape('<?= $relationship->getForeignKey() ?>') . ' = ? AND ' . <?= var_export($relationship->getConditions()) ?>, $this->getInitialPkValue());
<?php } else { ?>
      $conditions = Angie_DB::prepareString(Angie_DB::getConnection()->escape('<?= $relationship->getForeignKey() ?>') . ' = ?', $this->getInitialPkValue());
<?php } // if ?>
      if($additional_conditions) {
        $conditions = "($conditions) AND ($additional_conditions)";
      } // if
      
      $this->cache[$cache_key] = <?= $target_entity->getManagerClassName() ?>::count($conditions);
<?php } // if ?>

      return $this->cache[$cache_key];
    } // <?= $relationship->getCounterName() ?> 
    
    /**
    * Add single <?= $target_entity->getObjectClassName() ?> to the collection
    *
    * If $save is true $value object will be saved after its key values is updated
    *
    * @param <?= $target_entity->getObjectClassName() ?> $value
    * @param $save
    * @return <?= $target_entity->getObjectClassName() ?> 
    */
    function <?= $relationship->getAdderName() ?>(<?= $target_entity->getObjectClassName() ?> $value, $save = true) {
      $value-><?= $relationship->getForeignKeySetter() ?>($this-><?= $relationship->getEntityPrimaryKeyGetter() ?>());
      
      if($save) {
        $value->save();
      } // if
      
      // Clean cache, values in it might be invalid now...
      foreach($this->cache as $k => $v) {
        if(str_starts_with($k, '<?= $relationship->getName() ?>')) {
          unset($this->cache[$k]);
        } // if
      } // foreach
      
      return $value;
    } // <?= $relationship->getAdderName() ?> 
    