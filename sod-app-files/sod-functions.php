<?php 
	
/* The #include for PHP */
require('sod-classes.php');

	
/**
	Converts the program object representation of
	an Item_Library into its JSON representation as
	described in Section 3.2 and saves it to $file_path.
*/
function serialize_item_library($item_lib, $file_path) {
	// writing custom JSON to have control over the process
	$fp = fopen($file_path, 'w');
	fwrite($fp, "{\n");
	fwrite($fp, "\t\"items\":\n");
	fwrite($fp, "\t[\n");
	
	$item_ids = $item_lib->list_item_ids();
	// O(N^2) to iterate over all the items at the moment
	for ($id_index = 0; $id_index < count($item_ids); $id_index++) {
		$item = $item_lib->get_item($item_ids[$id_index]); //O(N) lookup
		// write the item object in the JSON array
		fwrite($fp, "\t\t{\n");
		fwrite($fp, "\t\t\t\"item_id\":{$item->get_item_id()},\n");
		fwrite($fp, "\t\t\t\"item_name\":\"{$item->get_item_name()}\",\n");
		fwrite($fp, "\t\t\t\"item_price\":{$item->get_item_price()},\n");
		// attributes
		fwrite($fp, "\t\t\t\"attribute_names\": [");
		$attr_names = $item->list_attribute_names();
		for ($attr = 0; $attr < count($attr_names); $attr++) {
			fwrite($fp, "\"{$attr_names[$attr]}\"");
			if ($attr < count($attr_names) - 1) {
				fwrite($fp, ",");
			}
			else {
				fwrite($fp, " ");
			}
		}
		fwrite($fp, "],\n");
		fwrite($fp, "\t\t\t\"attribute_values\": [");
		$attr_names = $item->list_attribute_names();
		for ($attr = 0; $attr < count($attr_names); $attr++) {
			fwrite($fp, "\"{$item->get_attribute_value(attr_names[$attr])}\"");
			if ($attr < count($attr_names) - 1) {
				fwrite($fp, ",");
			}
			else {
				fwrite($fp, " ");
			}
		}
		fwrite($fp, "]\n");
		
		fwrite($fp, "\t\t}");
		
		if ($id_index < count($item_ids) - 1) {
			fwrite($fp, ",\n");
		}
		else {
			fwrite($fp, "\n");
		}
	}
	
	fwrite($fp, "\t]\n");
	fwrite($fp, "}\n");
	fclose($fp);
}

function item_library_to_string($item_lib) {
	$item_lib_str = "";
	$item_lib_str .= "{\n";
	$item_lib_str .= "\t\"items\":\n";
	$item_lib_str .= "\t[\n";
	
	$item_ids = $item_lib->list_item_ids();
	// O(N^2) to iterate over all the items at the moment
	for ($id_index = 0; $id_index < count($item_ids); $id_index++) {
		$item = $item_lib->get_item($item_ids[$id_index]); //O(N) lookup
		// write the item object in the JSON array
		$item_lib_str .= "\t\t{\n";
		$item_lib_str .= "\t\t\t\"item_id\":{$item->get_item_id()},\n";
		$item_lib_str .= "\t\t\t\"item_name\":\"{$item->get_item_name()}\",\n";
		$item_lib_str .= "\t\t\t\"item_price\":{$item->get_item_price()},\n";
		// attributes
		$item_lib_str .= "\t\t\t\"attribute_names\": [";
		$attr_names = $item->list_attribute_names();
		for ($attr = 0; $attr < count($attr_names); $attr++) {
			$item_lib_str .= "\"{$attr_names[$attr]}\"";
			if ($attr < count($attr_names) - 1) {
				$item_lib_str .= ",";
			}
			else {
				$item_lib_str .= " ";
			}
		}
		$item_lib_str .= "],\n";
		$item_lib_str .= "\t\t\t\"attribute_values\": [";
		$attr_names = $item->list_attribute_names();
		for ($attr = 0; $attr < count($attr_names); $attr++) {
			$item_lib_str .= "\"{$item->get_attribute_value($attr_names[$attr])}\"";
			if ($attr < count($attr_names) - 1) {
				$item_lib_str .= ",";
			}
			else {
				$item_lib_str .= " ";
			}
		}
		$item_lib_str .= "]\n";
		
		$item_lib_str .= "\t\t}";
		
		if ($id_index < count($item_ids) - 1) {
			$item_lib_str .= ",\n";
		}
		else {
			$item_lib_str .= "\n";
		}
	}
	
	$item_lib_str .= "\t]\n";
	$item_lib_str .= "}\n";
	return $item_lib_str;
}

