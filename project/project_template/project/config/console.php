<?= '<?php' ?>

  
  // ---------------------------------------------------
  //  Project settings
  // ---------------------------------------------------
  
  Angie::setConfig('project.name', '<?= $project_name ?>');
  Angie::setConfig('project.url', '');
  
  // ---------------------------------------------------
  //  System settings
  // ---------------------------------------------------
  
  Angie::setConfig('system.default_application', 'default');
  Angie::setConfig('system.default_controller', 'default');
  Angie::setConfig('system.default_action', 'index');
  Angie::setConfig('system.engine', '<?= Angie_Inflector::camelize($project_name) ?>Engine');
  Angie::setConfig('system.template_engine', 'Angie_TemplateEngine_Php');
  Angie::setConfig('system.debugging', true);
  Angie::setConfig('system.routing', false);
  
  // ---------------------------------------------------
  //  Database connection
  // ---------------------------------------------------
  //
  // Angie::setConfig('test_db.connect', true);
  // Angie::setConfig('test_db.hostname', '');
  // Angie::setConfig('test_db.username', '');
  // Angie::setConfig('test_db.password', '');
  // Angie::setConfig('test_db.name', '');
  // Angie::setConfig('test_db.persist', true);
  // Angie::setConfig('db.table_prefix', ''); // runtime table prefix config
  
  // ---------------------------------------------------
  //  MySQL generator related settings
  // ---------------------------------------------------
  //
  // Angie::setConfig('mysql.default_engine',    'InnoDB');
  // Angie::setConfig('mysql.default_charset',   'utf8');
  // Angie::setConfig('mysql.default_collation', 'utf8_general_ci');

<?= '?>' ?>