<?= '<?php' ?>


  // ---------------------------------------------------
  //  Project settings
  // ---------------------------------------------------
  
  Angie::setConfig('project.name', <?= var_export($project_name) ?>);
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
  Angie::setConfig('system.routing', true);
  
  // ---------------------------------------------------
  //  Database connection
  // ---------------------------------------------------
  //
  // Angie::setConfig('db.connect_on_init', true);
  // Angie::setConfig('db.hostname',        '');
  // Angie::setConfig('db.username',        '');
  // Angie::setConfig('db.password',        '');
  // Angie::setConfig('db.name',            '');
  // Angie::setConfig('db.persist',         true);
  // Angie::setConfig('db.table_prefix',    '');
  
  // ---------------------------------------------------
  //  MySQL generator related settings
  // ---------------------------------------------------
  //
  // Angie::setConfig('mysql.default_engine',    'InnoDB');
  // Angie::setConfig('mysql.default_charset',   'utf8');
  // Angie::setConfig('mysql.default_collation', 'utf8_general_ci');

<?= '?>' ?>