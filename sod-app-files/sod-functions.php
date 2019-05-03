<?php 
	
/* The #include for PHP */
require('sod-classes.php');
	
	
	
/**
	Creates a grid layout object for a page of the given page width,
	with the number of rows and columns. The last two parameters
	specify the spacing betweeen elements
*/
function create_grid_layout($rows, $columns, $page_width, $page_height, $hspace, $vspace) {
	$grid_layout = new Layout("Grid Layout $rows x $columns");
	
	if ($hspace*($columns+1)>=$page_width or $vspace*($rows+1)>=$page_height) {
		echo "Cannot create GridLayout, the spacing is too large!";
		return nil;
	}
	else {
		$cellWidth = ($page_width - ($columns+1)*$hspace)/$columns;
		$cellHeight = ($page_height - ($rows+1)*$vspace)/$rows;		
		for ($r = 0; $r < $rows; $r++) {
			for ($c = 0; $c < $columns; $c++) {
				$rect = new Rectangle(
					$hspace + $c*($cellWidth+$hspace),
					$vspace + $r*($cellHeight+$vspace),
					$cellWidth,
					$cellHeight
				);
				$grid_layout->push_cell($rect);
			}
		}
		return $grid_layout;
	}
}
	
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
	
?>