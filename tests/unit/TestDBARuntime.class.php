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
      
      if(!class_exists('BasePackage')) require $this->test_dir . 'output/packages/base/BasePackage.class.php';
      if(!class_exists('BasePackages')) require $this->test_dir . 'output/packages/base/BasePackages.class.php';
      if(!class_exists('Package')) require $this->test_dir . 'output/packages/Package.class.php';
      if(!class_exists('Packages')) require $this->test_dir . 'output/packages/Packages.class.php';
      
      Angie_DB::execute("DROP TABLE IF EXISTS `generator_users`");
      Angie_DB::execute("DROP TABLE IF EXISTS `generator_companies`");
      Angie_DB::execute("DROP TABLE IF EXISTS `generator_packages`");
      
      Angie_DB::execute("CREATE TABLE `generator_companies` (
        `id` smallint(5) unsigned NOT NULL auto_increment,
        `package_id` smallint(5) unsigned NOT NULL default '0',
        `name` varchar(100) NOT NULL default '',
        `created_by_id` smallint(5) unsigned NOT NULL default '0',
        PRIMARY KEY  (`id`)
      );");
      
      Angie_DB::execute("CREATE TABLE `generator_packages` (
        `id` smallint(5) unsigned NOT NULL auto_increment,
        `name` varchar(100) NOT NULL default '',
        PRIMARY KEY  (`id`)
      );");

      Angie_DB::execute("CREATE TABLE `generator_users` (
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
      delete_dir(dirname(__FILE__) . '/dba_generator/output/companies');
      delete_dir(dirname(__FILE__) . '/dba_generator/output/users');
      delete_dir(dirname(__FILE__) . '/dba_generator/output/packages');
      
      Angie_DBA_Generator::cleanUp();
      Angie_DB::execute("DROP TABLE `generator_users`");
      Angie_DB::execute("DROP TABLE `generator_companies`");
    } // tearDown
    
    function testProtections() {
      $user = new User();
      
      // Allowed
      $user->set(array(
        'username' => 'ilija',
      ));
      $this->assertEqual($user->getUsername(), 'ilija');
      
      // Protected
      $now = Angie_DateTime::now();
      $user->set(array(
        'created_on' => Angie_DateTime::now(),
      ));
      $this->assertEqual($user->getCreatedOn(), null);
      
      // Protected through setters
      $user->setCreatedOn($now);
      $this->assertEqual($user->getCreatedOn(), $now);
    } // testProtections
    
    function testCrud() {
      $user = new User();
      $user->setUsername('Ilija Studen');
      $user->setEmail('ilija.studen@gmail.com');
      $this->assertTrue($user->save());
      $this->assertEqual($user->getId(), 1);
      $this->assertEqual($user->getInitialPkValue(), array('id' => 1));
      
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
    
    function testAutoSetters() {
      $now = Angie_DateTime::now();
      
      $user = new User();
      $user->setUsername('Ilija Studen');
      $user->setEmail('ilija@ctivecollab.com');
      $this->assertTrue($user->save());
      
      $this->assertIsA($user->getCreatedOn(), 'Angie_DateTime');
      $this->assertEqual($user->getCreatedOn()->getTimestamp(), $now->getTimestamp());
      $this->assertEqual($user->getUpdatedOn(), null);
      
      $user->setEmail('ilija.other@activecollab.com');
      $user->save();
      
      $this->assertIsA($user->getCreatedOn(), 'Angie_DateTime');
      $this->assertIsA($user->getUpdatedOn(), 'Angie_DateTime');
      $this->assertEqual($user->getCreatedOn()->getTimestamp(), $now->getTimestamp());
      $this->assertEqual($user->getUpdatedOn()->getTimestamp(), $now->getTimestamp());
    } // testAutoSetters
    
    function testFinders() {
      $activecollab = new Company();
      $activecollab->setName('activeCollab');
      $activecollab->save();
      
      $new_ilija = new User();
      $new_ilija->set(array(
        'username' => 'ilija',
        'email' => 'ilija@activecollab.com',
        'company_id' => $activecollab->getId()
      ));
      
      $new_oliver = new User();
      $new_oliver->set(array(
        'username' => 'oliver',
        'email' => 'oliver@activecollab.com',
        'company_id' => 0
      ));
      
      $new_goran = new User();
      $new_goran->set(array(
        'username' => 'goran',
        'email' => 'goran@activecollab.com',
        'company_id' => $activecollab->getId()
      ));
      
      $this->assertTrue($new_ilija->save());
      $this->assertTrue($new_oliver->save());
      $this->assertTrue($new_goran->save());
      
      $ilija_id = $new_ilija->getId();
      $goran_id = $new_goran->getId();
      $oliver_id = $new_oliver->getId();
      
      // Find one
      $oliver = Users::findOne(array(
        'conditions' => array('username = ?', 'oliver')
      ));
      
      $this->assertIsA($oliver, 'User');
      $this->assertEqual($oliver->getId(), $oliver_id);
      $this->assertEqual($oliver->getUsername(), 'oliver');
      
      $goran = Users::findOne(array(
        'conditions' => 'username = ' . Angie_DB::escape('goran'),
      ));
      
      $this->assertIsA($goran, 'User');
      $this->assertEqual($goran->getId(), $goran_id);
      $this->assertEqual($goran->getUsername(), 'goran');
      
      $in_activecollab = Users::find(array(
        'conditions' => array('company_id = ?', $activecollab->getId())
      )); // find
      
      $this->assertTrue(is_array($in_activecollab) && (count($in_activecollab) == 2));
      
      $oliver->setCompanyId($activecollab->getId());
      $oliver->save();
      
      $in_activecollab = Users::find(array(
        'conditions' => array('company_id = ?', $activecollab->getId())
      )); // find
      
      $this->assertTrue(is_array($in_activecollab) && (count($in_activecollab) == 3));
      
      $in_activecollab = Users::find(array(
        'conditions' => array('company_id = ?', $activecollab->getId()),
        'order' => 'username'
      )); // find
      
      $this->assertEqual(objects_array_extract($in_activecollab, 'getId'), array($goran_id, $ilija_id, $oliver_id));
      
      $in_activecollab_first = Users::findOne(array(
        'conditions' => array('company_id = ?', $activecollab->getId()),
        'order' => 'username'
      )); // find
      
      $this->assertIsA($in_activecollab_first, 'User');
      $this->assertEqual($in_activecollab_first->getId(), $goran_id);
      
      // Count
      
      $this->assertEqual(Users::count(array('username = ?', 'ilija')), 1);
      $this->assertEqual(Users::count(array('company_id = ?', $activecollab->getId())), 3);
      
      // Paginate
      list($objects, $pagination) = Users::paginate(array(
        'order' => 'username',
      ), 2, 1);
      $this->assertTrue(is_array($objects) && (count($objects) == 2));
      $this->assertEqual(objects_array_extract($objects, 'getId'), array($goran_id, $ilija_id));
      $this->assertIsA($pagination, 'Angie_Pagination');
      $this->assertEqual($pagination->getTotalPages(), 2);
      $this->assertEqual($pagination->getCurrentPage(), 1);
      
      list($objects, $pagination) = Users::paginate(array(
        'order' => 'username',
      ), 2, 2);
      $this->assertTrue(is_array($objects) && (count($objects) == 1));
      $this->assertEqual(objects_array_extract($objects, 'getId'), array($oliver_id));
      $this->assertIsA($pagination, 'Angie_Pagination');
      $this->assertEqual($pagination->getTotalPages(), 2);
      $this->assertEqual($pagination->getCurrentPage(), 2);
    } // testFinders
    
    function testValidators() {
      $activecollab = new Company();
      $activecollab->setName('activeCollab');
      $activecollab->save();
      
      $new_ilija = new User();
      $new_ilija->set(array(
        'username' => 'ilija',
        'email' => 'ilija@activecollab.com',
        'company_id' => $activecollab->getId()
      ));
      
      $new_oliver = new User();
      $new_oliver->set(array(
        'username' => 'oliver',
        'email' => 'oliver@activecollab.com',
        'company_id' => 0
      ));
      
      $new_goran = new User();
      $new_goran->set(array(
        'username' => 'goran',
        'email' => 'goran@activecollab.com',
        'company_id' => $activecollab->getId()
      ));
      
      $this->assertTrue($new_ilija->save());
      $this->assertTrue($new_oliver->save());
      $this->assertTrue($new_goran->save());
      
      $ilija_id = $new_ilija->getId();
      $goran_id = $new_goran->getId();
      $oliver_id = $new_oliver->getId();
      
      $new_ilija = new User();
      $new_ilija->setUsername('ilija');;
      $new_ilija->setEmail('ilija@activecollab.com');
      
      $now = Angie_DateTime::now();
      $new_ilija->setCreatedOn($now);
      
      $this->assertTrue($new_ilija->validatePresenceOf('username'));
      $this->assertTrue($new_ilija->validatePresenceOf('email'));
      $this->assertFalse($new_ilija->validatePresenceOf('company_id'));
      
      $this->assertFalse($new_ilija->validateUniquenessOf('username'));
      $this->assertFalse($new_ilija->validateUniquenessOf('email'));
      $this->assertFalse($new_ilija->validateUniquenessOf('username', 'email'));
      
      $new_ilija->setEmail('ilija.other@activecollab.com');
      $this->assertTrue($new_ilija->validateUniquenessOf('username', 'email'));
      
      $this->assertTrue($new_ilija->validateFormatOf('email', EMAIL_FORMAT));
      $this->assertFalse($new_ilija->validateFormatOf('email', URL_FORMAT));
      
      $this->assertTrue($new_ilija->validateMaxValueOf('username', 5)); // max acceptable lenght
      $this->assertFalse($new_ilija->validateMaxValueOf('username', 3)); // max acceptable lenght
      $this->assertTrue($new_ilija->validateMinValueOf('username', 4)); // min acceptable lenght
      $this->assertFalse($new_ilija->validateMinValueOf('username', 12)); // min acceptable lenght
      
      $future = $now->advance(600, false);
      $past   = $now->advance(-600, false);
      
      $this->assertTrue($new_ilija->validateMaxValueOf('created_on', $future));
      $this->assertFalse($new_ilija->validateMaxValueOf('created_on', $past));
      $this->assertTrue($new_ilija->validateMinValueOf('created_on', $past));
      $this->assertFalse($new_ilija->validateMinValueOf('created_on', $future));
    } // testValidators
    
    function testUnsavedObjectQue() {
      
      // ---------------------------------------------------
      //  Has many
      // ---------------------------------------------------
      
      $company = new Company();
      $company->setName('A51');
      
      $oliver = new User();
      $oliver->setUsername('oliver');
      
      $ilija = new User();
      $ilija->setUsername('ilija');
      
      $company->addUser($oliver, true);
      $company->addUser($ilija, false);
      
      $company->save();
      
      $this->assertEqual($oliver->getCompanyId(), $company->getId());
      $this->assertEqual($ilija->getCompanyId(), $company->getId());
      
      $this->assertFalse($oliver->isNew()); // needs to be saved
      $this->assertTrue($ilija->isNew()); // needs to be saved
      
      // ---------------------------------------------------
      //  Has one
      // ---------------------------------------------------
      
      $company = new Company();
      $company->setName('A52');
      
      $package = new Package();
      $package->setName('lite');
      
      $company->setPackage($package, true);
      
      $package->save();
      $this->assertEqual($company->getPackageId(), $package->getId());
      $this->assertFalse($company->isNew()); // it should be asved...
      
      $company = new Company();
      $company->setName('A53');
      
      $package = new Package();
      $package->setName('lite2');
      
      $company->setPackage($package, false);
      
      $package->save();
      $this->assertEqual($company->getPackageId(), $package->getId());
      $this->assertTrue($company->isNew()); // it should be new, but with package ID set
      
      // ---------------------------------------------------
      //  Has one, but reset
      // ---------------------------------------------------
      
      $company = new Company();
      $company->setName('A52');
      
      $package = new Package();
      $package->setName('lite');
      
      $company->setPackage($package, true);
      $company->setPackage(null);
      
      $package->save();
      
      $this->assertEqual($company->getPackageId(), null);
      
      // ---------------------------------------------------
      //  Belongs to
      // ---------------------------------------------------
      
      $ilija = new User();
      $ilija->setUsername('Ilija');
      
      $company = new Company();
      $company->setName('A51');
      
      $company->setCreatedBy($ilija, true);
      
      $ilija->save();
      
      $this->assertEqual($company->getCreatedById(), $ilija->getId());
      $this->assertFalse($company->isNew());
      
      $ilija = new User();
      $ilija->setUsername('Ilija');
      
      $company = new Company();
      $company->setName('A51');
      
      $company->setCreatedBy($ilija, false);
      
      $ilija->save();
      
      $this->assertEqual($company->getCreatedById(), $ilija->getId());
      $this->assertTrue($company->isNew());
      
      // ---------------------------------------------------
      //  Beongs to, but reseted
      // ---------------------------------------------------
      
      $company = new Company();
      $company->setName('A52');
      
      $user = new User();
      $user->setUsername('Ilija');
      
      $company->setCreatedBy($user, true);
      $company->setCreatedBy(null);
      
      $user->save();
      
      $this->assertEqual($company->getCreatedById(), null);
      
    } // testUnsavedObjectQue
    
    function testHasOneRelation() {
      $company = new Company();
      $company->setName('Company name');
      $this->assertTrue($company->save());
      
      $package = new Package();
      $package->setName('Some package');
      $package->save();
      
      $company->setPackage($package);
      
      $this->assertEqual($company->getPackageId(), $package->getId());
      
      $companies = $package->getCompanies();
      $this->assertTrue(is_array($companies) && (count($companies) == 1));
      $first_company = $companies[0];
      $this->assertIsA($first_company, 'Company');
      $this->assertNotIdentical($first_company, $company);
      $this->assertEqual($first_company->getId(), $company->getId());
    } // testHasOneRelation
    
    function testBelongsTo() {
      $user = new User();
      $user->setUsername('Ilija');
      $this->assertTrue($user->save());
      
      $company = new Company();
      $company->setName('Company name');
      $company->setCreatedBy($user);
      $this->assertTrue($company->save());
      
      $owned_companies = $user->getOwnedCompanies();
      $this->assertTrue(is_array($owned_companies) && (count($owned_companies) == 1));
      $first_company = $owned_companies[0];
      
      $this->assertEqual($first_company->getId(), $company->getId());
      $created_by = $first_company->getCreatedBy();
      
      $this->assertIsA($created_by, 'User');
      $this->assertNotEqual($created_by, $user);
      $this->assertEqual($created_by->getId(), $user->getId());
    } // testBelongsTo
    
    function testHasMany() {
      
    } // testHasMany
  
  } // TestDBARuntime

?>