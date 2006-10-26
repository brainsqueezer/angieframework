<?php

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
  
  Angie_DBA_Generator::setOutputDir(dirname(__FILE__) . '/output');
  Angie_DBA_Generator::generate(new Angie_Output_Silent());

?>