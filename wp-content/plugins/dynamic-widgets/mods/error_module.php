<?php
/**
 * Error Module
 *
 * @version $Id: error_module.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

	$e404_yes_selected = 'checked="checked"';
	$opt_e404 = $DW->getOptions($_GET['id'], 'e404');
	if ( count($opt_e404) > 0 ) {
		$e404_condition = $opt_e404[0]['value'];
		if ( $e404_condition == '0' ) {
			$e404_no_selected = $e404_yes_selected;
			unset($e404_yes_selected);
		}
	}
?>

<h4><b><?php _e('Error Page', DW_L10N_DOMAIN); ?></b><?php echo ( count($opt_e404) > 0 ? ' <img src="' . $DW->plugin_url . 'img/checkmark.gif" alt="Checkmark" />' : '' ); ?></h4>
<div class="dynwid_conf">
<?php _e('Show widget on the error page?', DW_L10N_DOMAIN); ?><br />
<?php $DW->dumpOpt($opt_e404); ?>
<input type="radio" name="e404" value="yes" id="e404-yes" <?php echo ( isset($e404_yes_selected) ? $e404_yes_selected : '' ); ?> /> <label for="e404-yes"><?php _e('Yes'); ?></label>
<input type="radio" name="e404" value="no" id="e404-no" <?php echo ( isset($e404_no_selected) ? $e404_no_selected : '' ); ?> /> <label for="e404-no"><?php _e('No'); ?></label>
</div><!-- end dynwid_conf -->
