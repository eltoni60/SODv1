<?php
	/*If this is being called then we are assuming that there isn't a project
		of the name already*/

	require('sod-functions.php');

	$data = json_decode(file_get_contents("php://input"), true);
	$GPOS = $data["GPOS"];
	$GPOS_Name = $data["GPOS_Name"]; //JSON representation of it

	$sod = dirname(__FILE__, 2);
	$GPOSdir = $sod."/generated-pos/".$GPOS_Name;
	
	$debug = fopen("debug.txt", "w");
	fwrite($debug, $GPOSdir);
	fclose($debug);
	
	// just write the HTML to the file
	$writer = fopen($GPOSdir, "w");
	fwrite($writer, $GPOS);
	fclose($writer);
		
?>
