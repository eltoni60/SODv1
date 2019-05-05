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


	public function debug_info_dump() {
		echo "Properties: pos: $this->x, $this->y size: $this->width, $this->height<br />";
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
	
	function __destruct() {
		//Insert any necessary cleanup here
	}
	
	// to only be used for the initial construction of a layout object
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
	
	/**
		Creates a grid layout object for a page of the given page width,
		with the number of rows and columns. The last two parameters
		specify the spacing betweeen elements
	*/
	public static function create_grid_layout($rows, $columns, $page_width, $page_height, $hspace, $vspace) {
		$grid_layout = new Layout("Grid Layout $rows x $columns");
		
		if ($hspace*($columns+1)>=$page_width or $vspace*($rows+1)>=$page_height) {
			echo "Cannot create GridLayout, the spacing is too large!";
			return null;
		}
		else {
			// use the floor function to ensure that the cell sizes are integers
			$cellWidth = floor(($page_width - ($columns+1)*$hspace)/$columns);
			$cellHeight = floor(($page_height - ($rows+1)*$vspace)/$rows);		
			for ($r = 0; $r < $rows; $r++) {
				for ($c = 0; $c < $columns; $c++) {
					$rect = new Rectangle(
						floor($hspace + $c*($cellWidth+$hspace)),
						floor($vspace + $r*($cellHeight+$vspace)),
						$cellWidth,
						$cellHeight
					);
					$grid_layout->push_cell($rect);
				}
			}
			return $grid_layout;
		}
	}
	
	public function debug_info_dump() {
		echo "Layout Name: {$this->get_layout_name()}<br />";
		for ($i = 0; $i < $this->get_cell_count(); $i++) {
			$rect = $this->get_cell($i);
			echo "Cell $i: pos: $rect->x, $rect->y size: $rect->width, $rect->height<br />";
		}
	}
}
	
/* Program object for an individual page layout / 
	tab on generated POS*/
class Page {
	
	// the name of this page. This will be the name of the tab as well
	private $page_name;
	// list of layout cells
	private $layout;
	// array of element IDs for corresponding layout cells
	private $element_list;
	
	function __destruct() {
		//Insert any necessary cleanup here
	}
	
	function __construct($name, $_layout) {
		$this->page_name = $name;
		$this->layout = $_layout;
		$this->element_list = array();
		for ($i = 0; $i < $this->layout->get_cell_count(); $i++) {
			array_push($this->element_list, 0); // no elements 
		}
	}
	
	public function get_page_name() {
		return $this->page_name;
	}
	
	public function get_layout() {
		return $this->layout;
	}
	
	/**
		See Section 5.1.7.2 of the System Design Document.
	*/
	public function change_layout($new_layout) {
		if ($this->get_cell_count() > $new_layout->get_cell_count()) {
			// it just squishes everything to the left
			$elements_to_process = $this->get_element_count();
			$iterations = 0;
			$last_moved_element = 0;
			$to_move = 0;
			$first_empty = 0;
			while ($elements_to_process > 0) {
								
				for ($i = 0; $i < $this->get_cell_count(); $i++) {
					if ($this->element_list[$i] == 0) {
						$first_empty = $i;
						break;
					}
				}		

				for ($i = $last_moved_element+1; $i < $this->get_cell_count(); $i++) {
					if ($this->element_list[$i] != 0) {
						$to_move = $i;
						break;
					}
				}	
				$last_moved_element = $to_move;		
				$this->move_element($to_move, $first_empty);
				$elements_to_process--;
			}
		}
		//transfer to the new layout
		$new_element_list = array();
		for ($i = 0; $i < $new_layout->get_cell_count(); $i++) {
			array_push($new_element_list, 0);
		}
		
		for ($i = 0; $i < $this->get_cell_count(); $i++) {
			$new_element_list[$i] = $this->element_list[$i];
		}
		// old stuff will be garbage collected?
		$this->element_list = $new_element_list;
		$this->layout = $new_layout;
	}
	
	/**
		See Section 5.1.7.2 of the System Design Document.
	*/
	public function can_change_layout($new_layout) {
		if ($this->get_element_count() > $new_layout->get_cell_count()) {
			return false;
		}
		else {
			return true;
		}
	}
	
	//convenience function
	public function get_cell_count() {
		return $this->layout->get_cell_count();
	}
	
