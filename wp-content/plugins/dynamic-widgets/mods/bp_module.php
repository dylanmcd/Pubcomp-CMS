<?php
/**
 * BP module
 * http://buddypress.org/
 *
 * @version $Id: bp_module.php 348295 2011-02-20 20:13:21Z qurl $\
 * @copyright 2011 Jacco Drabbe
 */

	if ( defined('BP_VERSION') ) {
		$DW->bp = TRUE;
		require_once(DW_PLUGIN . 'bp.php');

		$tmp = array();
		$bp_yes_selected = 'checked="checked"';
		$opt_bp = $DW->getOptions($_GET['id'], 'bp');
		if ( count($opt_bp) > 0 ) {
			$bp_act = array();
			foreach ( $opt_bp as $key => $bp_condition ) {
				if ( $bp_condition['maintype'] == 'bp' ) {
					if ( $bp_condition['name'] == 'default' || empty($bp_condition['name']) ) {
						$bp_default = $bp_condition['value'];
					} else {
						$bp_act[ ] = $bp_condition['name'];
					}
				} else {
					$tmp[ ] = $key;
				}
			}

			if ( $bp_default == '0' ) {
				$bp_no_selected = $bp_yes_selected;
				unset($bp_yes_selected);
			}

			// Removing the bp-group options
			if ( count($tmp) > 0 ) {
				foreach ( $tmp as $key ){
					unset($opt_bp[$key]);
				}
			}
		}

		$bp_components = dw_get_bp_components();
		if ( count($bp_components) > DW_LIST_LIMIT ) {
			$bp_condition_select_style = DW_LIST_STYLE;
		}
		unset($tmp);
?>

<h4><b><?php _e('BuddyPress', DW_L10N_DOMAIN); ?></b><?php echo ( count($opt_bp) > 0 ? ' <img src="' . $DW->plugin_url . 'img/checkmark.gif" alt="Checkmark" />' : '' ); ?></h4>
<div class="dynwid_conf">
<?php _e('Show widget default on BuddyPress pages?', DW_L10N_DOMAIN); ?><br />
<?php $DW->dumpOpt($opt_bp); ?>
<input type="radio" name="bp" value="yes" id="bp-yes" <?php echo ( isset($bp_yes_selected) ? $bp_yes_selected : '' ); ?> /> <label for="bp-yes"><?php _e('Yes'); ?></label>
<input type="radio" name="bp" value="no" id="bp-no" <?php echo ( isset($bp_no_selected) ? $bp_no_selected : '' ); ?> /> <label for="bp-no"><?php _e('No'); ?></label><br />
<?php _e('Except on the compontents pages', DW_L10N_DOMAIN); ?>:<br />
<div id="bp-select" class="condition-select" <?php echo ( isset($bp_condition_select_style) ? $bp_condition_select_style : '' ); ?>>
<?php foreach ( $bp_components as $id => $component ) { ?>
<input type="checkbox" id="bp_act_<?php echo $id; ?>" name="bp_act[]" value="<?php echo $id; ?>" <?php echo ( count($bp_act) > 0 && in_array($id, $bp_act) ) ? 'checked="checked"' : ''; ?> /> <label for="bp_act_<?php echo $id; ?>"><?php echo $component; ?></label><br />
<?php } ?>
</div>
</div><!-- end dynwid_conf -->

<!-- BuddyPress Groups //-->
<?php
		if ( $DW->bp_groups ) {
			$bp_group_yes_selected = 'checked="checked"';
			$opt_bp_group = $DW->getOptions($_GET['id'], 'bp-group');
			if ( count($opt_bp_group) > 0 ) {
				$bp_group_act = array();
				foreach ( $opt_bp_group as $bp_group_condition ) {
					if ( $bp_group_condition['name'] == 'default' || empty($bp_group_condition['name']) ) {
						$bp_group_default = $bp_group_condition['value'];
					} else {
						$bp_group_act[ ] = $bp_group_condition['name'];
					}
				}

				if ( $bp_group_default == '0' ) {
					$bp_group_no_selected = $bp_group_yes_selected;
					unset($bp_group_yes_selected);
				}
			}

			$bp_groups = dw_get_bp_groups();
			if ( count($bp_groups) > DW_LIST_LIMIT ) {
				$bp_group_condition_select_style = DW_LIST_STYLE;
			}
?>
<h4><b><?php _e('BuddyPress Groups', DW_L10N_DOMAIN); ?></b><?php echo ( count($opt_bp_group) > 0 ? ' <span class="hasoptions">*</span>' : '' ); ?></h4>
<div class="dynwid_conf">
<?php _e('Show widget default on BuddyPress Group pages?', DW_L10N_DOMAIN); ?><br />
<?php $DW->dumpOpt($opt_bp_group); ?>
<input type="radio" name="bp-group" value="yes" id="bp-group-yes" <?php echo ( isset($bp_group_yes_selected) ? $bp_group_yes_selected : '' ); ?> /> <label for="bp-group-yes"><?php _e('Yes'); ?></label>
<input type="radio" name="bp-group" value="no" id="bp-group-no" <?php echo ( isset($bp_group_no_selected) ? $bp_group_no_selected : '' ); ?> /> <label for="bp-group-no"><?php _e('No'); ?></label><br />
<?php _e('Except in the groups', DW_L10N_DOMAIN); ?>:<br />
<div id="bp-group-select" class="condition-select" <?php echo ( isset($bp_group_condition_select_style) ? $bp_group_condition_select_style : '' ); ?>>
<?php foreach ( $bp_groups as $id => $group ) { ?>
<input type="checkbox" id="bp_group_act_<?php echo $id; ?>" name="bp_group_act[]" value="<?php echo $id; ?>" <?php echo ( count($bp_group_act) > 0 && in_array($id, $bp_group_act) ) ? 'checked="checked"' : ''; ?> /> <label for="bp_group_act_<?php echo $id; ?>"><?php echo ucfirst($group); ?></label><br />
<?php	 } ?>
</div>
</div><!-- end dynwid_conf -->

<?php
		}	// end $DW->bp_groups
	} // end $DW->bp;
?>