/**
	Converts the program object representation of
	a SOD_Project into its JSON representation as
	described in Section 3.2 of the System Design
	Document and saves it to $file_path.
*/
function serialize_project_data($project_data, $file_path) {
	// writing custom JSON to have control over the process
	$fp = fopen($file_path, 'w');
	fwrite($fp, "{\n");
	fwrite($fp, "\t\"project_name\":\"{$project_data->get_project_name()}\",\n");
	fwrite($fp, "\t\"pages\":\n");
	fwrite($fp, "\t[\n");
	$page_names = $project_data->list_page_names();
	for ($page_name_index = 0; $page_name_index < count($page_names); $page_name_index++) {
		$page = $project_data->get_page($page_names[$page_name_index]);
		// write the page as a JSON object
		fwrite($fp, "\t\t{\n");
		fwrite($fp, "\t\t\t\"page_name\":\"{$page->get_page_name()}\",\n");
		fwrite($fp, "\t\t\t\"layout\":\n");
		fwrite($fp, "\t\t\t{\n");
		//write this page's layout object
		$layout = $page->get_layout();
		fwrite($fp, "\t\t\t\t\"layout_name\":\"{$layout->get_layout_name()}\",\n");
		fwrite($fp, "\t\t\t\t\"cells\":\n");
		fwrite($fp, "\t\t\t\t[\n");
		//write the list of cells 
		for ($r = 0; $r < $layout->get_cell_count(); $r++) {
			$cell = $layout->get_cell($r);
			fwrite($fp, "\t\t\t\t\t[ {$cell->x},{$cell->y},{$cell->width},{$cell->height} ]");
			if ($r < $layout->get_cell_count() - 1) {
				fwrite($fp, ",\n");
			}
			else {
				fwrite($fp, "\n");
			}
		}
		fwrite($fp, "\t\t\t\t]\n");
		fwrite($fp, "\t\t\t},\n"); // close layout object
		//write elements array now
		fwrite($fp, "\t\t\t\"elements\": [ ");
		for ($e = 0; $e < $page->get_cell_count(); $e++) {
			fwrite($fp, "{$page->get_element_by_cell_location($e)}");
			if ($e < $page->get_cell_count() - 1 ) {
				fwrite($fp, ", ");
			}
			else {
				fwrite($fp, " ");
			}
		}
		fwrite($fp, "]\n");
		//close this page object
		fwrite($fp, "\t\t}");
		if ($page_name_index < count($page_names) - 1) {
			fwrite($fp, ",\n");
		}
		else {
			fwrite($fp, "\n");
		}
	}
	
	fwrite($fp, "\t]\n");
	fwrite($fp, "}\n");
	fclose($fp);
}

