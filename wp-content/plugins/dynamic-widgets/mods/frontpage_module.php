<?php
/**
 * Front Page Module
 *
 * @version $Id: frontpage_module.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

	if ( get_option('show_on_front') != 'page' ) {
		$frontpage_yes_selected = 'checked="checked"';
		$opt_frontpage = $DW->getOptions($_GET['id'], 'front-page');
		if ( count($opt_frontpage) > 0 ) {
			$frontpage_condition = $opt_frontpage[0]['value'];
			if ( $frontpage_condition == '0' ) {
				$frontpage_no_selected = $frontpage_yes_selected;
				unset($frontpage_yes_selected);
			}
		}
?>
<h4><b><?php _e('Front Page', DW_L10N_DOMAIN); ?></b><?php echo ( count($opt_frontpage) > 0 ? ' <img src="' . $DW->plugin_url . 'img/checkmark.gif" alt="Checkmark" />' : '' ); ?></h4>
<div class="dynwid_conf">
<?php _e('Show widget on the front page?', DW_L10N_DOMAIN) ?> <img src="<?php echo $DW->plugin_url; ?>img/info.gif" alt="info" onclick="divToggle('frontpage');" /><br />
<?php $DW->dumpOpt($opt_frontpage); ?>
<div>
	<div id="frontpage"  class="infotext">
	<?php _e('This option only applies when your front page is set to display your latest posts (See Settings &gt; Reading).<br />
						When a static page is set, you can use the options for the static pages below.
					', DW_L10N_DOMAIN); ?>
	</div>
</div>
<input type="radio" name="front-page" value="yes" id="front-page-yes" <?php echo ( isset($frontpage_yes_selected) ? $frontpage_yes_selected : '' ); ?> /> <label for="front-page-yes"><?php _e('Yes'); ?></label>
<input type="radio" name="front-page" value="no" id="front-page-no" <?php echo ( isset($frontpage_no_selected) ? $frontpage_no_selected : '' ); ?> /> <label for="front-page-no"><?php _e('No'); ?></label>
</div><!-- end dynwid_conf -->
<?php } ?>
