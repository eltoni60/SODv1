<?php
	/*If this is being called then we are assuming that there isn't a project
		of the name already*/

	require('sod-functions.php');

	$data = json_decode(file_get_contents("php://input"), true);
	$POSSD = $data["POSSD"];
	$projectName = $data["projectName"];
	$pageName = $data["pageName"];
	$layoutName = $data["layoutName"];

	$sod = dirname(__DIR__);
	$projDir = $sod."/possd-".$POSSD."/project-".$projectName."/";
	
	// get the project, change the layout and then write it back
	$projectDataFilePath = $projDir.$projectName."-project.json";
	$loadedProject = deserialize_project_data($projectDataFilePath);
	$pageObject = $loadedProject->get_page($pageName);
	
	// get the page and change the layout if it is possible to
	// do a lazy series of if-then statements
	$layoutObject = null;
	
	if ($layoutName == 'Grid 3x3') {
		$layoutObject = Layout::create_grid_layout(3, 3, $GLOBALS["GEN_POS_WIDTH"], $GLOBALS["GEN_POS_HEIGHT"], 2, 2);
	}
	else if ($layoutName == 'Grid 2x2') {
		$layoutObject = Layout::create_grid_layout(2, 2, $GLOBALS["GEN_POS_WIDTH"], $GLOBALS["GEN_POS_HEIGHT"], 2, 2);
	}
	else if ($layoutName == 'Grid 4x4') {
		$layoutObject = Layout::create_grid_layout(4, 4, $GLOBALS["GEN_POS_WIDTH"], $GLOBALS["GEN_POS_HEIGHT"], 2, 2);
	}
	else if ($layoutName == 'Brick Style 1') {
		
	}
	else if ($layoutName == 'Brick Style 2') {
		
	}
	else if ($layoutName == 'Vertical') {
		
	}
	else if ($layoutName == 'Horizontal') {
		
	}
	
	if ($layoutObject != null) {
		//if it is legal to change to, then do it, otherwise do nothing
		if ($pageObject->can_change_layout($layoutObject)) {
			$pageObject->change_layout($layoutObject);
			// only need to save it if it actually worked.
			serialize_project_data($loadedProject, $projectDataFilePath);
			
			echo "SUCCESS";
		}
		else {
			echo "FAILURE";
		}
	}
	else {
		echo "FAILURE";
	}		
?>