	/**
		Returns a count for the number of 
		actual elements. Not the most efficient 
		to recount it every time but
		it will work for now.
	*/
	public function get_element_count() {
		$counter = 0;
		for ($i = 0; $i < $this->layout->get_cell_count(); $i++) {
			if ($this->element_list[$i] != 0) {
				$counter++;
			}
		}
		return $counter;
	}
	
	/**
		See Section 5.1.7.1
	*/
	public function insert_element($index, $element) {
		if ($element == 0) {
			echo "Cannot add the no element to a page";
			return;
		}
		$this->element_list[$index] = $element;
	}
	
	/**
		See Section 5.1.7.3.
		This function returns the element ID of the
		element that was just removed.
		
		It is the responsibility of the 
		user of this class to place the 
		element that was removed back into
		the Element Toolbox.
	*/
	public function remove_element($index) {
		$removing = $this->element_list[$index];
		$this->element_list[$index] = 0;
		return $removing;
	}
	
	/**
		See Section 5.1.7.5.
		
		This is a lossless function. It will
		return false if the place it was
		trying to move the function to already had
		an element in it. It will return true 
		if it successfully moved the element.
	*/
	public function move_element($from_index, $to_index) {
		if ($this->element_list[$to_index] != 0) {
			return false;
		}
		else {
			$this->element_list[$to_index] = $this->element_list[$from_index];
			$this->element_list[$from_index] = 0;
			return true;
		}
	}
	
	/**
		Gets the index of a specified element ID.
		Returns -1 if it cannot find the specified element ID.
	*/
	public function find_element($element) {
		for ($i = 0; $i < $this->layout->get_cell_count(); $i++) {
			if ($this->element_list[$i] == $element) {
				return $element;
			}
		}
		return -1;
	}
	
	/**
		Gets the element at the specified cell index.
		Returns 0 if there is no element in that location.
	*/
	public function get_element_by_cell_location($cell_index) {
		return $this->element_list[$cell_index];
	}
	
	//For Section 5.1.7.4, the responsibility of selecting
	//an element will be left to the user of this class
	
	//The hitElement function is only really used for the
	//client-side version of this function.
	
	
	public function debug_info_dump() {
		echo "Page name: $this->page_name<br />";
		$this->layout->debug_info_dump();
		echo "Element list:<br />";
		for ($i = 0; $i < $this->layout->get_cell_count(); $i++) {
		echo "{$this->element_list[$i]}<br />";
		}
	}
}
	
/*Program object for an item on the menu*/
class Item {
	
	// TODO: automatic item ID incrementation
	//  challenge, synchronization between program instances
	private $item_id;
	private $item_name;
	private $item_price;
	private $attributes;
	
	function __construct($id, $name, $price) {
		$this->item_id = $id;
		$this->item_name = $name;
		$this->item_price = $price;
		$this->attributes = array();
	}
	
	function __destruct() {
		//Insert any necessary cleanup here
	}
	
	public function get_item_id() {
		return $this->item_id;
	}
	
	public function get_item_name() {
		return $this->item_name;
	}
	
	public function get_item_price() {
		return $this->item_price;
	}
	
	public function set_item_name($name) {
		$this->item_name = $name;
	}
	
	public function set_item_price($price) {
		$this->item_price = $price;
	}
	
	public function get_attribute_value($attr_name) {
		return $this->attributes[$attr_name];
	}
	
	public function set_attribute($attr_name, $attr_value) {
		$this->attributes[$attr_name] = $attr_value;
	}
	
	public function list_attribute_names() {
		return array_keys($this->attributes);
	}
	
	public function debug_info_dump() {
		echo "Item ID: $this->item_id, Name: $this->item_name, Price: $this->item_price<br />";
		if (count($this->attributes) == 0) {
			echo "No attributes for this item<br />";
		}
		else {
			$keys = $this->list_attribute_names();
			for ($i = 0; $i < count($keys); $i++) {
				echo "Attribute $i: {$keys[$i]} = {$this->attributes[$keys[$i]]}<br />";
			}
		}
	}
}
	
/*Program object for the project-data. Essentially a container for Page objects*/
class SOD_Project {
	
	private $project_name;
	private $pages;
	
	function __construct($name) {
		$this->project_name = $name;
		$this->pages = array();
	}
	
	function __destruct() {
		//Insert any necessary cleanup here
	}
	
	public function get_project_name() {
		return $this->project_name;
	}
	
	public function get_page_count() {
		return count($this->pages);
	}
	
