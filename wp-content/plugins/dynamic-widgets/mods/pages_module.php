<?php
/**
 * Pages Module
 *
 * @version $Id: pages_module.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

	define('DW_PAGE_LIMIT', 500);

	function getPageChilds($arr, $id, $i) {
		$pg = get_pages('child_of=' . $id);
		foreach ($pg as $p ) {
			if (! in_array($p->ID, $i) ) {
				$i[ ] = $p->ID;
				$arr[$p->ID] = array();
				$a = &$arr[$p->ID];
				$a = getPageChilds($a, $p->ID, &$i);
			}
		}
		return $arr;
	}

	function prtPgs($pages, $page_act, $page_childs_act, $static_page) {
		foreach ( $pages as $pid => $childs ) {
			$page = get_page($pid);

			echo '<div style="position:relative;left:15px;">';
			echo '<input type="checkbox" id="page_act_' . $page->ID . '" name="page_act[]" value="' . $page->ID . '" ' . ( isset($page_act) && count($page_act) > 0 && in_array($page->ID, $page_act) ? 'checked="checked"' : '' ) . ' onchange="chkChild(' . $pid . ')" /> <label for="page_act_' . $page->ID . '">' . $page->post_title . ' ' . ( get_option('show_on_front') == 'page' && isset($static_page[$page->ID]) ? '(' . $static_page[$page->ID] . ')' : '' ) . '</label><br />';

			echo '<div style="position:relative;left:15px;">';
			echo '<input type="checkbox" id="child_' . $pid . '" name="page_childs_act[]" value="' . $pid . '" ' . ( isset($page_childs_act) && count($page_childs_act) > 0 && in_array($pid, $page_childs_act) ? 'checked="checked"' : '' ) . ' onchange="chkParent(' . $pid . ')" /> <label for="child_' . $pid . '"><em>' . __('All childs', DW_L10N_DOMAIN) . '</em></label><br />';
			echo '</div>';

			if ( count($childs) > 0 ) {
				prtPgs($childs, $page_act, $page_childs_act, $static_page);
			}
			echo '</div>';
		}
	}

	function lsPages($pages, $static_page, $page_act) {
		foreach ( $pages as $page ) {
			echo '<input type="checkbox" id="page_act_' . $page->ID . '" name="page_act[]" value="' . $page->ID . '" ' . ( count($page_act) > 0 && in_array($page->ID, $page_act) ? 'checked="checked"' : '' ) . ' /> <label for="page_act_' . $page->ID . '">' . $page->post_title . ' ' . ( get_option('show_on_front') == 'page' && isset($static_page[$page->ID]) ? '(' . $static_page[$page->ID] . ')' : '' ) . '</label><br />';
 		}
	}

	$page_yes_selected = 'checked="checked"';
	$opt_page = $DW->getOptions($_GET['id'], 'page');
	if ( count($opt_page) > 0 ) {
		$page_act = array();
		foreach ( $opt_page as $page_condition ) {
			if ( $page_condition['maintype'] == 'page' ) {
				if ( $page_condition['name'] == 'default' || empty($page_condition['name']) ) {
					$page_default = $page_condition['value'];
				} else {
					$page_act[ ] = $page_condition['name'];
				}
			}
		}

		if ( $page_default == '0' ) {
			$page_no_selected = $page_yes_selected;
			unset($page_yes_selected);
		}

		// -- Childs
		$opt_page_childs = $DW->getOptions($_GET['id'], 'page-childs');
		if ( count($opt_page_childs) > 0 ) {
			$page_childs_act = array();
			foreach ( $opt_page_childs as $child_condition ) {
				if ( $child_condition['name'] != 'default' ) {
					$page_childs_act[ ] = $child_condition['name'];
				}
			}
		}
	}

	$pages = get_pages();
	$num_pages = count($pages);
	if ( $num_pages > DW_LIST_LIMIT ) {
		$page_condition_select_style = DW_LIST_STYLE;
	}

	$static_page = array();
	if ( get_option('show_on_front') == 'page' ) {
		if ( get_option('page_on_front') == get_option('page_for_posts') ) {
			$id = get_option('page_on_front');
			$static_page[$id] = __('Front page', DW_L10N_DOMAIN) . ', ' . __('Posts page', DW_L10N_DOMAIN);
		} else {
			$id = get_option('page_on_front');
			$static_page[$id] = __('Front page', DW_L10N_DOMAIN);
			$id = get_option('page_for_posts');
			$static_page[$id] = __('Posts page', DW_L10N_DOMAIN);
		}
	}

	if ( $num_pages < DW_PAGE_LIMIT ) {
		$pagemap = getPageChilds(array(), 0, array());
	}
?>
<h4><b><?php _e('Pages'); ?></b> <?php echo ( count($opt_page) > 0 ? ' <img src="' . $DW->plugin_url . 'img/checkmark.gif" alt="Checkmark" />' : '' ) . ( $DW->wpml ? $wpml_icon : '' ); ?></h4>
<div class="dynwid_conf">
<?php _e('Show widget default on static pages?', DW_L10N_DOMAIN); ?> <img src="<?php echo $DW->plugin_url; ?>img/info.gif" alt="info" title="<?php _e('Click to toggle info', DW_L10N_DOMAIN); ?>" onclick="divToggle('pages');" /><br />
<?php $DW->dumpOpt($opt_page); ?>
<div>
	<div id="pages" class="infotext">
	<?php
		if ( $num_pages < DW_PAGE_LIMIT ) {
			$childs_infotext = __('Checking the "All childs" option, makes the exception rule apply
					to the parent and all items under it in all levels. Also future items
					under the parent. It\'s not possible to apply an exception rule to
					"All childs" without the parent.', DW_L10N_DOMAIN);
		} else {
			$childs_infotext = __('Unfortunately the childs-function has been disabled
					because you have more than 500 pages.', DW_L10N_DOMAIN);
		}
		echo $childs_infotext;
	?>
	</div>
</div>
<input type="radio" name="page" value="yes" id="page-yes" <?php echo ( isset($page_yes_selected) ? $page_yes_selected : '' ); ?> /> <label for="page-yes"><?php _e('Yes'); ?></label>
<input type="radio" name="page" value="no" id="page-no" <?php echo ( isset($page_no_selected) ? $page_no_selected : '' ); ?> /> <label for="page-no"><?php _e('No'); ?></label><br />
<?php _e('Except the page(s)', DW_L10N_DOMAIN); ?>:<br />
<div id="page-select" class="condition-select" <?php echo ( isset($page_condition_select_style) ? $page_condition_select_style : '' ); ?>>
<div style="position:relative;left:-15px">
<?php ( $num_pages < DW_PAGE_LIMIT ? prtPgs($pagemap, $page_act, $page_childs_act, $static_page) : lsPages($pages, $static_page, $page_act) ); ?>
</div>
</div>
</div><!-- end dynwid_conf -->
