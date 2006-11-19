<?= '<?php' ?>


  /**
  * Collect and run test for the framework and for the current project
  *
  * @package <?= $project_name ?>.development
  */
  
  define('ANGIE_ENVIRONMENT', 'test');
  require realpath(dirname(__FILE__) . '/../../init.php');
  
  if(!is_dir(ANGIE_PATH . '/vendor/simpletest')) {
    die('Simpletest framework is required. Download it from http://www.lastcraft.com and extract it into /vendors folders');
  } // if
  require ANGIE_PATH . '/vendor/simpletest/unit_tester.php';
  require ANGIE_PATH . '/vendor/simpletest/reporter.php';
  
  // Connect to database...
  if(Angie::getConfig('test_db.connect')) {
    Angie_DB::setConnection(new Angie_DB_MySQL_Connection(array(
      'hostname' => Angie::getConfig('test_db.hostname'),
      'username' => Angie::getConfig('test_db.username'),
      'password' => Angie::getConfig('test_db.password'),
      'name'     => Angie::getConfig('test_db.name'),
      'persist'  => Angie::getConfig('test_db.persist')
    ))); // Angie_DB_MySQL_Connection
  } // if
  
  $all_test_files = array();
  $angie_tests = collect_test_from_dir(ANGIE_PATH . '/tests');
  $project_tests = collect_test_from_dir(DEVELOPMENT_PATH . '/tests');
  
  if(is_array($angie_tests)) {
    $all_test_files = $angie_tests;
  } // if
  
  if(is_array($project_tests)) {
    $all_test_files = array_merge($all_test_files, $project_tests);
  } // if
  
  $all_tests_group = new GroupTest('All tests');
  
  if(count($all_test_files)) {
    foreach($all_test_files as $file) {
      $all_tests_group->addTestFile($file);
    } // foreach
  } // if
  
  $all_tests_group->run(new TextReporter());

<?= '?>' ?>