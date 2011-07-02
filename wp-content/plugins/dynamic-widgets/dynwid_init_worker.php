<?php

/**
 * dynwid_init_worker.php
 *
 * @version $Id: dynwid_init_worker.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

	$DW->message('Dynamic Widgets INIT');
	echo "\n" . '<!-- Dynamic Widgets v' . DW_VERSION . ' //-->' . "\n";

	// UserAgent detection
	$DW->message('UserAgent: ' . $DW->useragent);

	// WPML Plugin Support
	if ( defined('ICL_PLUGIN_PATH') ) {
		$wpml_api = ICL_PLUGIN_PATH . DW_WPML_API;

		if ( file_exists($wpml_api) ) {
			require_once($wpml_api);

			$wpmlang = wpml_get_default_language();
			$curlang = wpml_get_current_language();
			$wpml = TRUE;
			$DW->message('WPML language: ' . $curlang);

			if ( $wpmlang != $curlang ) {
				$DW->wpml = TRUE;
				$DW->message('WPML enabled');
				require_once(DW_PLUGIN . 'wpml.php');
			}
		}
	}

	$DW->message('User has role(s): ' . implode(', ', $DW->userrole));

	$custom_post_type = FALSE;
	$DW->whereami = $DW->detectPage();
	$DW->message('Page is ' . $DW->whereami);

	if ( $DW->whereami == 'single' ) {
		$post = $GLOBALS['post'];
		$DW->message('post_id = ' . $post->ID);

		/* WordPress 3.0 and higher: Custom Post Types */
		if ( version_compare($GLOBALS['wp_version'], '3.0', '>=') ) {
			$post_type = get_post_type($post);
			$DW->message('Post Type = ' . $post_type);
			if ( $post_type != 'post' ) {
				$DW->custom_post_type = TRUE;
				$DW->whereami = $post_type;
				$DW->message('Custom Post Type detected, page changed to ' . $DW->whereami);
			}
		}
	}

	if ( $DW->whereami == 'page' ) {
		// WPSC/WPEC Plugin Support
		if ( defined('WPSC_VERSION') && version_compare(WPSC_VERSION, '3.8', '<') ) {
			$wpsc_query = &$GLOBALS['wpsc_query'];

			if ( $wpsc_query->category > 0 ) {
				$DW->wpsc = TRUE;
				$DW->whereami = 'wpsc';
				$DW->message('WPSC detected, page changed to ' . $DW->whereami . ', category: ' . $wpsc_query->category);

				require_once(DW_PLUGIN . 'wpsc.php');
			}
		} else if ( defined('BP_VERSION') ) {	// BuddyPress Plugin Support -- else if needed, otherwise WPEC pages are detected as BP
			$bp = &$GLOBALS['bp'];
			if (! empty($bp->current_component) ) {
				if ( $bp->current_component == 'groups' && ! empty($bp->current_item) ) {
					$DW->bp_groups = TRUE;
					$DW->whereami = 'bp-group';
					$DW->message('BP detected, component: ' . $bp->current_component . '; Group: ' . $bp->current_item . ', Page changed to bp-group');
				} else {
					$DW->bp = TRUE;
					$DW->whereami = 'bp';
					$DW->message('BP detected, component: ' . $bp->current_component . ', Page changed to bp');
				}
			}
		}
	}

	if ( $DW->whereami == 'archive' ) {
		// WPSC/WPEC Plugin Support
		if ( defined('WPSC_VERSION') && version_compare(WPSC_VERSION, '3.8', '>=') ) {
			$wpsc_query = &$GLOBALS['wpsc_query'];
			if ( $wpsc_query->query_vars['taxonomy'] == 'wpsc_product_category' ) {
				$DW->wpsc = TRUE;
				$DW->whereami = 'wpsc';
				$term = get_term_by('slug', $wpsc_query->query_vars['term'], 'wpsc_product_category');
				$DW->message('WPSC detected, page changed to ' . $DW->whereami . ', category: ' . $term->term_id);

				require_once(DW_PLUGIN . 'wpsc.php');
			}
		}
	}

	$DW->dwList($DW->whereami);
?>