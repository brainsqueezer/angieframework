<?php

  class TestDBDescription extends UnitTestCase {
    
    /**
    * Database connection
    *
    * @var Angie_DB_Connection
    */
    private $database_connection;
    
    /**
    * Test table
    *
    * @var Angie_DB_Table
    */
    private $table_definition;
    
    /**
    * Value of timestamp used in tests
    *
    * @var Angie_DateTime
    */
    private $now;
  
    /**
    * Constructor
    *
    * @param void
    * @return TestDBDescription
    */
    function __construct() {
      $this->UnitTestCase('Test classes used for describing database');
      $this->database_connection = Angie_DB::getConnection();
      $this->now = Angie_DateTime::now();
    } // __construct
    
    function setUp() {
      $this->table_definition = $this->database_connection->produceTable('test_description');
      
      $id_field = new Angie_DB_Field_Integer('id');
      $id_field->setUnsigned(true);
      $id_field->setAutoIncrement(true);
      
      $title_field = new Angie_DB_Field_String('title', 'default title', true);
      $title_field->setLenght(100);
      
      $text_field = new Angie_DB_Field_Text('text');
      
      $date_field = new Angie_DB_Field_DateTime('created_on', $this->now);
      
      $enum_field = new Angie_DB_Field_Enum('status', 'open');
      $enum_field->setPossibleValues(array('open', 'closed', 'pending'));
      
      $float_field = new Angie_DB_Field_Float('float');
      
      $float_field_with_lenght = new Angie_DB_Field_Float('float_with_lenght', 12);
      $float_field_with_lenght->setLenght(5);
      $float_field_with_lenght->setPrecission(3);
      
      $this->table_definition->addField($id_field);
      $this->table_definition->addField($title_field);
      $this->table_definition->addField($text_field);
      $this->table_definition->addField($date_field);
      $this->table_definition->addField($enum_field);
      $this->table_definition->addField($float_field);
      $this->table_definition->addField($float_field_with_lenght);
      
      $this->table_definition->addPrimaryKey($id_field);
    } // setUp
    
    function testCreation() {
      $this->assertTrue($this->table_definition->buildTable($this->database_connection));
      $this->assertTrue($this->database_connection->dropTable($this->table_definition->getName()));
    } // testCreation
    
    function testExtraction() {
      $this->assertTrue($this->table_definition->buildTable($this->database_connection));
      
      $table = $this->database_connection->describeTable('test_description');
      $this->assertTrue($table instanceof Angie_DB_Table);
      $this->assertEqual($table->getPrimaryKey(), array('id'));
      
      $id_field = $table->getField('id');
      $this->assertEqual($id_field->getType(), Angie_DB::TYPE_INTEGER);
      $this->assertTrue($id_field->getUnsigned());
      $this->assertTrue($id_field->getAutoIncrement());
      
      $title_field = $table->getField('title');
      $this->assertEqual($title_field->getType(), Angie_DB::TYPE_VARCHAR);
      $this->assertEqual($title_field->getLenght(), 100);
      $this->assertEqual($title_field->getDefaultValue(), 'default title');
      
      $text_field = $table->getField('text');
      $this->assertEqual($text_field->getType(), Angie_DB::TYPE_TEXT);
      
      $datetime_field = $table->getField('created_on');
      $this->assertEqual($datetime_field->getType(), Angie_DB::TYPE_DATETIME);
      $this->assertEqual($datetime_field->getDefaultValue(), $this->now->toMySQL());
      
      $float_field = $table->getField('float');
      $this->assertEqual($float_field->getType(), Angie_DB::TYPE_FLOAT);
      
      $float_with_lenght_field = $table->getField('float_with_lenght');
      $this->assertEqual($float_with_lenght_field->getType(), Angie_DB::TYPE_FLOAT);
      $this->assertEqual($float_with_lenght_field->getDefaultValue(), 12);
      $this->assertEqual($float_with_lenght_field->getLenght(), 5);
      $this->assertEqual($float_with_lenght_field->getPrecission(), 3);
      
      $enum_field = $table->getField('status');
      $this->assertEqual($enum_field->getType(), Angie_DB::TYPE_ENUM);
      $this->assertEqual($enum_field->getDefaultValue(), 'open');
      $this->assertEqual($enum_field->getPossibleValues(), array('open', 'closed', 'pending'));
      
      $this->assertTrue($this->database_connection->dropTable($this->table_definition->getName()));
    } // testExtraction
    
    function testCompositePk() {
      $table = $this->database_connection->produceTable('test_description_composite');
      
      $table->addField($user_id = new Angie_DB_Field_Integer('user_id'));
      $table->addField($project_id = new Angie_DB_Field_Integer('project_id'));
      $table->addField($title = new Angie_DB_Field_String('title'));
      $title->setLenght(100);
      
      $table->addPrimaryKey($user_id);
      $table->addPrimaryKey($project_id);
      
      $this->assertTrue($table->buildTable($this->database_connection));
      
      $table_description = $this->database_connection->describeTable($table->getName());
      $this->assertEqual($table_description->getPrimaryKey(), array('user_id', 'project_id'));
      
      $this->assertTrue($this->database_connection->dropTable($table->getName()));
    } // testCompositePk
  
  } // TestDBDescription

?>