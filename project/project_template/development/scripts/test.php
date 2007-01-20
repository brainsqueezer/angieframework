<?= '<?php' ?>

  /**
  * Collect and run test for the framework and for the current project
  *
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  
  ini_set('memory_limit', '32M');
  
  $run_start = microtime(true);
  
  define('ANGIE_ENVIRONMENT', 'console');
  
  if(!defined('ANGIE_INITED')) {
    require_once 'angie/init.php'; // init Angie if not already inited
  } // if
  
  set_exception_handler('console_exception_handler');
  
  if(!is_dir(ANGIE_PATH . '/vendor/simpletest')) {
    die("Simpletest framework is required. Download it from http://www.lastcraft.com and extract it into /vendors folders\n");
  } // if
  require ANGIE_PATH . '/vendor/simpletest/unit_tester.php';
  require ANGIE_PATH . '/vendor/simpletest/reporter.php';
  require ANGIE_PATH . '/vendor/simpletest/mock_objects.php';
  
  require_once realpath(dirname(__FILE__) . '/../../init.php');
  
  // ---------------------------------------------------
  //  Prepare database
  //
  //  - Read development tables
  //  - Clear test table
  //  - Create development tables in test database
  // ---------------------------------------------------

  $development_connection = new Angie_DB_MySQL_Connection(array(
    'hostname' => Angie::getConfig('db.hostname'),
    'username' => Angie::getConfig('db.username'),
    'password' => Angie::getConfig('db.password'),
    'name'     => Angie::getConfig('db.name'),
    'persist'  => true
  ));
  
  $development_tables = $development_connection->describeTables();
  
  Angie_DB::setConnection(new Angie_DB_MySQL_Connection(array(
    'hostname' => Angie::getConfig('test_db.hostname'),
    'username' => Angie::getConfig('test_db.username'),
    'password' => Angie::getConfig('test_db.password'),
    'name'     => Angie::getConfig('test_db.name'),
    'persist'  => true
  ))); // Angie_DB_MySQL_Connection
  
  Angie_DB::getConnection()->emptyDatabase(); // clear before any tests...
  
  foreach($development_tables as $development_table) {
    if($development_table->getCanUseMemory()) {
      $development_table->setEngine('Memory');
    } // if
    $development_table->buildTable(Angie_DB::getConnection());
  } // foreach
  
  // ---------------------------------------------------
  //  Load and run test cases
  // ---------------------------------------------------
  
  if(defined('TEST_SELF') && TEST_SELF) {
    $all_test_files = collect_test_from_dir(ANGIE_PATH . '/tests');
  } else {
    $all_test_files = collect_test_from_dir(Angie::engine()->getDevelopmentPath('tests'));
  } // if
  
  $all_tests_group = new GroupTest('All tests');
  
  if(count($all_test_files)) {
    foreach($all_test_files as $file) {
      $all_tests_group->addTestFile($file);
    } // foreach
  } // if
  
  $all_tests_group->run(new TextReporter());
  
  print "Time spent: " . number_format(microtime(true) - $run_start, 4, '.', '') . " seconds\n";
  
  if(function_exists('memory_get_usage')) {
    $memory_usage =  memory_get_usage();
    print "Memory usage: " . number_format($memory_usage / 1048576, 2, '.', '') . "MB ($memory_usage bytes)\n";
  } // if

<?= '?>' ?>