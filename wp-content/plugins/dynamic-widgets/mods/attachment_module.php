<?php
/**
 * Attachment Module
 *
 * @version $Id: attachment_module.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

	$attachment_yes_selected = 'checked="checked"';
	$opt_attachment = $DW->getOptions($_GET['id'], 'attachment');
	if ( count($opt_attachment) > 0 ) {
		$attachment_condition = $opt_attachment[0]['value'];
		if ( $attachment_condition == '0' ) {
			$attachment_no_selected = $attachment_yes_selected;
			unset($attachment_yes_selected);
		}
	}
?>

<h4><b><?php _e('Attachments', DW_L10N_DOMAIN); ?></b><?php echo ( count($opt_attachment) > 0 ? ' <img src="' . $DW->plugin_url . 'img/checkmark.gif" alt="Checkmark" />' : '' ); ?></h4>
<div class="dynwid_conf">
<?php _e('Show widget on attachment pages', DW_L10N_DOMAIN); ?>?<br />
<?php $DW->dumpOpt($opt_attachment); ?>
<input type="radio" name="attachment" value="yes" id="attachment-yes" <?php echo ( isset($attachment_yes_selected) ? $attachment_yes_selected : '' ); ?> /> <label for="attachment-yes"><?php _e('Yes'); ?></label>
<input type="radio" name="attachment" value="no" id="attachment-no" <?php echo ( isset($attachment_no_selected) ? $attachment_no_selected : '' ); ?> /> <label for="attachment-no"><?php _e('No'); ?></label>
</div><!-- end dynwid_conf -->