	/**
		If this project already has a page
		with the same name as the given page,
		then this function will return false.
		Otherwise, if the page add operation is
		successful, it will return true.
	*/
	public function add_page($page) {
		if ($this->get_page($page->get_page_name()) == null) {
			array_push($this->pages, $page);
			return true;
		}
		else {
			return false;
		}
	}
	
	public function remove_page($page_name) {
		for ($i = 0; $i < count($this->pages); $i++) {
			if (strcmp($this->pages[$i]->get_page_name(), $page_name) == 0) {
				//remove page $i
				array_splice($this->pages, $i, 1);
				return;
			}
		}
	}
	
	/**
		Finds the page named $page_name in this project and 
		returns it. If the project does not have a page with
		that name, it returns null;
	*/
	public function get_page($page_name) {
		for ($i = 0; $i < count($this->pages); $i++) {
			if (strcmp($this->pages[$i]->get_page_name(), $page_name) == 0) {
				return $this->pages[$i];
			}
		}
		return null;
	}
	
	/**
		This might be useful later. It builds an array of
		all of the element IDs that are used by all of the pages.
		This list is not sorted.
		
		If it finds duplicate elements, then it echo and error message.
	*/
	public function list_used_elements() {
		$used_elements = array();
		for ($i = 0; $i < count($this->pages); $i++) {
			$page = $this->pages[$i];
			for ($k = 0; $k < $page->get_cell_count(); $k++) {
				$elem_id = $page->get_element_by_cell_location($k);
				// 0s are not actually elements
				if ($elem_id != 0) {
					//https://www.php.net/manual/en/function.array-search.php
					if (array_search($elem_id, $used_elements) === FALSE) {
						//we can safely add this to the array
						array_push($used_elements, $elem_id);
					}
					else {
						//echo an error becuse there are duplicate elements
						//placed in this SOD_Project
						echo "<h1 style=\"color:red;\">FATAL: There are duplicate elements placed in this project</h1>";
						return null;
					}
				}
			}
		}
		return $used_elements;
	}
	
	// for iterating over pages
	public function list_page_names() {
		$page_names = array();
		for ($i = 0; $i < count($this->pages); $i++) {
			array_push($page_names, $this->pages[$i]->get_page_name());
		}
		return $page_names;
	}
	
	public function debug_info_dump() {
		echo "Project Name: $this->project_name<br />";
		if (count($this->pages) == 0) {
			echo "No pages for this project<br />";
		}
		else {
			for ($i = 0; $i < count($this->pages); $i++) {
				$this->pages[$i]->debug_info_dump();
				echo '<br />';
			}
		}
	}
}
	
/*Program object for the Item Library. Essentially a container for Item objects*/
class Item_Library {
	
	private $items;

	function __construct() {
		//Default Initialization of me
		$this->items = array();
	}
	
	function __destruct() {
		//Insert any necessary cleanup here
	}
	
	/**
		See Section 5.1.5.2 for the full description of the 
		functionality that this is the server-side function for.
		
		If the Item_Library already has an item with the 
		specified ID, then it will not add it and return false.
		Otherwise it returns true on a successful add.
	*/
	public function add_item($item) {
		if ($this->get_item($item->get_item_id()) == null) {
			array_push($this->items, $item);
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
		See Section 5.1.5.4 for the full description of the 
		functionality that this is the server-side function for.
	*/
	public function remove_item($item_id) {
		for ($i = 0; $i < count($this->items); $i++) {
			if ($this->items[$i]->get_item_id() == $item_id) {
				array_splice($this->items, $i, 1);
				return;
			}
		}
	}

	// currently O(n), but if the list is sorted
	// by IDs and the find function is implemented as binary search
	// then it could get item lookup to O(log N)
	public function get_item($item_id) {
		for ($i = 0; $i < count($this->items); $i++) {
			if ($this->items[$i]->get_item_id() == $item_id) {
				return $this->items[$i];
			}
		}
		return null;
	}

	public function get_item_by_name($name) {
		for ($i = 0; $i < count($this->items); $i++) {
			if (strcmp($this->items[$i]->get_item_name(), $name) == 0) {
				return $this->items[$i];
			}
		}
		return null;
	}
	
	// for iterating over the item library
	public function list_item_ids() {
		$ids = array();
		for ($i = 0; $i < count($this->items); $i++) {
			array_push($ids, $this->items[$i]->get_item_id());
		}
		return $ids;
	}
	
	public function debug_info_dump() {
		echo "The Item Library:<br />";
		for ($i = 0; $i < count($this->items); $i++) {
			$this->items[$i]->debug_info_dump();
		}
	}
}

?>

