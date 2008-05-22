<?php

/**
 * class WalkerPageSelect
 * 
 * Extends the Walker class for generating HTML for tree-like structures.
 * Used to generate a tree of ordered lists of pages, with associated
 * form elements to allow for the selection of particular pages.
 * @package Tarski
 * @since 2.2
 */
class WalkerPageSelect extends Walker {
	
	var $tree_type = 'page';
	var $db_fields = array('parent' => 'post_parent', 'id' => 'ID');
	var $selected = array();
	
	/**
	 * WalkerPageSelect() - Constructor for the class.
	 * 
	 * Constructor to allow a list of selected pages to be passed in.
	 * @since 2.2
	 */
	function WalkerPageSelect($selected = array()) {
		$this->selected = $selected;
	}
	
	/**
	 * start_lvl() - Start a level.
	 * 
	 * Implements the abstract start_lvl() function from the Walker class,
	 * starting a particular level of the tree by opening an ordered list.
	 * @since 2.2
	 */
	function start_lvl(&$output, $depth) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ol>\n";
	}

	/**
	 * end_lvl() - End a level.
	 * 
	 * Implements the abstract end_lvl() function from the Walker class,
	 * ending a particular level of the tree by closing an ordered list.
	 * @since 2.2
	 */
	function end_lvl(&$output, $depth) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ol>\n";
	}
	
	/**
	 * start_el() - Start an element, and add its content.
	 * 
	 * Implements the abstract start_el() function from the Walker class,
	 * starting a particular element of the tree by opening a list item
	 * and adding its content.
	 * @since 2.2
	 */
	function start_el(&$output, $page, $depth) {
		if ( $depth )
			$indent = str_repeat("\t", $depth);
		else
			$indent = '';
		
		if ( in_array($page->ID, $this->selected) )
			$checked = ' checked="checked"';
		else
			$checked = '';
		
		$output .= $indent. '<li>';
		$output .= '<p class="nav-page">'.
			'<label for="opt-pages-'. $page->ID. '">'. apply_filters('the_title', $page->post_title). '</label> '.
			'<a title="'. __('View this page','tarski'). '" href="'. get_page_link($page->ID). '">&#8599;</a> '.
			'<input id="opt-pages-'. $page->ID. '" name="nav_pages[]" type="checkbox" value="'. $page->ID. '"'. $checked. ' />'.
		'</p>';
	}
	
	/**
	 * end_el() - End an element.
	 * 
	 * Implements the abstract end_el() function from the Walker class,
	 * ending a particular element of the tree by closing a list item.
	 * @since 2.2
	 */
	function end_el(&$output, $page, $depth) {
		$output .= "</li>\n";
	}
	
}

?>