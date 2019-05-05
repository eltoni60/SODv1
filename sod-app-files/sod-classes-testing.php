<!DOCTYPE html>
<!-- We can git rm this file once we are done with
	 testing all of the PHP classes -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SOD</title>
</head>
<body>
	<?php 
		require('sod-functions.php');
	?>
		
	<h4>Testing Rectangle class</h4>
	
	<p>Making a 200x300 rectangle at position 100, 200</p>
	<?php
		$testRect = new Rectangle(100, 200, 200, 300);
		
	?>
	
	 <h4>Testing Layout class</h4>
	
	 <p>Making a 3x3 grid layout and printing the rectangles</p>
	 
	 <?php
		$grd44 = Layout::create_grid_layout(4, 4, 600, 600, 10, 10);
		$grd44->debug_info_dump();
	 ?>
	 
	 <h4>Testing the Page class</h4>
	 
	 <p>Making a page object with the previous layout and inserting some elements into it</p>
	 
	 <?php
		
		$pagee = new Page("My Test Page", $grd44);
		
		$pagee->insert_element(0, 8);
		$pagee->insert_element(1, 1);
		$pagee->insert_element(15, 3);
		
		echo "Changing the page layout<br />";
		
		//the creation of layouts shall be static functions in the Layout class
		//this is so the rule of not changing a layout after it is created can
		//be enforced.
		$small_layout = Layout::create_grid_layout(2, 2, 600, 600, 20, 20);
		$break_layout = Layout::create_grid_layout(1, 1, 400, 400, 10, 10);
		
		//Always check that the new layout will fit the new elements
		//before changing it
		if ($pagee->can_change_layout($break_layout)) {
			$pagee->change_layout($break_layout);
		}
		else {
			echo "Cannot change to that layout, too many elements in the page";
		}
		
		if ($pagee->can_change_layout($small_layout)) {
			$pagee->change_layout($small_layout);
		}
		else {
			echo "Cannot change to that layout, too many elements in the page";
		}
		
		$pagee->debug_info_dump();
		
	 ?>
	 
	 <h4>Testing Item</h4>
	 
	 <p>Creating two simple items</p>
	 
	 <?php 
	 
		$item1 = new Item(1, "Cheeseburger", 2.5);
		$item2 = new Item(2, "Fries", 1.25);
		
		$item1->debug_info_dump();
		$item2->debug_info_dump();
	 
	 ?>
	 
	 <h4>Testing the SOD_Project class</h4>
	 
	 <p>Creating a project with two pages in it</p>
	 
	 <?php
		$test_project = new SOD_Project("Test Project");
		
		$test_project->add_page($pagee);
		$page2 = new Page("Another page", $grd44); // can reuse layouts
		$page2->insert_element(4, 6);
		$page2->insert_element(6, 9);
		$page2->insert_element(8, 11);
		$page2->insert_element(12, 2);
		$test_project->add_page($page2);
	 
		$test_project->debug_info_dump();
	 
		$element_list_project = $test_project->list_used_elements();
	?>
	<p> The elements in this project </p>
	<ul>
	<?php
		for ($i = 0; $i < count($element_list_project); $i++) {
			echo "<li>{$element_list_project[$i]}</li>\n";
		}
	 ?>
	 </ul>
	 
	 <h4>Testing the Item_Library class</h4>
	 
	 <p> Creating an Item Library and then adding the items we made earlier to it.</p>
	 
	 <?php
		$test_item_lib = new Item_Library;
		$test_item_lib->add_item($item1);
		$test_item_lib->add_item($item2);
		$test_item_lib->debug_info_dump();
		
		//serialize_item_library($test_item_lib, "test_output_item_lib.json");
	 ?>
	 
	 <h4>Testing deserialization on example project</h4>
	 
	 <p>Deserializing project data and item library and then showing it here</p>
	 
	 <?php
	 
		$example_project_data = deserialize_project_data("./../possd-EXAMPLE/project-1/1-project.json");
		$example_item_library = deserialize_item_library("./../possd-EXAMPLE/project-1/1-item-library.json");
		$example_project_data->debug_info_dump();
		$example_item_library->debug_info_dump();
	 ?>
	 
</body>
</html>