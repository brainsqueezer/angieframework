<?= '<?php' ?>


  /**
  * Record class for <?= $model_name ?> model
  *
  * @package <?= $application_name ?>.model
  */
  class <?= $record_class ?> extends Angie_Doctrine_Record {
  
    /**
    * Use this function to describe your model
    *
    * @param void
    * @return null
    */
    public function setTableDefinition() {
      $this->setTableName(Angie::getConfig('db.table_prefix') . '<?= $table_name ?>');
    } // setTableDefinition
    
//    /**
//    * Use this function to define model relations
//    *
//    * @param void
//    * @return null
//    */
//    public function setUp() {
//    
//    } // setUp
  
  } // <?= $record_class ?> 

<?= '?>' ?>