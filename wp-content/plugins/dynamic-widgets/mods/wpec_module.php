<?php
/**
 *	WPEC Module
 *  http://getshopped.org/
 *
 * @version $Id: wpec_module.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

	if ( defined('WPSC_VERSION') ) {
		$DW->wpsc = TRUE;
		require_once(DW_PLUGIN . 'wpsc.php');

		$wpsc_yes_selected = 'checked="checked"';
		$opt_wpsc = $DW->getOptions($_GET['id'], 'wpsc');
		if ( count($opt_wpsc) > 0 ) {
			$wpsc_act = array();
			foreach ( $opt_wpsc as $wpsc_condition ) {
				if ( $wpsc_condition['name'] == 'default' || empty($wpsc_condition['name']) ) {
					$wpsc_default = $wpsc_condition['value'];
				} else {
					$wpsc_act[ ] = $wpsc_condition['name'];
				}
			}

			if ( $wpsc_default == '0' ) {
				$wpsc_no_selected = $wpsc_yes_selected;
				unset($wpsc_yes_selected);
			}
		}

		$wpsc = dw_wpsc_get_categories();
		if ( count($wpsc) > DW_LIST_LIMIT ) {
			$wpsc_condition_select_style = DW_LIST_STYLE;
		}
?>
<h4><b><?php _e('WPSC Category', DW_L10N_DOMAIN); ?></b><?php echo ( count($opt_wpsc) > 0 ? ' <img src="' . $DW->plugin_url . 'img/checkmark.gif" alt="Checkmark" />' : '' ); ?></h4>
<div class="dynwid_conf">
<?php _e('Show widget default on WPSC categories?', DW_L10N_DOMAIN); ?><br />
<?php $DW->dumpOpt($opt_wpsc); ?>
<input type="radio" name="wpsc" value="yes" id="wpsc-yes" <?php echo ( isset($wpsc_yes_selected) ? $wpsc_yes_selected : '' ); ?> /> <label for="wpsc-yes"><?php _e('Yes'); ?></label>
<input type="radio" name="wpsc" value="no" id="wpsc-no" <?php echo ( isset($wpsc_no_selected) ? $wpsc_no_selected : '' ); ?> /> <label for="wpsc-no"><?php _e('No'); ?></label><br />
<?php _e('Except the categories', DW_L10N_DOMAIN); ?>:<br />
<div id="wpsc-select" class="condition-select" <?php echo ( isset($wpsc_condition_select_style) ? $wpsc_condition_select_style : '' ); ?>>
<?php foreach ( $wpsc as $id => $cat ) { ?>
<input type="checkbox" id="wpsc_act_<?php echo $id; ?>" name="wpsc_act[]" value="<?php echo $id; ?>" <?php echo ( count($wpsc_act) > 0 && in_array($id, $wpsc_act) ) ? 'checked="checked"' : ''; ?> /> <label for="wpsc_act_<?php echo $id; ?>"><?php echo $cat; ?></label><br />
<?php } ?>
</div>
</div><!-- end dynwid_conf -->
<?php
	} // end DW->wpsc
?>