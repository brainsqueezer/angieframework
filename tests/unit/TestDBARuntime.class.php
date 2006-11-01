<?php

  class TestDBARuntime extends UnitTestCase {
    
    private $test_dir;
  
    /**
    * Constructor
    *
    * @param void
    * @return TestDBARuntime
    */
    function __construct() {
      $this->UnitTestCase('Test DBA runtime');
      $this->test_dir = dirname(__FILE__) . '/dba_generator/';
    } // __construct
    
    function setUp() {
      include $this->test_dir . 'test_description.php';
      
      if(!class_exists('BaseUser')) require $this->test_dir . 'output/users/base/BaseUser.class.php';
      if(!class_exists('BaseUsers')) require $this->test_dir . 'output/users/base/BaseUsers.class.php';
      if(!class_exists('User')) require $this->test_dir . 'output/users/User.class.php';
      if(!class_exists('Users')) require $this->test_dir . 'output/users/Users.class.php';
      
      if(!class_exists('BaseCompany')) require $this->test_dir . 'output/companies/base/BaseCompany.class.php';
      if(!class_exists('BaseCompanies')) require $this->test_dir . 'output/companies/base/BaseCompanies.class.php';
      if(!class_exists('Company')) require $this->test_dir . 'output/companies/Company.class.php';
      if(!class_exists('Companies')) require $this->test_dir . 'output/companies/Companies.class.php';
      
      Angie_DB::execute("CREATE TABLE IF NOT EXISTS `generator_companies` (
        `id` smallint(5) unsigned NOT NULL auto_increment,
        `name` varchar(100) NOT NULL default '',
        `created_by_id` smallint(5) unsigned NOT NULL default '0',
        PRIMARY KEY  (`id`)
      );");

      Angie_DB::execute("CREATE TABLE IF NOT EXISTS `generator_users` (
        `id` smallint(5) unsigned NOT NULL auto_increment,
        `company_id` smallint(5) unsigned NOT NULL default '0',
        `username` varchar(50) NOT NULL default '',
        `email` varchar(100) NOT NULL default '',
        `display_name` varchar(100) NOT NULL default '',
        `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
        `created_by_id` smallint(5) unsigned NOT NULL default '0',
        `updated_on` datetime NOT NULL default '0000-00-00 00:00:00',
        `updated_by_id` smallint(5) unsigned NOT NULL default '0',
        PRIMARY KEY  (`id`)
      )");
    } // setUp
    
    function tearDown() {
      Angie_DBA_Generator::cleanUp();
      Angie_DB::execute("DROP TABLE `generator_users`");
      Angie_DB::execute("DROP TABLE `generator_companies`");
    } // tearDown
    
    function testCrud() {
      $user = new User();
      $user->setUsername('Ilija Studen');
      $user->setEmail('ilija.studen@gmail.com');
      $this->assertTrue($user->save());
      $this->assertEqual($user->getId(), 1);
      
      $user->setEmail('ilija.studen@activecollab.com');
      $this->assertTrue($user->save());
      
      $loaded_user = Users::findById($user->getId());
      $this->assertEqual($loaded_user->getId(), $user->getId());
      $this->assertEqual($loaded_user->getUsername(), $user->getUsername());
      $this->assertEqual($loaded_user->getEmail(), $user->getEmail(), 'Details not loaded!'); // detail
      
      $this->assertTrue($user->delete());
      $loaded_user = Users::findById($user->getId());
      
      $this->assertEqual($loaded_user, null);
    } // testCreation
    
    function testFinders() {
      $activecollab = new Company();
      $activecollab->setName('activeCollab');
      $activecollab->save();
      
      $ilija = new User();
      $ilija->setFromAttributes(array(
        'username' => 'ilija',
        'email' => 'ilija@activecollab.com',
        'company_id' => $activecollab->getID()
      ));
      
      $oliver = new User();
      $oliver->setFromAttributes(array(
        'username' => 'oliver',
        'email' => 'oliver@activecollab.com',
        'company_id' => $activecollab->getID()
      ));
      
      $goran = new User();
      $goran->setFromAttributes(array(
        'username' => 'goran',
        'email' => 'goran@activecollab.com',
        'company_id' => $activecollab->getID()
      ));
      
      $this->assertTrue($ilija->save());
      $this->assertTrue($oliver->save());
      $this->assertTrue($goran->save());
    } // testFinders
  
  } // TestDBARuntime

?>