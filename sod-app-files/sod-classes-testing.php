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
	
	<p>Making a 200x300 rectangle at position 100, 200</p><br />
	<?php
		$testRect = new Rectangle(100, 200, 200, 300);
		
		echo "Properties: pos: $testRect->x, $testRect->y size: $testRect->width, $testRect->height<br />";
	?>
	
	 <h1>Testing Layout class</h1>
	
	 <p>Making a 3x3 grid layout and printing the rectangles</p><br />
	 
	 <?php
		$grd33 = create_grid_layout(4, 4, 600, 600, 10, 10);
		echo "Layout Name: {$grd33->get_layout_name()}<br />";
		for ($i = 0; $i < $grd33->get_cell_count(); $i++) {
			$rect = $grd33->get_cell($i);
		echo "Cell $i: pos: $rect->x, $rect->y size: $rect->width, $rect->height<br />";
		}
	 ?>
</body>
</html>