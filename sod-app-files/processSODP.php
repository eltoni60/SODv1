

<?php

	require('sod-functions.php');

	/*
	TODO write function/process to import a .sodp file
	TODO use fopen(), fwrite(), fclose(), mkdir
	*/

	$httpPostBody = file_get_contents("php://input");
	$sodpArray = json_decode($httpPostBody);

	$project_data_object = construct_project_data_object_from_json(sodpArray["sodp"]["project_data"]);
	$item_library_object = construct_item_library_object_from_json(sodpArray["sodp"]["item_libray"]);

	$POSSD = sodpArray["possd"]; // see loadFile()
	$pName = $project_data_object->get_project_name();

	//create the files from these objects now
	$sod = dirname(__DIR__);
	create_sod_project_files($sod, $POSSD, $pName);

	//now serialize the project data object and item library object to its proper
	//location
	$project_data_path = $sod."/possd-".$POSSD."/".$POSSD."/project-".$pName."/".$pName."-project.json";
	$item_lib_path = $sod."/possd-".$POSSD."/".$POSSD."/project-".$pName."/".$pName."-item-library.json";
	serialize_project_data($project_data_object, $project_data_path);
	serialize_item_library($item_library_object, $item_lib_path);

	$fp_out = fopen("processSODP_debug.txt", "w");
	//first we see what was posted
	fwrite($fp_out, $httpPostBody);
	fwrite($fp_out, '\n'.$sod);

	fclose($fp_out);

 ?>
