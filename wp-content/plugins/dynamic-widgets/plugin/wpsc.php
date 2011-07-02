<?php
/**
 * WordPress Shopping Cart / WordPress E-Commerce Plugin
 * http://getshopped.org/
 *
 * @version $Id: wpsc.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

	function dw_wpsc_get_categories() {
		if ( version_compare(WPSC_VERSION, '3.8', '<' ) ) {
			$wpdb = &$GLOBALS['wpdb'];

			$categories = array();
			$table = WPSC_TABLE_PRODUCT_CATEGORIES;
			$fields = array('id', 'name');
			$query = "SELECT " . implode(', ', $fields) . " FROM " . $table . " WHERE active = '1' ORDER BY name";
			$results = $wpdb->get_results($query);

			foreach ( $results as $myrow ) {
				$categories[$myrow->id] = $myrow->name;
			}
		} else {
			$category_list = get_terms( 'wpsc_product_category', 'hide_empty=0');
			foreach ( $category_list as $cat ) {
					$categories[$cat->term_id] = $cat->name;
				}
		}

		return $categories;
	}

	function is_dw_wpsc_category($id) {
		$wpsc_query = &$GLOBALS['wpsc_query'];
		if ( version_compare(WPSC_VERSION, '3.8', '<') ) {
			$category = $wpsc_query->category;
		} else {
			$term = get_term_by('slug', $wpsc_query->query_vars['term'], 'wpsc_product_category');
			$category = $term->term_id;
		}

		if ( is_int($id) ) {
			$id = array($id);
		}

		if ( in_array($category, $id) ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
?>