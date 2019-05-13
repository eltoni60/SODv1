<?php
	/*If this is being called then we are assuming that there isn't a project
		of the name already*/

	require('sod-functions.php');

	$data = json_decode(file_get_contents("php://input"), true);
	$POSSD = $data["POSSD"];
	$projectData = $data["projectData"]; //JSON representation of it
	$projectName = $projectData["project_name"];

	$sod = dirname(__DIR__);
	$projDir = $sod."/possd-".$POSSD."/project-".$projectName."/";
	
	// get the project, add the page, and then write it back
	
	$projectDataFilePath = $projDir.$projectName."-project.json";
	
	$projectObject = construct_project_data_object_from_json($projectData);	
	serialize_project_data($projectObject, $projectDataFilePath);
		
?>
