    
    /**
    * Return collection of <?= $target_entity->getObjectClassName() ?> objects related through <?= $relationship->getJoinTable() ?>
    *
    * @param boolean $reload
    * @param string $additional_conditions
    * @param string $order
    * @return array
    */
    function <?= $relationship->getGetterName() ?>($reload = false, $additional_conditions = null, $order = null) {
      $trimmed_additional_conditions = trim($additional_conditions);
      if($trimmed_additional_conditions) {
        $reload = true;
      } // if
      
      $cache_key = "<?= $relationship->getName() ?>$trimmed_additional_conditions$order";
      
      if(isset($this->cache[$cache_key])) {
        if($reload) {
          unset($this->cache[$cache_key]);
        } else {
          return $this->cache[$cache_key];
        } // if
      } // if
      
<?php if(trim($relationship->getFinderSql())) { ?>
      $sql = Angie_DB::getConnection()->prepareString(<?= var_export($relationship->getFinderSql()) ?>, array($this-><?= $relationship->getOwnerEntityPrimaryKeyGetterName() ?>()));
      $this->cache[$cache_key] = <?= $target_entity->getManagerClassName() ?>::findBySql($sql);
<?php } else { ?>
      $additional_conditions = $trimmed_additional_conditions == '' ? '' : " AND ($trimmed_additional_conditions)";
      $connection = Angie_DB::getConnection();
      
      $table_prefix = trim(Angie::getConfig('db.table_prefix'));
      $target_table = $connection->escapeTableName($table_prefix . '<?= $target_entity->getTableName() ?>');
      $join_table = $connection->escapeTableName($table_prefix . '<?= $relationship->getJoinTable() ?>');
      
      if($order === null) {
<?php if(trim($relationship->getOrder()) == '') { ?>
        $order = '';
<?php } else { ?>
        $order = str_replace('#PREFIX#', Angie::getConfig('db.table_prefix'), ' ORDER BY ' . <?= var_export($relationship->getOrder()) ?>);
<?php } // if ?>
      } // if
      
      $this->cache[$cache_key] = <?= $target_entity->getManagerClassName() ?>::findBySql(sprintf('SELECT %s.* FROM %s, %s WHERE (%s.%s = %s AND %s.%s = %s.%s)%s%s',
        $target_table,
        $target_table,
        $join_table,
        $join_table,
        $connection->escapeFieldName('<?= $relationship->getOwnerKey() ?>'),
        $connection->escape($this-><?= $relationship->getOwnerEntityPrimaryKeyGetterName() ?>()),
        $join_table,
        $connection->escapeFieldName('<?= $relationship->getTargetKey() ?>'),
        $target_table,
        $connection->escapeFieldName('<?= $relationship->getTargetEntityPrimaryKeyName() ?>'),
        $additional_conditions,
        $order
      )); // findBySql
<?php } // if ?>

      $this->cache['<?= $relationship->getName() ?>_count'] = is_array($this->cache[$cache_key]) ? count($this->cache[$cache_key]) : 0;
      return $this->cache[$cache_key];
    } // <?= $relationship->getGetterName() ?> 
    
    /**
    * Set collection of <?= $target_entity->getObjectClassName() ?> objects
    *
    * Current collection will be cleaned to make place for new one
    *
    * @param array $collection
    * @return null
    */
    function <?= $relationship->getSetterName() ?>($collection) {
      $this-><?= $relationship->getCleanerName() ?>();
      if(is_foreachable($collection)) {
        foreach($collection as &$collection_item) {
          if($collection_item instanceof <?= $target_entity->getObjectClassName() ?>) {
            $this-><?= $relationship->getAdderName() ?>($collection_item);
          } else {
            throw new Angie_Core_Error_InvalidParamValue('collection', $collection, '$collection should be an array of <?= $target_entity->getObjectClassName() ?> objects');
          } // if
        } // foreach
      } // if
    } // <?= $relationship->getSetterName() ?> 
    
    /**
    * Return number of related <?= $target_entity->getObjectClassName() ?> objects
    *
    * @param boolean $reload
    * @param string $additional_conditions
    * @return integer
    */
    function <?= $relationship->getCounterName() ?>($reload = false, $additional_conditions = null) {
      $trimmed_additional_conditions = trim($additional_conditions);
      if($trimmed_additional_conditions) {
        $reload = true;
      } // if
      
      $cache_key = "<?= $relationship->getName() ?>$trimmed_additional_conditions" . '_count';
      
      if(isset($this->cache[$cache_key])) {
        if($reload) {
          unset($this->cache[$cache_key]);
        } else {
          return $this->cache[$cache_key];
        } // if
      } // if
      
<?php if(trim($relationship->getCounterSql())) { ?>
      $row = Angie_DB::getConnection()->executeOne(<?= var_export($relationship->getCounterSql()) ?>, $this-><?= $relationship->getOwnerEntityPrimaryKeyGetterName() ?>(), $value-><?= $relationship->getTargetEntityPrimaryKeyGetterName ?>());
<?php } else { ?>
      $additional_conditions = $trimmed_additional_conditions == '' ? '' : " AND ($trimmed_additional_conditions)";
      $connection = Angie_DB::getConnection();
      $row = $connection->executeOne(sprintf("SELECT COUNT(*) AS 'row_count' FROM %s WHERE (%s = %s)%s",
        $connection->escapeTableName(trim(Angie::getConfig('db.table_prefix')) . '<?= $relationship->getJoinTable() ?>'),
        $connection->escapeFieldName('<?= $relationship->getOwnerKey() ?>'),
        $connection->escape($this-><?= $relationship->getOwnerEntityPrimaryKeyGetterName() ?>()),
        $additional_conditions
      )); // execute
<?php } // if ?>

      $this->cache[$cache_key] = array_var($row, 'row_count', 0);
      return $this->cache[$cache_key];
    } // <?= $relationship->getCounterName() ?> 
    
    /**
    * Add a single <?= $target_entity->getObjectClassName() ?> object to the collection
    *
    * @param <?= $target_entity->getObjectClassName() ?> $value
    * @return <?= $target_entity->getObjectClassName() ?> 
    */
    function <?= $relationship->getAdderName() ?>(<?= $target_entity->getObjectClassName() ?> $value) {
      if($this->isNew()) {
        $this->save();
      } // if
      
      if($value->isNew()) {
        $value->save();
      } // if
    
<?php if(trim($relationship->getAdderSql())) { ?>
      Angie_DB::getConnection()->execute(<?= var_export($relationship->getAdderSql()) ?>, $this-><?= $relationship->getOwnerEntityPrimaryKeyGetterName() ?>(), $value-><?= $relationship->getTargetEntityPrimaryKeyGetterName ?>());
<?php } else { ?>
      $connection = Angie_DB::getConnection();
      $connection->execute(sprintf('INSERT INTO %s (%s, %s) VALUES (%s, %s)',
        $connection->escapeTableName(trim(Angie::getConfig('db.table_prefix')) . '<?= $relationship->getJoinTable() ?>'),
        $connection->escapeFieldName('<?= $relationship->getOwnerKey() ?>'),
        $connection->escapeFieldName('<?= $relationship->getTargetKey() ?>'),
        $connection->escape($this-><?= $relationship->getOwnerEntityPrimaryKeyGetterName() ?>()),
        $connection->escape($value-><?= $relationship->getTargetEntityPrimaryKeyGetterName() ?>())
      )); // execute
<?php } // if  ?>
      if(isset($this->cache['<?= $relationship->getName() ?>'])) {
        $this->cache['<?= $relationship->getName() ?>'][] = $value;
      } else {
        $this->cache['<?= $relationship->getName() ?>'] = array($value);
      } // if
      
      return $value;
    } // <?= $relationship->getAdderName() ?> 
    
    /**
    * Remove specific <?= $target_entity->getObjectClassName() ?> object from the collection
    *
    * @param <?= $target_entity->getObjectClassName() ?> $value
    * @return null
    */
    function <?= $relationship->getDeleterName() ?>(<?= $target_entity->getObjectClassName() ?> $value) {
<?php if(trim($relationship->getDeleterSql())) { ?>
      Angie_DB::getConnection()->execute(<?= var_export($relationship->getDeleterSql()) ?>, $this-><?= $relationship->getOwnerEntityPrimaryKeyGetterName() ?>(), $value-><?= $relationship->getTargetEntityPrimaryKeyGetterName ?>());
<?php } else { ?>
      $connection = Angie_DB::getConnection();
      $connection->execute(sprintf('DELETE FROM %s WHERE %s = %s AND %s = %s',
        $connection->escapeTableName(trim(Angie::getConfig('db.table_prefix')) . '<?= $relationship->getJoinTable() ?>'),
        $connection->escapeFieldName('<?= $relationship->getOwnerKey() ?>'),
        $connection->escape($this-><?= $relationship->getOwnerEntityPrimaryKeyGetterName() ?>()),
        $connection->escapeFieldName('<?= $relationship->getTargetKey() ?>'),
        $connection->escape($value-><?= $relationship->getTargetEntityPrimaryKeyGetterName() ?>())
      )); // execute
<?php } // if  ?>
      
      if(isset($this->cache['<?= $relationship->getName() ?>']) && is_foreachable($this->cache['<?= $relationship->getName() ?>'])) {
        foreach($this->cache['<?= $relationship->getName() ?>'] as $k => $v) {
          if($v-><?= $relationship->getTargetEntityPrimaryKeyGetterName() ?>() == $value-><?= $relationship->getTargetEntityPrimaryKeyGetterName() ?>()) {
            unset($this->cache['<?= $relationship->getName() ?>'][$k]);
            break;
          } // if
        } // foreach
      } // if
    } // <?= $relationship->getDeleterName() ?> 
    
    /**
    * Delete all relations between this object and related <?= $target_entity->getObjectClassName() ?> objects
    *
    * @param void
    * @return integer
    */
    function <?= $relationship->getAllRelationsDeleterName() ?>() {
<?php if(trim($relationship->getCleanerSql())) { ?>
      Angie_DB::getConnection()->execute(<?= var_export($relationship->getCleanerSql()) ?>, $this-><?= $relationship->getOwnerEntityPrimaryKeyGetterName() ?>());
<?php } else { ?>
      $connection = Angie_DB::getConnection();
      $connection->execute(sprintf('DELETE FROM %s WHERE %s = %s',
        $connection->escapeTableName(trim(Angie::getConfig('db.table_prefix')) . '<?= $relationship->getJoinTable() ?>'),
        $connection->escapeFieldName('<?= $relationship->getOwnerKey() ?>'),
        $connection->escape($this-><?= $relationship->getOwnerEntityPrimaryKeyGetterName() ?>())
      )); // execute
<?php } // if  ?>
      if(isset($this->cache['<?= $relationship->getName() ?>'])) {
        unset($this->cache['<?= $relationship->getName() ?>']);
      } // if
    } // <?= $relationship->getAllRelationsDeleterName() ?> 
    
    /**
    * Delete all realted <?= $target_entity->getObjectClassName() ?> objects. This will cascade relation deletation too
    *
    * @param void
    * @return null
    */
    function <?= $relationship->getCleanerName() ?>() {
      $related_objects = $this-><?= $relationship->getGetterName() ?>();
      if(is_foreachable($related_objects)) {
        foreach($related_objects as $related_object) {
          $related_object->delete();
        } // foreach
      } // if
      
      if(isset($this->cache['<?= $relationship->getName() ?>'])) {
        unset($this->cache['<?= $relationship->getName() ?>']);
      } // if
      
      if(isset($this->cache['<?= $relationship->getName() ?>_count'])) {
        unset($this->cache['<?= $relationship->getName() ?>_count']);
      } // if
    } // <?= $relationship->getCleanerName() ?> 
    