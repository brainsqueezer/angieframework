<?= '<?php' ?>


  require_once 'base/<?= $entity->getBaseManagerClassName() ?>.class.php';
  require_once 'base/<?= $entity->getBaseObjectClassName() ?>.class.php';
  require_once '<?= $entity->getManagerClassName() ?>.class.php';

  /**
  * <?= $entity->getObjectClassName() ?> class
  */
  class <?= $entity->getObjectClassName() ?> extends <?= $entity->getBaseObjectClassName() ?> {
  
    // Put methods and properties specific for this entity in this class. 
    // Changes you make will be preserved even when your run the generator 
    // next time.
  
  } // <?= $entity->getObjectClassName() ?> 

<?= '?>' ?>