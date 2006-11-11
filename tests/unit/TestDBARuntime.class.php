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
      Angie_DB::execute("DROP TABLE IF EXISTS `generator_tags`");
      Angie_DB::execute("DROP TABLE IF EXISTS `generator_companies_tags`");
      
      Angie_DB::execute("CREATE TABLE `generator_companies` (
        `id` smallint(5) unsigned NOT NULL auto_increment,
        `name` varchar(100) NOT NULL default '',
        `created_by_id` smallint(5) unsigned NOT NULL default '0',
        PRIMARY KEY  (`id`)
      );");
      
      Angie_DB::execute("CREATE TABLE `generator_packages` (
        `id` smallint(5) unsigned NOT NULL auto_increment,
        `name` varchar(100) NOT NULL default '',
        `company_id` smallint(5) unsigned NOT NULL default '0',
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
      
      Angie_DB::execute("CREATE TABLE `generator_tags` (
        `id` smallint(5) unsigned NOT NULL auto_increment,
        `name` varchar(100) NOT NULL default '',
        PRIMARY KEY  (`id`)
      );");
      
      Angie_DB::execute("CREATE TABLE `generator_companies_tags` (
        `company_id` smallint(5)  NOT NULL,
        `tag_id` smallint(5) unsigned NOT NULL,
        PRIMARY KEY (`company_id`, `tag_id`)
      );");
      
    } // setUp
    
    function tearDown() {
      delete_dir(dirname(__FILE__) . '/dba_generator/output/companies');
      delete_dir(dirname(__FILE__) . '/dba_generator/output/users');
      delete_dir(dirname(__FILE__) . '/dba_generator/output/packages');
      delete_dir(dirname(__FILE__) . '/dba_generator/output/tags');
      
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
      $this->assertFalse($user->isNew());
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
      
      // We have a company and two user. First is added with save set to true, second with save set to false. On company 
      // save both users should get a company ID, but only first one should be saved
      
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
      
      // We have a new company, add a package with $save set to true. When we save a company company ID in package 
      // should be updated and the package should be saved
      
      $company = new Company();
      $company->setName('A52');
      
      $package = new Package();
      $package->setName('lite');
      
      $company->setPackage($package, true);
      
      $company->save();
      $this->assertEqual($company->getId(), $package->getCompanyId());
      $this->assertFalse($package->isNew()); // it should be asved...
      
      // We have a new company, add a package with $save set to false. When we save a company company ID in package 
      // should be updated on save, but package itself is not saved
      
      $company = new Company();
      $company->setName('A53');
      
      $package = new Package();
      $package->setName('lite2');
      
      $company->setPackage($package, false);
      
      $company->save();
      $this->assertEqual($company->getId(), $package->getCompanyId());
      $this->assertTrue($package->isNew()); // it should be new, but with package ID set
      
      // ---------------------------------------------------
      //  Has one, but reset
      // ---------------------------------------------------
      
      // We add a package to the company, and then remove it. On company save the package should not be updated (it 
      // should been removed from the que)
      
      $company = new Company();
      $company->setName('A52');
      
      $package = new Package();
      $package->setName('lite');
      
      $company->setPackage($package, true);
      $company->setPackage(null);
      
      $package->save();
      
      $this->assertEqual($package->getCompanyId(), null);
      
      // ---------------------------------------------------
      //  Belongs to
      // ---------------------------------------------------
      
      // User is added to the company with save set to true. On user save company should be updated and saved
      
      $ilija = new User();
      $ilija->setUsername('Ilija');
      
      $company = new Company();
      $company->setName('A51');
      
      $company->setCreatedBy($ilija, true);
      
      $ilija->save();
      
      $this->assertEqual($company->getCreatedById(), $ilija->getId());
      $this->assertFalse($company->isNew());
      
      // User is added to the company with save set to false. On user save company should be updated, but not saved
      
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
      
      // User is added to the company, and then removed. On user save company should not be updated (it should been 
      // removed from the que)
      
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
      
      $this->assertEqual($company->getId(), $package->getCompanyId());
      
      $company_by_package = $package->getCompany();
      $this->assertIsA($company_by_package, 'Company');
      $this->assertEqual($company_by_package->getId(), $company->getId());
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
    
    function testHasAndBelongsToMany() {
      
      // Define some tags and companies...
      $tag_1 = new Tag();
      $tag_2 = new Tag();
      $tag_3 = new Tag();
      
      $tag_1->save();
      $tag_2->save();
      $tag_3->save();
      
      $tag_1_id = $tag_1->getId();
      $tag_2_id = $tag_2->getId();
      $tag_3_id = $tag_3->getId();
      
      $company_1 = new Company();
      $company_2 = new Company();
      $company_3 = new Company();
      
      $company_1->save();
      $company_2->save();
      $company_3->save();
      
      $company_1_id = $company_1->getId();
      $company_2_id = $company_2->getId();
      $company_3_id = $company_3->getId();
      
      // Define relations...
      
      $company_1->setTags(array(
        $tag_1,
        $tag_2,
        $tag_3,
      ));
      
      // Check company 1 tags... There should be 3 tags
      
      $company_1_tags = $company_1->getTags(true);
      $this->assertEqual($company_1->countTags(true), 3);
      $this->assertTrue(is_array($company_1_tags) && (count($company_1_tags) == 3));
      $this->assertEqual(objects_array_extract($company_1_tags, 'getId'), array($tag_1_id, $tag_2_id, $tag_3_id));
      
      // Lets remove one tag... There should be left 2 tags and second tag should not be deleted
      $company_1->deleteTagRelation($tag_2);
      
      $company_1_tags = $company_1->getTags(true);
      $this->assertEqual($company_1->countTags(true), 2);
      $this->assertTrue(is_array($company_1_tags) && (count($company_1_tags) == 2));
      $this->assertEqual(objects_array_extract($company_1_tags, 'getId'), array($tag_1_id, $tag_3_id));
      
      $tag_2_reloaded = Tags::findById($tag_2_id);
      $this->assertIsA($tag_2_reloaded, 'Tag');
      
      // Now lets drop all relations. Tags should not be deleted
      $company_1->deleteTagRelations();
      
      $company_1_tags = $company_1->getTags(true);
      $this->assertEqual($company_1->countTags(true), 0);
      $this->assertEqual($company_1_tags, null);
      
      $tag_1_reloaded = Tags::findById($tag_1_id);
      $this->assertIsA($tag_1_reloaded, 'Tag');
      $tag_2_reloaded = Tags::findById($tag_2_id);
      $this->assertIsA($tag_2_reloaded, 'Tag');
      $tag_3_reloaded = Tags::findById($tag_3_id);
      $this->assertIsA($tag_3_reloaded, 'Tag');
      
      // Let add back tags, but not clear them...
      
      $company_1->setTags(array(
        $tag_1,
        $tag_2,
        $tag_3,
      ));
      
      $company_1->clearTags();
      
      $company_1_tags = $company_1->getTags(true);
      $this->assertEqual($company_1->countTags(true), 0);
      $this->assertEqual($company_1_tags, null);
      
      $tag_1_reloaded = Tags::findById($tag_1_id);
      $this->assertEqual($tag_1_reloaded, null);
      $tag_2_reloaded = Tags::findById($tag_2_id);
      $this->assertEqual($tag_2_reloaded, null);
      $tag_3_reloaded = Tags::findById($tag_3_id);
      $this->assertEqual($tag_3_reloaded, null);
      
      // Both sides...
      
      $company_1->setTags(array(
        $tag_1,
        $tag_3,
      ));
      
      $company_2->setTags(array(
        $tag_1,
        $tag_2,
      ));
      
      $this->assertEqual($tag_1->countCompanies(true), 2);
      $tag_1_companies = $tag_1->getCompanies(true);
      $this->assertEqual(objects_array_extract($tag_1_companies, 'getId'), array($company_1_id, $company_2_id));
      
      $this->assertEqual($tag_2->countCompanies(true), 1);
      $tag_2_companies = $tag_2->getCompanies(true);
      $this->assertEqual(objects_array_extract($tag_2_companies, 'getId'), array($company_2_id));
      
      $this->assertEqual($tag_3->countCompanies(true), 1);
      $tag_3_companies = $tag_3->getCompanies(true);
      $this->assertEqual(objects_array_extract($tag_3_companies, 'getId'), array($company_1_id));
      
      $company_1->deleteTagRelations();
      
      $this->assertEqual($tag_1->countCompanies(true), 1);
      $tag_1_companies = $tag_1->getCompanies(true);
      $this->assertEqual(objects_array_extract($tag_1_companies, 'getId'), array($company_2_id));
      
      $this->assertEqual($tag_2->countCompanies(true), 1);
      $tag_2_companies = $tag_2->getCompanies(true);
      $this->assertEqual(objects_array_extract($tag_2_companies, 'getId'), array($company_2_id));
      
      $this->assertEqual($tag_3->countCompanies(true), 0);
      $this->assertEqual($tag_3->getCompanies(true), null);
      
    } // testHasAndBelongsToMany
    
    function testRelationshipOnDelete() {
      
      // Create company and add multiple users. Company has many users with cascading deletation. When company is 
      // deleted users whould be dropped as well
      
      $company = new Company();
      $company->setName('Company');
      
      $ilija = new User();
      $ilija->setUsername('Ilija');
      
      $oliver = new User();
      $oliver->setUsername('oliver');
      
      $godza = new User();
      $godza->setUsername('godza');
      
      $company->addUser($ilija, true);
      $company->addUser($oliver, true);
      $company->addUser($godza, true);
      
      $company->save();
      
      $this->assertFalse($company->isNew());
      $this->assertFalse($ilija->isNew());
      $this->assertFalse($godza->isNew());
      $this->assertFalse($oliver->isNew());
      
      $ilija_id = $ilija->getId();
      $oliver_id = $oliver->getId();
      $godza_id = $godza->getId();
      $company_id = $company->getId();
      
      $this->assertEqual(objects_array_extract($company->getUsers(), 'getId'), array($ilija_id, $oliver_id, $godza_id));
      
      $company->delete();
      
      $this->assertEqual(Companies::findById($company_id), null);
      $this->assertEqual(Users::findById($ilija_id), null);
      $this->assertEqual(Users::findById($oliver_id), null);
      $this->assertEqual(Users::findById($godza_id), null);
      
      // Company has one package with nullify connection. When company gets dropped package should be reseted to have 
      // company ID set to 0
      
      $company = new Company();
      $company->setName('Name');
      
      $package = new Package();
      $package->setName('Nice');
      
      $company->setPackage($package, true);
      $company->save();
      
      $this->assertFalse($company->isNew());
      $this->assertFalse($package->isNew());
      
      $company_id = $company->getId();
      $package_id = $package->getId();
      
      $company->delete();
      
      $loaded_package = Packages::findById($package_id);
      $this->assertIsA($loaded_package, 'Package');
      $this->assertEqual($loaded_package->getCompanyId(), 0);
    } // testRelationshipOnDelete
  
  } // TestDBARuntime

?>