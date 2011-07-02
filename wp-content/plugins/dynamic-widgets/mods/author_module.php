<?php
/**
 * Author Module
 *
 * @version $Id: author_module.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

	$author_yes_selected = 'checked="checked"';
	$opt_author = $DW->getOptions($_GET['id'], 'author');
	if ( count($opt_author) > 0 ) {
		$author_act = array();
		foreach ( $opt_author as $author_condition ) {
			if ( $author_condition['name'] == 'default' || empty($author_condition['name']) ) {
				$author_default = $author_condition['value'];
			} else {
				$author_act[ ] = $author_condition['name'];
			}
		}

		if ( $author_default == '0' ) {
			$author_no_selected = $author_yes_selected;
			unset($author_yes_selected);
		}
	}

	$authors = get_users_of_blog();
	if ( count($authors) > DW_LIST_LIMIT ) {
		$author_condition_select_style = DW_LIST_STYLE;
	}
?>

<h4><b><?php _e('Author Pages', DW_L10N_DOMAIN); ?></b><?php echo ( count($opt_author) > 0 ? ' <img src="' . $DW->plugin_url . 'img/checkmark.gif" alt="Checkmark" />' : '' ); ?></h4>
<div class="dynwid_conf">
<?php _e('Show widget default on author pages?', DW_L10N_DOMAIN); ?><br />
<?php $DW->dumpOpt($opt_author); ?>
<input type="radio" name="author" value="yes" id="author-yes" <?php echo ( isset($author_yes_selected) ? $author_yes_selected : '' ); ?> /> <label for="author-yes"><?php _e('Yes'); ?></label>
<input type="radio" name="author" value="no" id="author-no" <?php echo ( isset($author_no_selected) ? $author_no_selected : '' ); ?> /> <label for="author-no"><?php _e('No'); ?></label><br />
<?php _e('Except the author(s)', DW_L10N_DOMAIN); ?>:<br />
<div id="author-select" class="condition-select" <?php echo ( isset($author_condition_select_style) ? $author_condition_select_style : '' ); ?>>
<?php foreach ( $authors as $author ) { ?>
<input type="checkbox" id="author_act_<?php echo $author->ID; ?>" name="author_act[]" value="<?php echo $author->ID; ?>" <?php echo ( isset($author_act) && count($author_act) > 0 && in_array($author->ID,$author_act) ) ? 'checked="checked"' : ''; ?> /> <label for="author_act_<?php echo $author->ID; ?>"><?php echo $author->display_name; ?></label><br />
<?php } ?></div>
</div><!-- end dynwid_conf -->
