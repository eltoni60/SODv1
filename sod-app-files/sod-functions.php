<?php 
	
/* The #include for PHP */
require('sod-classes.php');

	
/**
	Converts the program object representation of
	an Item_Library into its JSON representation as
	described in Section 3.2 and saves it to $file_path.
*/
function serialize_item_library($item_lib, $file_path) {
	
}

/**
	Converts the program object representation of
	a SOD_Project into its JSON representation as
	described in Section 3.2 of the System Design
	Document and saves it to $file_path.
*/
function serialize_project_data($project_data, $file_path) {
	
}
	
/**
	Reads the JSON representation of an Item_Library
	located at $file_path and returns the program
	object representation of it. The JSON file is
	expected to follow the form in Section 3.2 of the 
	System Design Document.
*/
function deserialize_item_library($file_path) {
	
}

/**
	Reads the JSON representation of a SOD_Project
	located at $file_path and returns the program
	object representation of it. The JSON file is
	expected to follow the form in Section 3.2 of the 
	System Design Document.
*/
function deserialize_project_data($file_path) {
	
}

/**
	Reads the JSON representation of an Item_Library
	object and returns the program object representation
	ofit. The JSON file is expected to follow the form 
	in Section 3.2 of the System Design Document.
*/
function deserialize_item_library_str($json_str) {
	
}

/**
	Reads the JSON representation of a SOD_Project object
	and returns the program object representation of
	it. The JSON file is expected to follow the form 
	in Section 3.2 of the System Design Document.
*/
function deserialize_project_data_str($json_str) {
	
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