
<?php


	require('../sod-functions.php');
	
	$pageName = $_GET["pageName"];
	$possd = $_GET["possd"];
    $projectName = $_GET["projectName"];
	$sod = dirname(__FILE__, 3);
	$projDir = $sod."/possd-".$possd."/project-".$projectName."/";
	
	// get the project and item library data
	
	$projectDataFilePath = $projDir.$projectName."-project.json";
	$loadedProject = deserialize_project_data($projectDataFilePath);
	$itemLibraryFilePath = $projDir.$projectName."-item-library.json";
	$loadedItemLibrary = deserialize_item_library($itemLibraryFilePath);
	
	$tableString = generate_page_to_string($loadedProject->get_page($pageName), $loadedItemLibrary, false); //output table
	
	// echo to the sender
	echo $tableString;
	
?>
