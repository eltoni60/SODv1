<?php
	/*If this is being called then we are assuming that there isn't a project
		of the name already*/

	require('sod-functions.php');

	$addedProj = json_decode(file_get_contents("php://input"), true);
	$POSSD = $addedProj["POSSD"];
	$projectName = $addedProj["projectName"];
	$pageName = $addedProj["pageName"];

	$sod = dirname(__DIR__);
	$projDir = $sod."/possd-".$POSSD."/project-".$projectName."/";
	
	// get the project, add the page, and then write it back
	
	$projectDataFilePath = $projDir.$projectName."-project.json";
	$loadedProject = deserialize_project_data($projectDataFilePath);
	$loadedProject->remove_page($pageName);
	serialize_project_data($loadedProject, $projectDataFilePath);
		
?>