function project_data_to_string($project_data) {
	$project_str = "";
	$project_str .= "{\n";
	$project_str .= "\t\"project_name\":\"{$project_data->get_project_name()}\",\n";
	$project_str .= "\t\"pages\":\n";
	$project_str .= "\t[\n";
	$page_names = $project_data->list_page_names();
	for ($page_name_index = 0; $page_name_index < count($page_names); $page_name_index++) {
		$page = $project_data->get_page($page_names[$page_name_index]);
		// write the page as a JSON object
		$project_str .= "\t\t{\n";
		$project_str .= "\t\t\t\"page_name\":\"{$page->get_page_name()}\",\n";
		$project_str .= "\t\t\t\"layout\":\n";
		$project_str .= "\t\t\t{\n";
		//write this page's layout object
		$layout = $page->get_layout();
		$project_str .= "\t\t\t\t\"layout_name\":\"{$layout->get_layout_name()}\",\n";
		$project_str .= "\t\t\t\t\"cells\":\n";
		$project_str .= "\t\t\t\t[\n";
		//write the list of cells 
		for ($r = 0; $r < $layout->get_cell_count(); $r++) {
			$cell = $layout->get_cell($r);
			$project_str .= "\t\t\t\t\t[ {$cell->x},{$cell->y},{$cell->width},{$cell->height} ]";
			if ($r < $layout->get_cell_count() - 1) {
				$project_str .= ",\n";
			}
			else {
				$project_str .= "\n";
			}
		}
		$project_str .= "\t\t\t\t]\n";
		$project_str .= "\t\t\t},\n"; // close layout object
		//write elements array now
		$project_str .= "\t\t\t\"elements\": [ ";
		for ($e = 0; $e < $page->get_cell_count(); $e++) {
			$project_str .= "{$page->get_element_by_cell_location($e)}";
			if ($e < $page->get_cell_count() - 1 ) {
				$project_str .= ", ";
			}
			else {
				$project_str .= " ";
			}
		}
		$project_str .= "]\n";
		//close this page object
		$project_str .= "\t\t}";
		if ($page_name_index < count($page_names) - 1) {
			$project_str .= ",\n";
		}
		else {
			$project_str .= "\n";
		}
	}
	
	$project_str .= "\t]\n";
	$project_str .= "}\n";
	return $project_str;
}
	
/**
	Reads the JSON representation of an Item_Library
	located at $file_path and returns the program
	object representation of it. The JSON file is
	expected to follow the form in Section 3.2 of the 
	System Design Document.
*/
function deserialize_item_library($file_path) {
	//read the JSON object and then convert it into
	//the correct form for the Item_Library class
	
	$file_content = file_get_contents($file_path);
	$file_json_array = json_decode($file_content, true);
		
	$item_lib_object = construct_item_library_object_from_json($file_json_array);
	return $item_lib_object;
}

function construct_item_library_object_from_json($json_object) {
	$item_lib_object = new Item_Library;
	$items_array = (array)$json_object["items"];
	for ($item_index = 0; $item_index < sizeof($items_array); $item_index++) {
		$item_array = $items_array[$item_index];
		$id = $item_array["item_id"];
		$name = $item_array["item_name"];
		$price = $item_array["item_price"];
		$attribute_name_array = (array)$item_array["attribute_names"];
		$attribute_value_array = (array)$item_array["attribute_values"];
		$item_object = new Item($id, $name, $price);
		for ($a = 0; $a < sizeof($attribute_name_array); $a++) {
			$item_object->set_attribute($attribute_name_array[$a], $attribute_value_array[$a]);
		}
		$item_lib_object->add_item($item_object);
	}
	return $item_lib_object;
}

/**
	Reads the JSON representation of a SOD_Project
	located at $file_path and returns the program
	object representation of it. The JSON file is
	expected to follow the form in Section 3.2 of the 
	System Design Document.
*/
function deserialize_project_data($file_path) {
	$file_content = file_get_contents($file_path);
	$file_json_array = json_decode($file_content, true);
	
	$project_object = construct_project_data_object_from_json($file_json_array);
	return $project_object;
}

function construct_project_data_object_from_json($json_object) {
	$project_name_val = (string)$json_object["project_name"];
		
	$project_object = new SOD_Project($project_name_val);

	$page_list_array = (array)$json_object["pages"];		
	for ($page_index = 0; $page_index < sizeof($page_list_array); $page_index++) {
		$page_val = $page_list_array[$page_index];
		$page_name_val = (string)$page_val["page_name"];
		$page_layout_val = $page_val["layout"];
		$page_layout_name_val = (string)$page_layout_val["layout_name"];
		$page_layout_cell_array = (array)$page_layout_val["cells"];
		// page layout object and add the layout cells to it
		$page_layout_object = new Layout($page_layout_name_val);
		for ($cell = 0; $cell < sizeof($page_layout_cell_array); $cell++) {
			$rect = new Rectangle(
				(int)$page_layout_cell_array[$cell][0], //x
				(int)$page_layout_cell_array[$cell][1], //y
				(int)$page_layout_cell_array[$cell][2], //width
				(int)$page_layout_cell_array[$cell][3]  //height
			);
			$page_layout_object->push_cell($rect);
		}
		// create the page object from the page name and layout object
		$page_object = new Page($page_name_val, $page_layout_object);
		
		// now we want to set the element data
		$elements_array = $page_val["elements"];
		for ($elem = 0; $elem < sizeof($elements_array); $elem++) {
			if ($elements_array[$elem] != 0) {
				$page_object->insert_element($elem, $elements_array[$elem]);
			}
		}
		
		//add the parsed page to the project and move to the next page
		$project_object->add_page($page_object);
	}
	return $project_object;
}

