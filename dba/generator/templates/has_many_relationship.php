    /**
    * Return an array of <?= $target_entity->getObjectClassName() ?> object that belong to this object
    *
    * @param boolean $reload
    * @param boolean $full
    * @return array
    */
    function <?= $relationship->getGetterName() ?>($reload = false, $full = false) {
      if(isset($this->cache['<?= $relationship->getRelationName() ?>'])) {
        if($reload) {
          unset($this->cache['<?= $relationship->getRelationName() ?>']);
        } else {
          return $this->cache['<?= $relationship->getRelationName() ?>'];
        } // if
      } // if
      
<?php if($relationship->getFinderSql()) { ?>
      $this->cache['<?= $relationship->getRelationName() ?>'] = <?= $target_entity->getManagerClassName() ?>::findBySql(<?= var_export($relationship->getFinderSql()) ?>);
<?php } else { ?>
      $conditions = array_merge(
<?php if($relationship->getConditions()) { ?>
        array(Angie_DB::getConnection()->escape('<?= $relationship->getForeignKey() ?>') . ' = ? AND ' . <?= var_export($relationship->getConditions()) ?>),
<?php } else { ?>
        array(Angie_DB::getConnection()->escape('<?= $relationship->getForeignKey() ?>') . ' = ?'),
<?php } // if ?>
        $this->getInitialPkValue()
      ); // array_merge
      
      $this->cache['<?= $relationship->getRelationName() ?>'] = <?= $target_entity->getManagerClassName() ?>::find(array(
        'conditions' => $conditions,
        'order' => <?= var_export($relationship->getOrder()) ?>,
      ), $full); // find
<?php } // if ?>

      return $this->cache['<?= $relationship->getRelationName() ?>'];
    } // <?= $relationship->getGetterName() ?> 
    
    /**
    * Return number of <?= $target_entity->getObjectClassName() ?> object that belong to this object
    *
    * @param boolean $reload
    * @return integer
    */
    function <?= $relationship->getCounterName() ?>($reload = false) {
      if(isset($this->cache['<?= $relationship->getRelationName() ?>_count'])) {
        if($reload) {
          unset($this->cache['<?= $relationship->getRelationName() ?>_count']);
        } else {
          return $this->cache['<?= $relationship->getRelationName() ?>_count'];
        } // if
      } // if
      
<?php if($relationship->getCounterSql()) { ?>
      $row = Angie_DB::getConnection()->executeOne(<?= var_export($relationship->getCounterSql()) ?>);
      $this->cache['<?= $relationship->getRelationName() ?>_count'] = array_var($row, 'row_count', 0);
<?php } else { ?>
      $conditions = array_merge(
<?php if($relationship->getConditions()) { ?>
        array(Angie_DB::getConnection()->escape('<?= $relationship->getForeignKey() ?>') . ' = ? AND ' . <?= var_export($relationship->getConditions()) ?>),
<?php } else { ?>
        array(Angie_DB::getConnection()->escape('<?= $relationship->getForeignKey() ?>') . ' = ?'),
<?php } // if ?>
        $this->getInitialPkValue()
      ); // array_merge
      
      $this->cache['<?= $relationship->getRelationName() ?>_count'] = <?= $target_entity->getManagerClassName() ?>::count($conditions);
<?php } // if ?>

      return $this->cache['<?= $relationship->getRelationName() ?>_count'];
    } // <?= $relationship->getCounterName() ?> 
    
    /**
    * Add single <?= $target_entity->getObjectClassName() ?> to the collection
    *
    * @param <?= $target_entity->getObjectClassName() ?> $value
    * @return <?= $target_entity->getObjectClassName() ?>
    */
    function <?= $relationship->getAdderName() ?>(<?= $target_entity->getObjectClassName() ?> $value) {
    
    } // <?= $relationship->getAdderName() ?> 
    