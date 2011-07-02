<?php

/**
 * Search Module
 *
 * @version $Id: search_module.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

	$search_yes_selected = 'checked="checked"';
	$opt_search = $DW->getOptions($_GET['id'], 'search');
	if ( count($opt_search) > 0 ) {
		$search_condition = $opt_search[0]['value'];
		if ( $search_condition == '0' ) {
			$search_no_selected = $search_yes_selected;
			unset($search_yes_selected);
		}
	}
?>

<h4><b><?php _e('Search Page', DW_L10N_DOMAIN); ?></b><?php echo ( count($opt_search) > 0 ? ' <img src="' . $DW->plugin_url . 'img/checkmark.gif" alt="Checkmark" />' : '' ); ?></h4>
<div class="dynwid_conf">
<?php _e('Show widget on the search page?', DW_L10N_DOMAIN); ?><br />
<?php $DW->dumpOpt($opt_search); ?>
<input type="radio" name="search" value="yes" id="search-yes" <?php echo ( isset($search_yes_selected) ? $search_yes_selected : '' ); ?> /> <label for="search-yes"><?php _e('Yes'); ?></label>
<input type="radio" name="search" value="no" id="search-no" <?php echo ( isset($search_no_selected) ? $search_no_selected : '' ); ?> /> <label for="search-no"><?php _e('No'); ?></label>
</div><!-- end dynwid_conf -->
