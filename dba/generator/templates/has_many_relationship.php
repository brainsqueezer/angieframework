
    /**
    * Return an array of <?= $target_entity->getObjectClassName() ?> object that belong to this object
    *
    * @param boolean $reload
    * @param boolean $full
<?php if(!$relationship->getFinderSql()) { ?>
    * @param string $additional
<?php } // if ?>
    * @return array
    */
    function <?= $relationship->getGetterName() ?>($reload = false, $full = false<?php if(!$relationship->getFinderSql()) { ?>, $additional = null<?php } // if ?>) {
      if($additional === null) {
        $additional_conditions = null;
        $additional_order = null;
        $additional_offset = null;
        $additional_limit = null;
        $additional_one = null;
      
        $cache_key = "<?= $relationship->getName() ?>";
      } elseif(is_string($additional)) {
        $additional_conditions = trim($additional);
        $additional_order = null;
        $additional_offset = null;
        $additional_limit = null;
        $additional_one = null;
        
        $cache_key = "<?= $relationship->getName() ?>$additional_conditions";
      } elseif(is_array($additional)) {
        $additional_conditions = array_var($additional, 'conditions');
        $additional_order = array_var($additional, 'order');
        $additional_offset = array_var($additional, 'offset');
        $additional_limit = array_var($additional, 'limit');
        $additional_one = array_var($additional, 'one');
      
        $cache_key = "<?= $relationship->getName() ?>$additional_conditions-$additional_order-$additional_offset-$additional_limit-$additional_one";
      } // if  
    
      if(isset($this->cache[$cache_key])) {
        if($reload) {
          unset($this->cache[$cache_key]);
        } else {
          return $this->cache[$cache_key];
        } // if
      } // if
      
      $connection = Angie_DB::getConnection();
      
<?php if($relationship->getFinderSql()) { ?>
      $finder_sql = $connection->prepareString(<?= var_export($relationship->getFinderSql()) ?>, $this->getInitialPkValue());
      $this->cache[$cache_key] = <?= $target_entity->getManagerClassName() ?>::findBySql($finder_sql);
<?php } else { ?>
      // Get conditions string
<?php if($relationship->getConditions()) { ?>
      $conditions = $connection->prepareString($connection->escapeFieldName('<?= $relationship->getForeignKey() ?>') . ' = ? AND ' . <?= var_export($relationship->getConditions()) ?>, $this->getInitialPkValue());
<?php } else { ?>
      $conditions = $connection->prepareString($connection->escapeFieldName('<?= $relationship->getForeignKey() ?>') . ' = ?', $this->getInitialPkValue());
<?php } // if ?>
      if($additional_conditions) {
        $conditions = "($conditions) AND ($additional_conditions)";
      } // if
      
      if($additional_order === null) {
        $order = <?= var_export($relationship->getOrder()) ?>;
      } else {
        $order = $additional_order;
      } // if
      
      $this->cache[$cache_key] = <?= $target_entity->getManagerClassName() ?>::find(array(
        'conditions' => $conditions,
        'order'      => $order,
        'limit'      => $additional_limit,
        'offset'     => $additional_offset,
        'one'        => $additional_one,
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
      $cache_key = "<?= $relationship->getName() ?>$additional_conditions" . '_count';
      
      if(isset($this->cache[$cache_key])) {
        if($reload) {
          unset($this->cache[$cache_key]);
        } else {
          return $this->cache[$cache_key];
        } // if
      } // if
      
      $connection = Angie_DB::getConnection();
      
<?php if($relationship->getCounterSql()) { ?>
      $row = $connection->executeOne(<?= var_export($relationship->getCounterSql()) ?>, $this->getInitialPkValue());
      $this->cache[$cache_key] = array_var($row, 'row_count', 0);
<?php } else { ?>
<?php if($relationship->getConditions()) { ?>
      $conditions = $connection->prepareString($connection->escapeFieldName('<?= $relationship->getForeignKey() ?>') . ' = ? AND ' . <?= var_export($relationship->getConditions()) ?>, $this->getInitialPkValue());
<?php } else { ?>
      $conditions = $connection->prepareString($connection->escapeFieldName('<?= $relationship->getForeignKey() ?>') . ' = ?', $this->getInitialPkValue());
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
      if($this->isNew()) {
        $this->addUnsavedRelatedObject($value, '<?= $relationship->getForeignKeySetter() ?>', '<?= $relationship->getName() ?>', true, $save);
      } else {
        $value-><?= $relationship->getForeignKeySetter() ?>($this-><?= $relationship->getEntityPrimaryKeyGetter() ?>());
        if($save) {
          $value->save();
        } // if
      } // if
      
      // Clean cache, values in it might be invalid now...
      foreach($this->cache as $k => $v) {
        if(str_starts_with($k, '<?= $relationship->getName() ?>')) {
          unset($this->cache[$k]);
        } // if
      } // foreach
      
      return $value;
    } // <?= $relationship->getAdderName() ?> 
    
    /**
    * Drop all related objects with a single DELETE query
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
      $connection = Angie_DB::getConnection();
<?php if($relationship->getConditions()) { ?>
      $conditions = $connection->prepareString($connection->escapeFieldName('<?= $relationship->getForeignKey() ?>') . ' = ? AND ' . <?= var_export($relationship->getConditions()) ?>, $this->getInitialPkValue());
<?php } else { ?>
      $conditions = $connection->prepareString($connection->escapeFieldName('<?= $relationship->getForeignKey() ?>') . ' = ?', $this->getInitialPkValue());
<?php } // if ?>
      if($additional_conditions) {
        $conditions = "($conditions) AND ($additional_conditions)";
      } // if
      
      return <?= $target_entity->getManagerClassName() ?>::delete($conditions);
<?php } // if ?>
    } // <?= $relationship->getDeleterName() ?> 
    
    /**
    * Reset value of foreign key to 0 for all related objects
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
      $connection = Angie_DB::getConnection();
<?php if($relationship->getConditions()) { ?>
      $conditions = $connection->prepareString($connection->escapeFieldName('<?= $relationship->getForeignKey() ?>') . ' = ? AND ' . <?= var_export($relationship->getConditions()) ?>, $this->getInitialPkValue());
<?php } else { ?>
      $conditions = $connection->prepareString($connection->escapeFieldName('<?= $relationship->getForeignKey() ?>') . ' = ?', $this->getInitialPkValue());
<?php } // if ?>
      if($additional_conditions) {
        $conditions = "($conditions) AND ($additional_conditions)";
      } // if
      
      return <?= $target_entity->getManagerClassName() ?>::update(array('<?= $relationship->getForeignKey() ?>' => 0), $conditions);
<?php } // if ?>
    } // <?= $relationship->getNullifierName() ?> 
    