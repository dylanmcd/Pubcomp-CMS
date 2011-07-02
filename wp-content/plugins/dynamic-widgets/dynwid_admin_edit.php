<?php
/**
 * dynwid_admin_edit.php - Options settings
 *
 * @version $Id: dynwid_admin_edit.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

	// WPML Plugin support
	if ( defined('ICL_PLUGIN_PATH') && file_exists(ICL_PLUGIN_PATH . DW_WPML_API) ) {
		$DW->wpml = TRUE;
		$wpml_icon = '<img src="' . $DW->plugin_url . DW_WPML_ICON . '" alt="WMPL" title="Dynamic Widgets syncs with other languages of these pages via WPML" style="position:relative;top:2px;" />';
	}
?>

<style type="text/css">
label {
  cursor : default;
}

.condition-select {
  width : 300px;
  -moz-border-radius-topleft : 6px;
  -moz-border-radius-topright : 6px;
  -moz-border-radius-bottomleft : 6px;
  -moz-border-radius-bottomright : 6px;
  border-style : solid;
  border-width : 1px;
  border-color : #E3E3E3;
  padding : 5px;
}

.infotext {
  width : 98%;
  display : none;
  color : #666666;
  font-style : italic;
}

h4 {
	text-indent : 30px;
}

.hasoptions {
	color : #ff0000;
}

#dynwid {
	font-family : 'Lucida Grande', Verdana, Arial, 'Bitstream Vera Sans', sans-serif;
	font-size : 13px;
}

.ui-datepicker {
	font-size : 10px;
}
</style>

<?php if ( isset($_POST['dynwid_save']) && $_POST['dynwid_save'] == 'yes' ) { ?>
<div class="updated fade" id="message">
  <p>
    <strong><?php _e('Widget options saved.', DW_L10N_DOMAIN); ?></strong> <a href="themes.php?page=dynwid-config"><?php _e('Return', DW_L10N_DOMAIN); ?></a> <?php _e('to Dynamic Widgets overview', DW_L10N_DOMAIN); ?>.
  </p>
</div>
<?php } else if ( isset($_GET['work']) && $_GET['work'] == 'none' ) { ?>
<div class="error" id="message">
  <p><?php echo __('Dynamic does not mean static hiding of a widget.', DW_L10N_DOMAIN) . ' ' . __('Hint', DW_L10N_DOMAIN) . ': '; ?><a href="widgets.php"><?php _e('Remove', DW_L10N_DOMAIN); ?></a> <?php _e('the widget from the sidebar', DW_L10N_DOMAIN); ?>.</p>
</div>
<?php } else if ( isset($_GET['work']) && $_GET['work'] == 'nonedate' ) { ?>
<div class="error" id="message">
  <p><?php _e('The From date can\'t be later than the To date.', DW_L10N_DOMAIN); ?></p>
</div>

<?php } ?>

<h3><?php _e('Edit options for the widget', DW_L10N_DOMAIN); ?>: <em><?php echo $DW->getName($_GET['id']); ?></em></h3>
<?php echo ( DW_DEBUG ) ? '<pre>ID = ' . $_GET['id'] . '</pre><br />' : ''; ?>

<form action="<?php echo trailingslashit(admin_url()) . 'themes.php?page=dynwid-config&action=edit&id=' . $_GET['id']; ?>" method="post">
<?php wp_nonce_field('plugin-name-action_edit_' . $_GET['id']); ?>
<input type="hidden" name="dynwid_save" value="yes" />
<input type="hidden" name="widget_id" value="<?php echo $_GET['id']; ?>" />
<input type="hidden" name="returnurl" value="<?php echo ( isset($_GET['returnurl']) ? urldecode($_GET['returnurl']) : '' ); ?>" />

<div id="dynwid">
<?php
	$modules = array(
								'role' => 'Role',
								'date' => 'Date',
								'wpml' => 'Language (WPML)',
								'useragent' => 'UserAgent',
								'frontpage' => 'Front Page',
								'single' => 'Single Posts',
								'attachment' => 'Attachment',
								'pages' => 'Pages',
								'author' => 'Author Pages',
								'category' => 'Category Pages',
								'archive' => 'Archive Pages',
								'error' => 'Error Page',
								'search' => 'Search Page',
								'custompost' => 'Custom Post Types',
								'wpec' => 'WPSC Category',
								'bp' => 'BuddyPress'
							);
	foreach ( $modules as $key => $mod ) {
		$modfile = DW_MODULES . $key . '_module.php';
		echo '<!-- ' . $mod . ' //-->';
		if ( file_exists($modfile) ) {
			include($modfile);
		}
	}
?>

</div><!-- end dynwid -->

<br />
<div style="float:left">
<input class="button-primary" type="submit" value="<?php _e('Save'); ?>" /> &nbsp;&nbsp;
</div>
<?php $url = (! empty($_GET['returnurl']) ? urldecode($_GET['returnurl']) : trailingslashit(admin_url()) ) . 'themes.php?page=dynwid-config'; ?>
<div style="float:left">
<input class="button-secondary" type="button" value="<?php _e('Return', DW_L10N_DOMAIN); ?>" onclick="location.href='<?php echo $url; ?>'" />
</div>

</form>

<script type="text/javascript">
/* <![CDATA[ */
  function chkInPosts() {
    var posts = <?php echo count($single_post_act); ?>;
    var tags = <?php echo count($single_tag_act); ?>;

    if ( (posts > 0 || tags > 0) && jQuery('#individual').attr('checked') == false ) {
      if ( confirm('Are you sure you want to disable the exception rule for individual posts and tags?\nThis will remove the options set to individual posts and/or tags for this widget.\nOk = Yes; No = Cancel') ) {
        swChb(cAuthors, false);
        swChb(cCat, false);
      } else {
        jQuery('#individual').attr('checked', true);
      }
    } else if ( icount > 0 && jQuery('#individual').attr('checked') ) {
      if ( confirm('Are you sure you want to enable the exception rule for individual posts and tags?\nThis will remove the exceptions set for Author and/or Category on single posts for this widget.\nOk = Yes; No = Cancel') ) {
        swChb(cAuthors, true);
        swChb(cCat, true);
        icount = 0;
      } else {
        jQuery('#individual').attr('checked', false);
      }
    } else if ( jQuery('#individual').attr('checked') ) {
        swChb(cAuthors, true);
        swChb(cCat, true);
    } else {
        swChb(cAuthors, false);
        swChb(cCat, false);
    }
  }

  function chkChild(pid) {
  	if ( jQuery('#page_act_'+pid).attr('checked') == false ) {
  		jQuery('#child_'+pid).attr('checked', false);
  	}
  }

  function chkParent(pid) {
  	if ( jQuery('#child_'+pid).attr('checked') == true ) {
  		jQuery('#page_act_'+pid).attr('checked', true);
  	}
  }

  function chkCPChild(type, pid) {
  	if ( jQuery('#'+type+'_act_'+pid).attr('checked') == false ) {
  		jQuery('#'+type+'_child_'+pid).attr('checked', false);
  	}
  }

  function chkCPParent(type, pid) {
  	if ( jQuery('#'+type+'_child_'+pid).attr('checked') == true ) {
  		jQuery('#'+type+'_act_'+pid).attr('checked', true);
  	}
  }

  function ci(id) {
    if ( jQuery('#'+id).attr('checked') ) {
      icount++;
    } else {
      icount--;
    }
  }

  function divToggle(div) {
    div = '#'+div;
    jQuery(div).slideToggle(400);
  }

  function showCalendar(id) {
    if ( document.getElementById('date-no').checked ) {
      var id = '#'+id;
      jQuery(function() {
  		  jQuery(id).datepicker({
  		    dateFormat: 'yy-mm-dd',
  		    minDate: new Date(<?php echo date('Y, n - 1, j'); ?>),
  		    onClose: function() {
  		    	jQuery(id).datepicker('destroy');
  		    }
  		  });
        jQuery(id).datepicker('show');
    	});
    } else {
      jQuery('#date-no').attr('checked', true);
      swTxt(cDate, false);
      showCalendar(id);
    }
  }

  function swChb(c, s) {
  	for ( i = 0; i < c.length; i++ ) {
  	  if ( s == true ) {
  	    jQuery('#'+c[i]).attr('checked', false);
  	  }
      jQuery('#'+c[i]).attr('disabled', s);
    }
  }

  function swTxt(c, s) {
  	for ( i = 0; i < c.length; i++ ) {
  	  if ( s == true ) {
  	    jQuery('#'+c[i]).val('');
  	  }
      jQuery('#'+c[i]).attr('disabled', s);
    }
  }

  var cAuthors = new Array(<?php echo implode(', ', $js_author_array); ?>);
  var cCat = new Array(<?php echo implode(', ', $js_category_array); ?>);
  var cRole = new Array(<?php echo implode(', ' , $jsroles); ?>);
  var cDate =  new Array('date_start', 'date_end');
  var icount = <?php echo $js_count; ?>;

  if ( jQuery('#role-yes').attr('checked') ) {
  	swChb(cRole, true);
  }
  if ( jQuery('#date-yes').attr('checked') ) {
  	swTxt(cDate, true);
  }
  if ( jQuery('#individual').attr('checked') ) {
    swChb(cAuthors, true);
    swChb(cCat, true);
  }

  jQuery(document).ready(function() {
		jQuery('#dynwid').accordion({
			header: 'h4',
			autoHeight: false,
		});
	});
/* ]]> */
</script>