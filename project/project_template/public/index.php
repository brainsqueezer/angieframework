<?= '<?php' ?>

  /**
  * Entry point
  * 
  * All public requests go thorugh here. This function will call project init file, construct and set a request object 
  * (routed or get) and call execute action. From that point its all on the application
  *
  * @package <?= $project_name ?>.public
  */

  define('ANGIE_ENVIRONMENT', 'development');
  require_once '../init.php';
  
  if(isset($_GET['_request_type']) && ($_GET['_request_type'] == 'routed')) {
    if(isset($_SERVER['REQUEST_URI'])) {
      Angie::engine()->setRequest(new Angie_Request_Routed(get_request_string()));
    } else {
      Angie::engine()->setRequest(new Angie_Request_Routed(array_var($_GET, '_request_path')));
    } // if
  } else {
    Angie::engine()->setRequest(new Angie_Request_Get(''));
  } // if
  
  Angie::engine()->execute();

<?= '?>' ?>