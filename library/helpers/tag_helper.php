<?php

/**
 * wrap_values_in_element() - Wraps array values in the specified HTML element
 * 
 * @since 2.0
 * @param $array array
 * @param $element string
 * @return array
 */
function wrap_values_in_element($array, $element) {
	if(!is_array($array) || empty($array))
		return;
	
	$output = array();
	
	foreach($array as $value) {
		$output_value = "<$element>$value</$element>";
		array_push($output, $output_value);
	}
	
	return $output;
}

/**
 * implode_proper() - Implodes an array and provides a proper last connective
 * 
 * @since 2.0
 * @param $array array
 * @param $glue string
 * @param $last_connective string
 * @return string
 */
function implode_proper($array, $glue = ', ', $last_connective = 'and') {
	if( !is_array($array) || count($array) == 0 )
		return;
	
	$last_value = array_pop($array);
	
	if( count($array) )
		$output = implode($glue, $array) . " $last_connective $last_value";
	else
		$output = $last_value;
	
	$output = apply_filters('implode_proper', $output);
	return $output;
}

/**
 * multiple_tag_titles() - Outputs all tags for a tag archive
 * 
 * Tag intersections and unions currently don't have a simple, single template
 * function. This provides one.
 * @since 2.0
 * @global
 * @param $return boolean
 * @return string
 */
function multiple_tag_titles($return = false, $tag_wrapper = '') {
	global $wpdb;
	
	if ( !is_tag() )
		return;
	
	// Start horrible hack
	$tag_slugs = array();
	if( get_query_var('tag_slug__and') ) {
		$tag_slugs = get_query_var('tag_slug__and');
		$connective = __('and','tarski');
	} elseif( get_query_var('tag_slug__in') ) {
		$tag_slugs = get_query_var('tag_slug__in');
		$connective = __('or','tarski');
	} else {
		return;
	}

	$tag_ids = array();

	foreach ($tag_slugs as $tag_slug) {
		$tag_id = $wpdb->get_var("SELECT term_id FROM $wpdb->terms WHERE slug = \"$tag_slug\"");
		array_push($tag_ids, $tag_id);
	}
	// End horrible hack
	
	
	/*
	// This doesn't work - tag__and and tag__in are empty for tag intersections and unions
	if( get_query_var('tag__and') ) {
		$tag_slugs = get_query_var('tag__and');
		$connective = __('and','tarski');
	} elseif( get_query_var('tag__in') ) {
		$tag_slugs = get_query_var('tag__in');
		$connective = __('or','tarski');
	} else {
		return;
	}
	*/
	
	$tags = array();
	
	foreach ( $tag_ids as $tag_id ) {
		$tag = &get_term($tag_id, 'post_tag', OBJECT, 'display');
		if ( empty($tag) || is_wp_error($tag) )
			return;
		else
			array_push($tags, $tag->name);
	}
	
	if ( $tag_wrapper )
		$tags = wrap_values_in_element($tags, $tag_wrapper);

	$tags = implode_proper($tags, ', ', $connective);
	$tags = apply_filters('multiple_tag_titles', $tags);

	if ( $return )
		return $tags;
	else
		echo $tags;
}

?>