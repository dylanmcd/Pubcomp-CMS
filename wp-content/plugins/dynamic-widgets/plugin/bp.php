<?php

/**
 * BuddyPress Plugin
 * http://buddypress.org/
 *
 * @version $Id: bp.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

	function dw_get_bp_components() {
		$bp = &$GLOBALS['bp'];
		$DW = &$GLOBALS['DW'];
		$components = array();

		if ( in_array('groups', $bp->active_components) ) {
			$DW->bp_groups = TRUE;
		}
		foreach ( $bp->active_components as $c ) {
			if ( $c == 'groups' ) {
				$components[$c] = ucfirst($c) . ' (only main page)';
			} else {
				$components[$c] = ucfirst($c);
			}
		}

		asort($components);
		return $components;
	}

	function dw_get_bp_groups() {
		$bp = &$GLOBALS['bp'];
		$wpdb = &$GLOBALS['wpdb'];

		$groups = array();
		$table = $bp->groups->table_name;
		$fields = array('slug', 'name');
		$query = "SELECT " . implode(', ', $fields) . " FROM " . $table . " ORDER BY name";
		$results = $wpdb->get_results($query);

		foreach ( $results as $myrow ) {
			$groups[$myrow->slug] = $myrow->name;
		}

		return $groups;
	}

	function is_dw_bp_component($id) {
		$bp = &$GLOBALS['bp'];

		$component = $bp->current_component;
		if ( in_array($component, $id) ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function is_dw_bp_group($id) {
		$bp = &$GLOBALS['bp'];

		$group = $bp->current_item;
		if ( in_array($group, $id) ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
?>