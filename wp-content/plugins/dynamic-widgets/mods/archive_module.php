<?php
/**
 * Archive Module
 *
 * @version $Id: archive_module.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

	$archive_yes_selected = 'checked="checked"';
	$opt_archive = $DW->getOptions($_GET['id'], 'archive');
	if ( count($opt_archive) > 0 ) {
		$archive_condition = $opt_archive[0]['value'];
		if ( $archive_condition == '0' ) {
			$archive_no_selected = $archive_yes_selected;
			unset($archive_yes_selected);
		}
	}
?>

<h4><b><?php _e('Archive Pages', DW_L10N_DOMAIN); ?></b><?php echo ( count($opt_archive) > 0 ? ' <img src="' . $DW->plugin_url . 'img/checkmark.gif" alt="Checkmark" />' : '' ); ?></h4>
<div class="dynwid_conf">
<?php _e('Show widget on archive pages', DW_L10N_DOMAIN); ?> <img src="<?php echo $DW->plugin_url; ?>img/info.gif" alt="info" title="<?php _e('Click to toggle info', DW_L10N_DOMAIN); ?>" onclick="divToggle('archive')" /><br />
<?php $DW->dumpOpt($opt_archive); ?>
<div>
<div id="archive" class="infotext">
  <?php _e('This option does not include Author and Category Pages.', DW_L10N_DOMAIN); ?>
</div>
</div>
<input type="radio" name="archive" value="yes" id="archive-yes" <?php echo ( isset($archive_yes_selected) ? $archive_yes_selected : '' ); ?> /> <label for="archive-yes"><?php _e('Yes'); ?></label>
<input type="radio" name="archive" value="no" id="archive-no" <?php echo ( isset($archive_no_selected) ? $archive_no_selected : '' ); ?> /> <label for="archive-no"><?php _e('No'); ?></label>
</div><!-- end dynwid_conf -->
