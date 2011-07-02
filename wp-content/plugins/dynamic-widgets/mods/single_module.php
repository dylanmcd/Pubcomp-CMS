<?php
/**
 * Single Post Module
 *
 * @version $Id: single_module.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

	$single_yes_selected = 'checked="checked"';
	$single_condition = '1';
	$opt_single = $DW->getOptions($_GET['id'], 'single');
	if ( count($opt_single) > 0 ) {
		foreach ( $opt_single as $widget ) {
			if ( $widget['maintype'] == 'single' ) {
				$single_condition = $widget['value'];
			}
		}
		if ( $single_condition == '0' ) {
			$single_no_selected = $single_yes_selected;
			unset($single_yes_selected);
		}
	}

	// -- Author
	$authors = get_users_of_blog();
	if ( count($authors) > DW_LIST_LIMIT ) {
    $author_condition_select_style = DW_LIST_STYLE;
  }

	$js_count = 0;
	$opt_single_author = $DW->getOptions($_GET['id'], 'single-author');
	$js_author_array = array();
	if ( count($opt_single_author) > 0 ) {
		$js_count = $js_count + count($opt_single_author) - 1;
		$single_author_act = array();
		foreach ( $opt_single_author as $single_author_condition ) {
			$single_author_act[ ] = $single_author_condition['name'];
		}
	}

	// -- Category
	$category = get_categories( array('hide_empty' => FALSE) );
	if ( count($category) > DW_LIST_LIMIT ) {
    $category_condition_select_style = DW_LIST_STYLE;
  }

	$opt_single_category = $DW->getOptions($_GET['id'], 'single-category');
	$js_category_array = array();
	if ( count($opt_single_category) > 0 ) {
		$js_count = $js_count + count($opt_single_category) - 1;
		$single_category_act = array();
		foreach ( $opt_single_category as $single_category_condition ) {
			$single_category_act[ ] = $single_category_condition['name'];
		}
	}

	// -- Individual / Posts / Tags
	$individual = FALSE;
	$opt_individual = $DW->getOptions($_GET['id'], 'individual');
	$single_post_act = array();
	$single_tag_act = array();

	if ( count($opt_individual) > 0 ) {
		$individual_condition = $opt_individual[0]['value'];
		if ( $individual_condition == 1 ) {
			$individual = TRUE;

			$opt_single_post = $DW->getOptions($_GET['id'], 'single-post');
			if ( count($opt_single_post) > 0 ) {
				foreach ( $opt_single_post as $single_post_condition ) {
					if ( $single_post_condition['name'] != 'default' ) {
						$single_post_act[ ] = $single_post_condition['name'];
					}
				}
			}

			$opt_single_tag = $DW->getOptions($_GET['id'], 'single-tag');
			if ( count($opt_single_tag) > 0 ) {
				foreach ( $opt_single_tag as $single_tag_condition ) {
					if ( $single_tag_condition['name'] != 'default' ) {
						$single_tag_act[ ] = $single_tag_condition['name'];
					}
				}
			}
			$count_individual = '(' . __('Posts: ', DW_L10N_DOMAIN) . count($single_post_act) . ', ' . __('Tags: ', DW_L10N_DOMAIN) . count($single_tag_act) . ')';
		}
	}
?>

<h4><b><?php _e('Single Posts', DW_L10N_DOMAIN); ?></b><?php echo ( count($opt_single) > 0 || count($opt_single_author) > 0 || count($opt_single_category) > 0 || count($opt_single_post) > 0 || count($opt_single_tag) > 0 ? ' <img src="' . $DW->plugin_url . 'img/checkmark.gif" alt="Checkmark" />' : '' ); ?></h4>
<div class="dynwid_conf">
<?php _e('Show widget default on single posts?', DW_L10N_DOMAIN) ?> <img src="<?php echo $DW->plugin_url; ?>img/info.gif" alt="info" title="<?php _e('Click to toggle info', DW_L10N_DOMAIN) ?>" onclick="divToggle('single')" /><br />
<?php $DW->dumpOpt($opt_single); ?>
<div>
	<div id="single" class="infotext">
  <?php _e('When you use an author <b>AND</b> a category exception, both rules in the condition must be met. Otherwise the exception rule won\'t be applied.
  					If you want to use the rules in a logical OR condition. Add the same widget again and apply the other rule to that.
  					', DW_L10N_DOMAIN); ?>
	</div>
</div>
<input type="radio" name="single" value="yes" id="single-yes" <?php echo ( isset($single_yes_selected) ? $single_yes_selected : '' ); ?> /> <label for="single-yes"><?php _e('Yes'); ?></label>
<input type="radio" name="single" value="no" id="single-no" <?php echo ( isset($single_no_selected) ? $single_no_selected : '' ); ?> /> <label for="single-no"><?php _e('No'); ?></label><br />
<?php $DW->dumpOpt($opt_individual); ?>
<input type="checkbox" id="individual" name="individual" value="1" <?php echo ( $individual ) ? 'checked="checked"' : ''; ?> onclick="chkInPosts()" />
<label for="individual"><?php _e('Make exception rule available to individual posts and tags.', DW_L10N_DOMAIN) ?> <?php echo ( isset($count_individual) ? $count_individual : '' ); ?></label>
<img src="<?php echo $DW->plugin_url; ?>img/info.gif" alt="info" title="<?php _e('Click to toggle info', DW_L10N_DOMAIN) ?>" onclick="divToggle('individual_post_tag')" />
<div>
	<div id="individual_post_tag" class="infotext">
  <?php _e('When you enable this option, you have the ability to apply the exception rule for <em>Single Posts</em> to tags and individual posts.
						You can set the exception rule for tags in the single Edit Tag Panel (go to <a href="edit-tags.php?taxonomy=post_tag">Post Tags</a>,
						click a tag), For individual posts in the <a href="post-new.php">New</a> or <a href="edit.php">Edit</a> Posts panel.
						Exception rules for tags and individual posts in any combination work independantly, but will always be counted as one exception.<br />
  					Please note when exception rules are set for Author and/or Category, these will be removed.
  				', DW_L10N_DOMAIN); ?>
	</div>
</div>
<?php foreach ( $single_post_act as $singlepost ) { ?>
<input type="hidden" name="single_post_act[]" value="<?php echo $singlepost; ?>" />
<?php } ?>
<?php foreach ( $single_tag_act as $tag ) { ?>
<input type="hidden" name="single_tag_act[]" value="<?php echo $tag; ?>" />
<?php } ?>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
  <td valign="top">
    <?php _e('Except the posts by author', DW_L10N_DOMAIN); ?>:
    <?php $DW->dumpOpt($opt_single_author); ?>
    <div id="single-author-select" class="condition-select" <?php echo ( isset($author_condition_select_style) ? $author_condition_select_style : '' ); ?>>
    <?php foreach ( $authors as $author ) { ?>
    <?php $js_author_array[ ] = '\'single_author_act_' . $author->ID . '\''; ?>
    <input type="checkbox" id="single_author_act_<?php echo $author->ID; ?>" name="single_author_act[]" value="<?php echo $author->ID; ?>" <?php echo ( isset($single_author_act) && count($single_author_act) > 0 && in_array($author->ID,$single_author_act) ) ? 'checked="checked"' : '';  ?> onclick="ci('single_author_act_<?php echo $author->ID; ?>')" /> <label for="single_author_act_<?php echo $author->ID; ?>"><?php echo $author->display_name; ?></label><br />
    <?php } ?>
    </div>
  </td>
  <td style="width:10px"></td>
  <td valign="top">
    <?php _e('Except the posts in category', DW_L10N_DOMAIN); ?>: <?php echo ( $DW->wpml ? $wpml_icon : '' ); ?>
    <?php $DW->dumpOpt($opt_single_category); ?>
    <div id="single-category-select" class="condition-select" <?php echo ( isset($category_condition_select_style) ? $category_condition_select_style : '' ); ?>>
    <?php foreach ( $category as $cat ) { ?>
    <?php $js_category_array[ ] = '\'single_cat_act_' . $cat->cat_ID . '\''; ?>
    <input type="checkbox" id="single_cat_act_<?php echo $cat->cat_ID; ?>" name="single_category_act[]" value="<?php echo $cat->cat_ID; ?>" <?php echo ( isset($single_category_act) && count($single_category_act) > 0 && in_array($cat->cat_ID,$single_category_act) ) ? 'checked="checked"' : ''; ?> onclick="ci('single_cat_act_<?php echo $cat->cat_ID; ?>')" /> <label for="single_cat_act_<?php echo $cat->cat_ID; ?>"><?php echo $cat->name; ?></label><br />
    <?php } ?>
    </div>
  </td>
</tr>
</table>
</div><!-- end dynwid_conf -->
