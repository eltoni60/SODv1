<?php
/* These PHP classes mirror those in sod_classes.js */
	
/*A way to represent rectangles for the Layout class*/
class Rectangle {
	public $x;
	public $y;
	public $width;
	public $height;

	function __construct($x, $y, $width, $height) {
		$this->x = $x;
		$this->y = $y;
		$this->width = $width;
		$this->height = $height;
	}
	
	function __destruct() {
		//Insert any necessary cleanup here
	}
	
}
	
/*Program object for the layout of a Page*/
class Layout {
	
	private $layout_name;
	private $rectangles;
	
	function __construct($name) {
		$this->layout_name = $name;
		//Rectangles to be added from other functions
		$this->rectangles = array();
	}
	
	public function push_cell($rect) {
		array_push($this->rectangles, $rect);
	}
	
	public function get_cell_count() {
		return count($this->rectangles);
	}
	
	public function get_cell($index) {
		return $this->rectangles[$index];
	}
	
	public function get_layout_name() {
		return $this->layout_name;
	}
	
	function __destruct() {
		//Insert any necessary cleanup here
	}
	
}
	
/* Program object for an individual page layout / 
	tab on generated POS*/
class Page {
	
	function __construct() {
		//Default Initialization of me
	}
	
	function __destruct() {
		//Insert any necessary cleanup here
	}
	
}
	
/*Program object for an item on the menu*/
class Item {
	
	function __construct() {
		//Default Initialization of me
	}
	
	function __destruct() {
		//Insert any necessary cleanup here
	}
	
}
	
/*Program object for the project-data*/
class SOD_Project {
	
	function __construct() {
		//Default Initialization of me
	}
	
	function __destruct() {
		//Insert any necessary cleanup here
	}
	
}
	
/*Program object for the Item Library*/
class Item_Library {

	function __construct() {
		//Default Initialization of me
	}
	
	function __destruct() {
		//Insert any necessary cleanup here
	}
		
}

?>