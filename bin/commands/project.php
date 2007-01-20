<?php

  /**
  * Create a new project in a given directory
  * 
  * This commant will create a folder structure and some intial files in a given folder. Structure and file content will 
  * be based on angie/project/template template structure.
  *
  * @package Angie.bin
  * @subpackage commands
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  
  if(isset($return_help) && $return_help) {
    return 'This command will create an empty project structure. Second parameter is project name.';
  } // if
  
  require_once realpath(dirname(__FILE__) . '/../../init.php');
  require_once ANGIE_PATH . '/toys/structuregenerator/Angie_StructureGenerator.class.php';
  require_once ANGIE_PATH . '/toys/output/Angie_Output.class.php';
  require_once ANGIE_PATH . '/toys/output/Angie_Output_Console.class.php';
  require_once ANGIE_PATH . '/template/Angie_TemplateEngine.class.php';
  require_once ANGIE_PATH . '/template/engine/Angie_TemplateEngine_Php.class.php';
  
  $project_name = array_var($argv, 2);
  if(trim($project_name) == '') {
    die("Please provide project name\n");
  } // if
  
  $parent_folder_path = array_var($argv, 3, getcwd());
  if(!is_dir($parent_folder_path)) {
    die("Please provide a path of the folder where you want to create '$project_name' project\n");
  } // if
  
  $project_path = with_slash($parent_folder_path) . $project_name;
  if(!is_dir($project_path)) {
    if(!mkdir($project_path)) {
      die("Failed to create '$project_name' folder\n");
    } // if
  } // if
  
  $structure_generator = new Angie_StructureGenerator();
  $structure_generator->assignToView('project_name', $project_name);
  $structure_generator->copyStructure(ANGIE_PATH . '/project/project_template', $project_path, new Angie_Output_Console(), $parent_folder_path);
  
  print "Project '$project_name' created\n\n";

?>