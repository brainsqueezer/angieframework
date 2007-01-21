<?= '<?php' ?>


  /**
  * <?= $project_name ?> application engine
  *
  * @package <?= $project_name ?>.application
  */
  class <?= Angie_Inflector::camelize($project_name) ?>Engine extends Angie_Engine {
  
//    /**
//    * Initialize engine
//    *
//    * @param void
//    * @return null
//    */
//    function init() {
//      if(Angie::getConfig('db.connect_on_init')) {
//        $hostname = Angie::getConfig('db.hostname');
//        $username = Angie::getConfig('db.username');
//        $password = Angie::getConfig('db.password');
//        $database = Angie::getConfig('db.name');
//        $persist  = Angie::getConfig('db.persist');
//        
//        Doctrine_Manager::connection(new Doctrine_Db("mysql://$username:$password@$hostname/$database"));
//        Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_VLD, true); // enable validation
//      } // if
//    } // init
  
  } // <?= Angie_Inflector::camelize($project_name) ?>Engine


<?= '?>' ?>