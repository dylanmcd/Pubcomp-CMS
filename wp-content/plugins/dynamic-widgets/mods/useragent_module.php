<?php
/**
 * UserAgent Module
 *
 * @version $Id: useragent_module.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

	$useragents = array(
									'gecko'  => 'Firefox' . ' ' . __('(and other Gecko based)', DW_L10N_DOMAIN),
									'msie'   => 'Internet Explorer',
	'opera'  => 'Opera',
	'ns'     => 'Netscape 4',
	'safari' => 'Safari',
	'chrome' => 'Chrome',
	'undef'  => __('Other / Unknown / Not detected', DW_L10N_DOMAIN)
);
	if ( count($useragents) > DW_LIST_LIMIT ) {
		$browser_condition_select_style = DW_LIST_STYLE;
	}

	$browser_yes_selected = 'checked="checked"';
	$opt_browser = $DW->getOptions($_GET['id'], 'browser');
	if ( count($opt_browser) > 0 ) {
		$browser_act = array();
		foreach ( $opt_browser as $browser_condition ) {
			if ( $browser_condition['name'] == 'default' || empty($browser_condition['name']) ) {
				$browser_default = $browser_condition['value'];
			} else {
				$browser_act[ ] = $browser_condition['name'];
			}
		}

		if ( $browser_default == '0' ) {
			$browser_no_selected = $browser_yes_selected;
			unset($browser_yes_selected);
		}
	}
?>

<h4><b><?php _e('Browser', DW_L10N_DOMAIN); ?></b><?php echo ( count($opt_browser) > 0 ? ' <img src="' . $DW->plugin_url . 'img/checkmark.gif" alt="Checkmark" />' : '' ); ?></h4>
<div class="dynwid_conf">
<?php _e('Show widget with all browsers?', DW_L10N_DOMAIN) ?> <img src="<?php echo $DW->plugin_url; ?>img/info.gif" alt="info" title="<?php _e('Click to toggle info', DW_L10N_DOMAIN) ?>" onclick="divToggle('browser');" /><br />
<?php $DW->dumpOpt($opt_browser); ?>
<div>
	<div id="browser"  class="infotext">
		<?php _e('Browser detection is never 100% accurate.', DW_L10N_DOMAIN); ?>
	</div>
</div>
<input type="radio" name="browser" value="yes" id="browser-yes" <?php echo ( isset($browser_yes_selected) ? $browser_yes_selected : '' ); ?> /> <label for="browser-yes"><?php _e('Yes'); ?></label>
<input type="radio" name="browser" value="no" id="browser-no" <?php echo ( isset($browser_no_selected) ? $browser_no_selected : '' ); ?> /> <label for="browser-no"><?php _e('No'); ?></label><br />
<?php _e('Except the browser(s)', DW_L10N_DOMAIN); ?>:<br />
<div id="browser-select" class="condition-select" <?php echo ( isset($browser_condition_select_style) ? $browser_condition_select_style : '' ); ?>>
<?php foreach ( $useragents as $code => $agent ) { ?>
	<input type="checkbox" id="browser_act_<?php echo $code; ?>" name="browser_act[]" value="<?php echo $code; ?>" <?php echo ( count($browser_act) > 0 && in_array($code, $browser_act) ) ? 'checked="checked"' : ''; ?> /> <label for="browser_act_<?php echo $code; ?>"><?php echo $agent; ?></label><br />
<?php } ?>
</div>
</div><!-- end dynwid_conf -->