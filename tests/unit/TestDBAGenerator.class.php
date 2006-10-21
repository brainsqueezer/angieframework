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
      $user = Angie_DBA_Generator::addEntity('user');
  
      $user->addIdAttribute('id', Angie_DBA_Generator::SIZE_TINY);
      $user->addStringAttribute('username', 50);
      $user->addStringAttribute('email', 150);
      $user->addStringAttribute('display_name', 150);
      $user->addDateTimeAttribute('created_on');
      $user->addDateTimeAttribute('updated_on');
      
      $user->belongsTo('company'); // getCompany() - by company_id
      $user->belongsTo('user', 'created_by_id');  // getCreatedBy()
      $user->belongsTo('user', 'updated_by_id');  // getUpdatedBy()
      $user->hasMany('company', 'created_by_id', 'owned_companies'); // getOwnedCompanies() - by created_by_id
      
      $user->protectFields('id', 'created_on', 'updated_on', 'created_by_id', 'updated_by_id');
      $user->allowFields('username', 'email');
      $user->detailFields('email', 'display_name');
      
      $user->addAutoSetter('created_by_id', 'get_logged_user_id', Angie_DBA_Generator::ON_INSERT);
      $user->addAutoSetter('updated_by_id', 'get_logged_user_id', Angie_DBA_Generator::ON_UPDATE);
      
      // This should be available by default...
      //$user->addAutoSetter('created_on', 'time_now', Angie_DBA_Generator::ON_UPDATE);
      //$user->addAutoSetter('updated_on', 'time_now', Angie_DBA_Generator::ON_UPDATE);
      
      $company = Angie_DBA_Generator::add('companie');
      $company->addIdAttribute('id', Angie_DBA_Generator::TINY);
      $company->addStringAttribute('name', 50);
      
      Angie_DBA_Generator::build();
    } // setUp
    
    function tearDown() {
      Angie_DBA_Gesnerator::cleanUp();
    } // tearDown
    
    function testFields() {
      $expected_fields = array(
        'id', 
        'company_id',
        'username', 
        'email', 
        'display_name', 
        'created_on', 
        'updated_on', 
        'created_by_id', 
        'updated_by_id'
      ); // array
      
      $fields = Angie_DBA_Generator::get('users')->getFields();
      $this->assertTrue(is_array($fields) && count($fields) == count($expected_fields));
      foreach($fields as $field) {
        $this->assertTrue(in_array($field->getName(), $expected_fields));
      } // foreach
    } // testComplexModel
    
    function testAdditionalFieldsSettings() {
      $expected_protected_fields = array('id', 'created_on', 'updated_on', 'created_by_id', 'updated_by_id');
      
      $protected_fields = Angie_DBA_Generator::get('users')->getProtectedFields();
      $this->assertTrue(is_array($protected_fields) && count($protected_fields) == count($expected_protected_fields));
      foreach($expected_protected_fields as $field) {
        $this->assertTrue(in_array($field, $expected_protected_fields));
      } // foreach
      
      $expected_allowed_files = array();
      
      $allowed_fields=  Angie_DBA_Generator::get('users')->getAllowedFields();
      $this->assertTrue(is_array($allowed_fields) && count($allowed_fields) == count($expected_allowed_files));
      foreach($allowed_fields as $field_name) {
        $this->assertTrue(in_array($field_name, $expected_allowed_files));
      } // foreach
      
      $expected_detail_fields = array();
      
      $detail_fields = Angie_DBA_Generator::get('users')->getDetailFields();
      $this->assertTrue(is_array($detail_fields) && count($detail_fields) == count($expected_detail_fields));
      foreach($detail_fields as $field) {
        $this->assertTrue(in_array($field, $detail_fields));
      } // foreach
    } // testAdditionalFieldsSettings
    
    function testAutoSetters() {
      
    } // testAutoSetters
  
  } // TestDBAGenerator

?>