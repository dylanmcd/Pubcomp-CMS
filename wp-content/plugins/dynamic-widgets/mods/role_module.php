<?php
/**
 * Role Module
 *
 * @version $Id: role_module.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

	$wp_roles = $GLOBALS['wp_roles'];
	$roles = array_merge($wp_roles->role_names, array('anonymous' => __('Anonymous') . '|User role'));
	$jsroles = array();
	foreach ( $roles as $rid => $role ) {
		$roles[esc_attr($rid)] = translate_user_role($role);
		$jsroles[ ] = '\'role_act_' . esc_attr($rid) . '\'';    // Prep for JS Array
	}
	if ( count($roles) > DW_LIST_LIMIT ) {
		$role_condition_select_style = DW_LIST_STYLE;
	}

	$role_yes_selected = 'checked="checked"';
	$opt_role = $DW->getOptions($_GET['id'], 'role');
	if ( count($opt_role) > 0 ) {
		$role_act = array();
		foreach ( $opt_role as $role_condition ) {
			if ( $role_condition['name'] == 'default' || empty($role_condition['name']) ) {
				$role_default = $role_condition['value'];
			} else {
				$role_act[ ] = $role_condition['name'];
			}
		}

		if ( $role_default == '0' ) {
			$role_no_selected = $role_yes_selected;
			unset($role_yes_selected);
		}
	}
?>

<h4><b><?php _e('Role'); ?></b><?php echo ( count($opt_role) > 0 ? ' <img src="' . $DW->plugin_url . 'img/checkmark.gif" alt="Checkmark" />' : '' ); ?></h4>
<div class="dynwid_conf">
<?php _e('Show widget to everybody?', DW_L10N_DOMAIN); ?> <img src="<?php echo $DW->plugin_url; ?>img/info.gif" alt="info" title="<?php _e('Click to toggle info', DW_L10N_DOMAIN) ?>" onclick="divToggle('role');" /><br />
<?php $DW->dumpOpt($opt_role); ?>
<div>
	<div id="role" class="infotext">
  <?php _e('Setting options by role is very powerfull. It can override all other options!<br />
						Users who are not logged in, get the <em>Anonymous</em> role.', DW_L10N_DOMAIN); ?>
	</div>
</div>
<input type="radio" name="role" value="yes" id="role-yes" <?php echo ( isset($role_yes_selected) ? $role_yes_selected : '' ); ?> onclick="swChb(cRole, true);" /> <label for="role-yes"><?php _e('Yes'); ?></label>
<input type="radio" name="role" value="no" id="role-no" <?php echo ( isset($role_no_selected) ? $role_no_selected : '' ); ?> onclick="swChb(cRole, false)" /> <label for="role-no"><?php _e('No'); ?>, <?php _e('only to', DW_L10N_DOMAIN); ?>:</label><br />
<div id="role-select" class="condition-select" <?php echo ( isset($role_condition_select_style) ? $role_condition_select_style : '' ); ?>>
<?php foreach ( $roles as $rid => $role ) { ?>
<input type="checkbox" id="role_act_<?php echo $rid; ?>" name="role_act[]" value="<?php echo $rid; ?>" <?php echo ( isset($role_act) && count($role_act) > 0 && in_array($rid, $role_act) ) ? 'checked="checked"' : ''; ?> /> <label for="role_act_<?php echo $rid; ?>"><?php echo $role; ?></label><br />
<?php } ?>
</div>
</div><!-- end dynwid_conf -->