/**
	Functions for converting program objects into a single 
	file for downloading / loading .sodp files
*/

/**
	Takes the project data and item library program objects
	and outputs them to the given file .
*/
function serialize_sodp_file($project_data, $item_library, $file_path) {
	//simply write the object as a JSON object
	$fp = fopen($file_path, 'w');
	fwrite($fp, "{\n");
	fwrite($fp, "\"project_data\":\n");
	$project_data_str = project_data_to_string($project_data);
	fwrite($fp, $project_data_str);
	fwrite($fp, ",\n");
	fwrite($fp, "\"item_library\":\n");
	$item_library_str = item_library_to_string($item_library);
	fwrite($fp, $item_library_str);
	fwrite($fp, "\n");
	fwrite($fp, "}\n");
	fclose($fp);
}

/**
	Reads a single file and then returns as associative array of the form:
	[
		"project_data" => $project_data_object,
		"item_library" => $item_library_object
	]
*/
function deserialize_sodp_file($file_path) {
	$file_content = file_get_contents($file_path);
	return deserialize_sodp_string($file_content);
}

function deserialize_sodp_string($json_str) {
	$file_json_array = json_decode($json_str, true);
	
	$project_data_val = $file_json_array["project_data"];
	$item_library_val = $file_json_array["item_library"];
	
	$project_data_object = construct_project_data_object_from_json($project_data_val);
	$item_library_object = construct_item_library_object_from_json($item_library_val);
	
	return array(
		"project_data" => $project_data_object,
		"item_library" => $item_library_object
	);
}

/**
	Moved project directory creation into its own
	function for code reuse purposes (at least two PHP files use this)
*/
function create_sod_project_files($sod_dir, $possd, $project_name) {
	$projDir = $sod_dir."/possd-".$possd."/project-".$project_name."";
	
	$dirSuccess = TRUE;
	if (is_dir($projDir)) {
		$dirSuccess = TRUE;
	}
	else {
		$dirSuccess = mkdir($projDir);
	}
	
	if ($dirSuccess) {
		$filesToCreate = array($project_name."-element-page-tracker.txt",
			$project_name."-item-library.json", $project_name."-project.json");

		fclose(fopen($projDir.$filesToCreate[0], "w"));
		fclose(fopen($projDir.$filesToCreate[1], "w"));
		fclose(fopen($projDir.$filesToCreate[2], "w"));

		$filePathP = $sod_dir."/possd-".$possd."/".$possd."-POSSD-filepaths.json";+
		$filePaths = fopen($filePathP, "r");
		$pathsRead = fread($filePaths, filesize($filePathP));
		fclose($filePaths);

		$jsonPaths = json_decode($pathsRead, true);

		array_push($jsonPaths["projects"], $project_name);

		$filePaths = fopen($filePathP, "w");
		fwrite($filePaths, json_encode($jsonPaths, JSON_PRETTY_PRINT));
		fclose($filePaths);
	}
	else {
		//mkdir() returned error
	}
}


/**
	POS System Generation - See Section 5.1.9
	
	Generates the code for an element button. This 
	depends on the generate_element function.
*/	
function generate_element($element_id, $item) {
	
}	

/**
	POS System Generation - See Section 5.1.9

	Generates an HTML table from a Page object. This 
	depends on the generate_element function.
*/	
function generate_page($page, $item_library) {
	
}
	

?>
