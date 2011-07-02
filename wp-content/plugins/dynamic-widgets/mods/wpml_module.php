<?php
/**
 * WPML Module
 *
 * @version $Id: wpml_module.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

	if ( $DW->wpml ) {
		$wpml_api = ICL_PLUGIN_PATH . DW_WPML_API;
		require_once($wpml_api);

		$wpml_yes_selected = 'checked="checked"';
		$opt_wpml = $DW->getOptions($_GET['id'], 'wpml');
		if ( count($opt_wpml) > 0 ) {
			$wpml_act = array();
			foreach ( $opt_wpml as $wpml_condition ) {
				if ( $wpml_condition['name'] == 'default' || empty($wpml_condition['name']) ) {
					$wpml_default = $wpml_condition['value'];
				} else {
					$wpml_act[ ] = $wpml_condition['name'];
				}
			}

			if ( $wpml_default == '0' ) {
				$wpml_no_selected = $wpml_yes_selected;
				unset($wpml_yes_selected);
			}
		}

		$wpml_langs = wpml_get_active_languages();
		if ( count($wpml_langs) > DW_LIST_LIMIT ) {
			$wpml_condition_select_style = DW_LIST_STYLE;
		}
?>

<h4><b><?php _e('Language (WPML)', DW_L10N_DOMAIN); ?></b><?php echo ( count($opt_wpml) > 0 ? ' <img src="' . $DW->plugin_url . 'img/checkmark.gif" alt="Checkmark" />' : '' ); ?></h4>
<div class="dynwid_conf">
<?php _e('Show widget default on all languages?', DW_L10N_DOMAIN); ?> <img src="<?php echo $DW->plugin_url; ?>img/info.gif" alt="info" title="<?php _e('Click to toggle info', DW_L10N_DOMAIN) ?>" onclick="divToggle('wpml');" /><br /><br />
<?php $DW->dumpOpt($opt_wpml); ?>
<div>
	<div id="wpml" class="infotext">
	<?php _e('Using this option can override all other options.', DW_L10N_DOMAIN); ?><br />
	</div>
</div>
<input type="radio" name="wpml" value="yes" id="wpml-yes" <?php echo ( isset($wpml_yes_selected) ? $wpml_yes_selected : '' ); ?> /> <label for="wpml-yes"><?php _e('Yes'); ?></label>
<input type="radio" name="wpml" value="no" id="wpml-no" <?php echo ( isset($wpml_no_selected) ? $wpml_no_selected : '' ); ?> /> <label for="wpml-no"><?php _e('No'); ?></label><br />
<?php _e('Except the languages', DW_L10N_DOMAIN); ?>:<br />
<div id="wpml-select" class="condition-select" <?php echo ( isset($wpml_condition_select_style) ? $wpml_condition_select_style : '' ); ?>>
<?php		foreach ( $wpml_langs as $code => $lang ) { ?>
<input type="checkbox" id="wpml_act_<?php echo $lang['code']; ?>" name="wpml_act[]" value="<?php echo $lang['code']; ?>" <?php echo ( count($wpml_act) > 0 && in_array($lang['code'], $wpml_act) ) ? 'checked="checked"' : ''; ?> /> <label for="wpml_act_<?php echo $lang['code']; ?>"><?php echo $lang['display_name']; ?></label><br />
<?php 	} ?>
</div>
</div><!-- end dynwid_conf -->
<?php } ?>