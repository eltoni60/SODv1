

<?php
	require('sod-functions.php');

	$sodpInputString = file_get_contents("php://input");
	$sodpArray = json_decode($sodpInputString, true);
	$sodpObject = $sodpArray["sodp"];
	$project_data_object = construct_project_data_object_from_json($sodpObject["project_data"]);
	$item_library_object = construct_item_library_object_from_json($sodpObject["item_library"]);

	// needed to modify loadFile to pass the POSSD to this PHP file
	$POSSD = $sodpArray["possd"]; // see loadFile()
	$pName = $project_data_object->get_project_name();


	//create the files from these objects now
	$sod = dirname(__DIR__);
	
	create_sod_project_files($sod, $POSSD, $pName);

	//now serialize the project data object and item library object to its proper
	//location
	
	//serialize the project data and item library to the appropriate location

	
	$project_data_path = $sod.'/possd-'.$POSSD.'/project-'.$pName.'/'.$pName.'-project.json';
	$item_lib_path = $sod.'/possd-'.$POSSD.'/project-'.$pName.'/'.$pName.'-item-library.json';
	
	echo 'Project: '.$project_data_path.'<br />';
	echo 'Item library '.$item_lib_path.'<br />';
	
	serialize_project_data($project_data_object, $project_data_path);
	serialize_item_library($item_library_object, $item_lib_path);

 ?>
