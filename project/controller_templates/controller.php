<?= '<?php' ?>


  /**
  * <?= $controller_name ?> controller
  *
  * @package <?= $project_name ?>.<?= $application_name ?> 
  * @subpackage controllers
  */
<?php if(isset($app_controller_class) && $app_controller_class) { ?>
  class <?= $controller_class_name ?> extends <?= $app_controller_class ?> {
<?php } else { ?>
  class <?= $controller_class_name ?> {
<?php } // if ?>
  
    // Put your actions here
  
  } // <?= $controller_class_name ?> 
  
<?= '?>' ?>