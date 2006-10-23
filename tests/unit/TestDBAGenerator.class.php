<?php

  class TestDBAGenerator extends UnitTestCase {
  
    /**
    * Constructor
    *
    * @param void
    * @return TestDBAGenerator
    */
    function __construct() {
      parent::UnitTestCase('Test DBA generator');
    } // __construct
    
    function setUp() {
      
      // Define USER
      $user = Angie_DBA_Generator::addEntity('user');
  
      $user->addIdAttribute('id', Angie_DBA_Generator::SIZE_TINY, true, true);
      $user->addStringAttribute('username', 50);
      $user->addStringAttribute('email', 150);
      $user->addStringAttribute('display_name', 150);
      $user->addDateTimeAttribute('created_on');
      $user->addDateTimeAttribute('updated_on');
      
      $user->protectFields('id', 'created_on', 'updated_on', 'created_by_id', 'updated_by_id');
      $user->allowFields('username', 'email');
      $user->detailFields('email', 'display_name');
      
      $user->addAutoSetter('created_by_id', 'get_logged_user_id', Angie_DBA_Generator::ON_INSERT);
      $user->addAutoSetter('updated_by_id', 'get_logged_user_id', Angie_DBA_Generator::ON_UPDATE);
      
      // Define COMPANY
      $company = Angie_DBA_Generator::addEntity('company');
      $company->addIdAttribute('id', Angie_DBA_Generator::SIZE_TINY, true, true);
      $company->addStringAttribute('name', 50);
      
      // Set relations
      $user->belongsTo('company'); // getCompany() - by company_id
      $user->belongsTo('user', array('field_name' => 'created_by_id'));  // getCreatedBy()
      $user->belongsTo('user', array('field_name' => 'updated_by_id'));  // getUpdatedBy()
      $user->hasMany('company', array('field_name' => 'created_by_id', 'getter' => 'getOwnedCompanies')); // getOwnedCompanies() - by created_by_id
      
      $company->hasMany('user');
      $company->belongsTo('user', array('field_name' => 'created_by_id'));
      
      //Angie_DBA_Generator::build();
      Angie_DBA_Generator::setOutputDir(dirname(__FILE__) . '/dba_generator/output');
      Angie_DBA_Generator::generate(new Angie_Output_Console());
    } // setUp
    
    function tearDown() {
      Angie_DBA_Generator::cleanUp();
    } // tearDown
    
    function testNaming() {
      $user_entry = Angie_DBA_Generator::getEntity('user');
      
      $this->assertEqual($user_entry->getName(), 'user');
      $this->assertEqual($user_entry->getObjectClassName(), 'User');
      $this->assertEqual($user_entry->getManagerClassName(), 'Users');
      $this->assertEqual($user_entry->getTableName(), 'users');
      
      $company_entry = Angie_DBA_Generator::getEntity('company');
      
      $this->assertEqual($company_entry->getName(), 'company');
      $this->assertEqual($company_entry->getObjectClassName(), 'Company');
      $this->assertEqual($company_entry->getManagerClassName(), 'Companies');
      $this->assertEqual($company_entry->getTableName(), 'companies');
    } // testNaming
    
    function testId() {
      $user_pk = Angie_DBA_Generator::getEntity('user')->getPrimaryKey();
      $this->assertTrue(is_array($user_pk) && count($user_pk) == 1 && array_key_exists('id', $user_pk));
    } // testId
    
    function testFields() {
      $expected_fields = array(
        'id', 
        'company_id',    // relationship
        'username', 
        'email', 
        'display_name', 
        'created_on', 
        'updated_on', 
        'created_by_id', // relationship
        'updated_by_id'  // relationship
      ); // array
      
      $fields = Angie_DBA_Generator::getEntity('user')->getFields();
      $this->assertTrue(is_array($fields) && count($fields) == count($expected_fields));
      foreach($fields as $field) {
        $this->assertTrue(in_array($field->getName(), $expected_fields));
      } // foreach
      
      $expected_fields = array(
        'id', 
        'name', 
        'created_by_id' // relationship
      ); // array
      
      $fields = Angie_DBA_Generator::getEntity('company')->getFields();
      $this->assertTrue(is_array($fields) && count($fields) == count($expected_fields));
      foreach($fields as $field) {
        $this->assertTrue(in_array($field->getName(), $expected_fields));
      } // foreach
    } // testComplexModel
    
    function testAdditionalFieldsSettings() {
      $expected_protected_fields = array('id', 'created_on', 'updated_on', 'created_by_id', 'updated_by_id');
      
      $protected_fields = Angie_DBA_Generator::getEntity('user')->getProtectedFields();
      $this->assertTrue(is_array($protected_fields) && count($protected_fields) == count($expected_protected_fields));
      foreach($expected_protected_fields as $field) {
        $this->assertTrue(in_array($field, $expected_protected_fields));
      } // foreach
      
      $expected_allowed_files = array('username', 'email');
      
      $allowed_fields=  Angie_DBA_Generator::getEntity('user')->getAllowedFields();
      $this->assertTrue(is_array($allowed_fields) && count($allowed_fields) == count($expected_allowed_files));
      foreach($allowed_fields as $field_name) {
        $this->assertTrue(in_array($field_name, $expected_allowed_files));
      } // foreach
      
      $expected_detail_fields = array('email', 'display_name');
      
      $detail_fields = Angie_DBA_Generator::getEntity('user')->getDetailFields();
      $this->assertTrue(is_array($detail_fields) && count($detail_fields) == count($expected_detail_fields));
      foreach($detail_fields as $field) {
        $this->assertTrue(in_array($field, $detail_fields));
      } // foreach
    } // testAdditionalFieldsSettings
    
    function testAutoSetters() {
      $auto_setters = Angie_DBA_Generator::getEntity('user')->getAutoSetters();
      $this->assertTrue(is_array($auto_setters) && count($auto_setters));
      
      // Default auto-setters
      $this->assertTrue(array_key_exists('created_on', $auto_setters));
      $this->assertTrue(array_key_exists('updated_on', $auto_setters));
      
      // User defined auto setters
      $this->assertTrue(array_key_exists('created_by_id', $auto_setters));
      $this->assertTrue(array_key_exists('updated_by_id', $auto_setters));
    } // testAutoSetters
  
  } // TestDBAGenerator

?>