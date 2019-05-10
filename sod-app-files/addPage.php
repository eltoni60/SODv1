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
	
	//default layout shall be a 3x3 grid
	$defaultLayout = Layout::create_grid_layout(3, 3, $GLOBALS["GEN_POS_WIDTH"], $GLOBALS["GEN_POS_HEIGHT"], 20, 20);
	$createdPage = new Page($pageName, $defaultLayout);
	$loadedProject->add_page($createdPage);
	serialize_project_data($loadedProject, $projectDataFilePath);
		
?>
