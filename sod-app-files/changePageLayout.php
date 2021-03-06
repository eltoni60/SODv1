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
		$layoutObject = Layout::create_grid_layout(3, 3, $GLOBALS["GEN_POS_WIDTH"], $GLOBALS["GEN_POS_HEIGHT"], 5, 5);
	}
	else if ($layoutName == 'Grid 2x2') {
		$layoutObject = Layout::create_grid_layout(2, 2, $GLOBALS["GEN_POS_WIDTH"], $GLOBALS["GEN_POS_HEIGHT"], 4, 4);
	}
	else if ($layoutName == 'Grid 4x4') {
		$layoutObject = Layout::create_grid_layout(4, 4, $GLOBALS["GEN_POS_WIDTH"], $GLOBALS["GEN_POS_HEIGHT"], 3, 3);
	}
	else if ($layoutName == 'Brick Style 1') {
		$layoutObject = new Layout("Brick Style 1");
		$layoutObject->push_cell(new Rectangle(6, 5, 25, 20));
		$layoutObject->push_cell(new Rectangle(37, 5, 25, 20));
		$layoutObject->push_cell(new Rectangle(69, 5, 25, 20));
		
		$layoutObject->push_cell(new Rectangle(20, 35, 25, 20));
		$layoutObject->push_cell(new Rectangle(50, 35, 25, 20));
		
		$layoutObject->push_cell(new Rectangle(6, 65, 25, 20));
		$layoutObject->push_cell(new Rectangle(37, 65, 25, 20));
		$layoutObject->push_cell(new Rectangle(69, 65, 25, 20));
	}
	else if ($layoutName == 'Brick Style 2') {
		$layoutObject = new Layout("Brick Style 2");
		$layoutObject->push_cell(new Rectangle(20, 5, 25, 20));
		$layoutObject->push_cell(new Rectangle(50, 5, 25, 20));
		
		$layoutObject->push_cell(new Rectangle(6, 35, 25, 20));
		$layoutObject->push_cell(new Rectangle(37, 35, 25, 20));
		$layoutObject->push_cell(new Rectangle(69, 35, 25, 20));
		
		$layoutObject->push_cell(new Rectangle(20, 65, 25, 20));
		$layoutObject->push_cell(new Rectangle(50, 65, 25, 20));
	}
	else if ($layoutName == 'Vertical') {
		$layoutObject = new Layout("Vertical");
		$layoutObject->push_cell(new Rectangle(6, 15, 25, 70));
		$layoutObject->push_cell(new Rectangle(37, 15, 25, 70));
		$layoutObject->push_cell(new Rectangle(69, 15, 25, 70));
	}
	else if ($layoutName == 'Horizontal') {
		$layoutObject = new Layout("Horizontal");
		$layoutObject->push_cell(new Rectangle(10, 2, 80, 17));
		$layoutObject->push_cell(new Rectangle(10, 21, 80, 17));
		$layoutObject->push_cell(new Rectangle(10, 40, 80, 17));
		$layoutObject->push_cell(new Rectangle(10, 59, 80, 17));
		$layoutObject->push_cell(new Rectangle(10, 78, 80, 17));
	}
	
	if ($layoutObject != null) {
		//if it is legal to change to, then do it, otherwise do nothing
		if ($pageObject->can_change_layout($layoutObject) == true) {
			$pageObject->change_layout($layoutObject);
			// only need to save it if it actually worked.
			serialize_project_data($loadedProject, $projectDataFilePath);
			
			echo "SUCCESS";
		}
		else {
			var_dump("can't change layout");
			echo "FAILURE";
		}
	}
	else {
		echo "FAILURE";
	}		
?>
