<?php
/*
Plugin Name: Wordpress Breadcrumbs
Plugin URI: http://drnaylor.co.uk/software/wordpress/
Description: This allows users to use breadcrumbs in their Wordpress Install
Author: Daniel R Naylor
Version: 1.2.3
Author URI: http://drnaylor.co.uk/

This plugin is licensed under the terms of the GNU GPL v2 licence
Read it here - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

In other words, feel free to use this code in your own projects, PROVIDED THAT

a) Any modifications that you make to this code are also open source
b) Any code based on this code must remain under the GPL licence.
c) Any code combined with this licence must be GPL-compatible

The copyright holder of this code is Daniel Naylor (http://drnaylor.co.uk)
Thanks to Tim for a bug fix in 1.2.3

*/ 

function breadcrumbs($display = true) {
	/*
	CONFIGURUATION
	This variable is the character for the sepeartor for the breadcrumbs. For a ">" symbol (the default), place in "&gt;"
	
	*/
	$sep = '&gt;';
	
	
	//END OF CONFIG
	//I know, that was short, wasn't it?
	//Don't edit anything below this line unless you know what you are doing...
	
	global $wpdb, $wp_locale, $wp_query, $year, $monthnum, $day;

	$cat = get_query_var('cat');
	$p = get_query_var('p');
	$name = get_query_var('name');
	$category_name = get_query_var('category_name');
	$author = get_query_var('author');
	$author_name = get_query_var('author_name');
	$m = get_query_var('m');
	$year = get_query_var('year');
	$monthnum = get_query_var('monthnum');
	$day = get_query_var('day');
	$search = $_REQUEST['s'];


		$title = get_bloginfo('name');
		
		

	// If there's a category
	if ( !empty($cat) ) {
			// category exclusion
			if ( !stristr($cat,'-') )
				$zmake = apply_filters('single_cat_title', get_the_category_by_ID($cat));
				$title = '<a href="' . get_bloginfo('url') . '">' . get_bloginfo('name') . '</a> ' . $sep . ' Category:' . $zmake;
	if (!empty($search))
	{
		$title .= '' . $sep . ' Search Results for ' . $search;
	}
	elseif ($search != '')
    {
		$title .= '' . $sep . ' Search Results for ' . $search;
	}
	
				
				
				//$title .= ;
	} elseif ( !empty($category_name) ) {
		if ( stristr($category_name,'/') ) {
				$category_name = explode('/',$category_name);
				if ( $category_name[count($category_name)-1] )
					$category_name = $category_name[count($category_name)-1]; // no trailing slash
				else
					$category_name = $category_name[count($category_name)-2]; // there was a trailling slash
		}
		$title = $wpdb->get_var("SELECT cat_name FROM $wpdb->categories WHERE category_nicename = '$category_name'");
		$zmake2 = apply_filters('single_cat_title', $title);
		$title = '<a href="' . get_bloginfo('url') . '">' . get_bloginfo('name') . '</a> ' . $sep . ' Category: ' . $zmake2;
		
			if (!empty($search))
	{
		$title .= '' . $sep . ' Search Results for ' . $search;
	}
	elseif ($search != '')
    {
		$title .= '' . $sep . ' Search Results for ' . $search;
	}
	
	}  //if date
	
		if ( !empty($author) ) {
		$title = get_userdata($author);
		$title = '<a href="' . get_bloginfo('url') . '">' . get_bloginfo('name') . '</a> ' . $sep . ' ' . $title->display_name;
	}
	if ( !empty($author_name) ) {
		// We do a direct query here because we don't cache by nicename.
		$title = '<a href="' . get_bloginfo('url') . '">' . get_bloginfo('name') . '</a> ' . $sep . ' ' . $wpdb->get_var("SELECT display_name FROM $wpdb->users WHERE user_nicename = '$author_name'");
	}	
	 if ( is_single() ) 
	 { 
	 		if ($year == "0" || $monthnum == "0" || $day == "0")
	 		{
				$title = '<a href="' . get_bloginfo('url') . '">' . get_bloginfo('name') . '</a> ' . $sep . ' ' . the_title2(); 
			}
			else
			{
				$title = '<a href="' . get_bloginfo('url') . '">' . get_bloginfo('name') . '</a> ' . $sep . ' <a href="' . get_bloginfo('url') . '/' . $year . '/">' . $year . '</a> ' . $sep . ' <a href="' . get_bloginfo('url') . '/' . $year . '/' . zeroise($monthnum, 2) . '/">' . $wp_locale->get_month($monthnum) . '</a> ' . $sep . ' <a href="' . get_bloginfo('url') . '/' . $year . '/' . zeroise($monthnum, 2) . '/' . zeroise($day, 2) . '">' . $day . '</a> ' . $sep . ' ' . the_title2(); 
			}
	}	
	elseif ( is_page() )
	{
	$this_page = get_the_ID();
	$page_no = get_the_ID();
	$parent = 0;
	$link = get_permalink($page_no);
	$middle_title = '';
	do
	{
		$output = get_post($page_no);
		$parent = $output->post_parent;
		if ($parent != 0)
		{
			$page_no = $parent;
			$output = get_post($page_no);
			$middle_title = '<a href="' . $output->guid . '">' . $output->post_title . '</a> ' . $sep . ' ' . $middle_title;
		}
		
	} while ($parent != 0);
		$title = '<a href="' . get_bloginfo('url') . '">' . get_bloginfo('name') . '</a> ' . $sep . ' ' . $middle_title . the_title2();
	}
	elseif ( !empty($year) ) {
		$title = '<a href="' . get_bloginfo('url') . '">' . get_bloginfo('name') . '</a> ' . $sep . ' <a href="' . get_bloginfo('url') . '/' . $year . '/">' . $year . '</a> ';
		if ( !empty($monthnum) )
			$title .= '' . $sep . ' <a href="' . get_bloginfo('url') . '/' . $year . '/' . zeroise($monthnum, 2) . '/">' . $wp_locale->get_month($monthnum) . '</a>';
		if ( !empty($day) )
			$title .= '' . $sep . ' <a href="' . get_bloginfo('url') . '/' . $year . '/' . zeroise($monthnum, 2) . '/' . zeroise($day, 2) . '">' . $day . '</a>';
	} 
	
	if (!empty($search))
	{
		$title = '<a href="' . get_bloginfo('url') . '">' . get_bloginfo('name') . '</a> ' . $sep . ' Search Results for ' . $search;
	}
	elseif ($search != '')
    {
	$title = '<a href="' . get_bloginfo('url') . '">' . get_bloginfo('name') . '</a> ' . $sep . ' Search Results for ' . $search;
	}
	
	
	if ( $display )
		echo $title;
	else
		return $title;
}

function the_title2($before = '', $after = '', $echo = false) {
	$title = get_the_title();
	if ( strlen($title) > 0 ) {
		$title = apply_filters('the_title', $before . $title . $after, $before, $after);
		if ( $echo )
			echo $title;
		else
			return $title;
	}
}

?>