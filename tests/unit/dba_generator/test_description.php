<?php

  // ---------------------------------------------------
  //  Define USER
  // ---------------------------------------------------
  
  $user = Angie_DBA_Generator::addEntity('user');
  
  $user->addIdAttribute('id');
  $user->addStringAttribute('username', 50);
  $user->addStringAttribute('email', 150);
  $user->addStringAttribute('display_name', 150);
  $user->addDateTimeAttribute('created_on');
  $user->addDateTimeAttribute('updated_on');
  
  $user->protectFields('id', 'created_on', 'updated_on', 'created_by_id', 'updated_by_id');
  $user->allowFields('username', 'email', 'company_id');
  $user->detailFields('email', 'display_name');
  
  $user->addAutoSetter('created_by_id', 'get_logged_user_id', Angie_DBA_Generator::ON_INSERT);
  $user->addAutoSetter('updated_by_id', 'get_logged_user_id', Angie_DBA_Generator::ON_UPDATE);
  
  // ---------------------------------------------------
  //  Define COMPANY
  // ---------------------------------------------------
  
  $company = Angie_DBA_Generator::addEntity('company');
  $company->addIdAttribute('id');
  $company->addStringAttribute('name', 50);
  
  // ---------------------------------------------------
  //  Define PACKAGE
  // ---------------------------------------------------
  
  $package = Angie_DBA_Generator::addEntity('package');
  $package->addIdAttribute('id');
  $package->addStringAttribute('name', 50);
  
  // ---------------------------------------------------
  //  Define TAG
  // ---------------------------------------------------
  
  $tag = Angie_DBA_Generator::addEntity('tag');
  $tag->addIdAttribute('id');
  $tag->addStringAttribute('name', 50);
  
  // ---------------------------------------------------
  //  Relations
  // ---------------------------------------------------
  
  $user->belongsTo('company'); // getCompany() - by company_id
  $user->belongsTo('user', array('foreign_key' => 'created_by_id'));  // getCreatedBy()
  $user->belongsTo('user', array('foreign_key' => 'updated_by_id'));  // getUpdatedBy()
  $user->hasMany('company', array(
    'name' => 'owned_companies', 
    'foreign_key' => 'created_by_id'
  )); // getOwnedCompanies() - by created_by_id
  
  $company->hasMany('user', array(
    'on_delete' => Angie_DBA_Generator::ON_DELETE_CASCADE
  )); // hasMany
  $company->belongsTo('user', array('foreign_key' => 'created_by_id'));
  
  $company->hasOne('package');
  $package->belongsTo('company');
  
  $company->hasAndBelongsToMany('tag');
  $tag->hasAndBelongsToMany('company');
  
  Angie_DBA_Generator::setOutputDir(dirname(__FILE__) . '/output');
  Angie_DBA_Generator::generate(new Angie_Output_Silent());

?>