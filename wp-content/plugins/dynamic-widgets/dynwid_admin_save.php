<?php
/**
 * dynwid_admin_save.php - Saving options to the database
 *
 * @version $Id: dynwid_admin_save.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

  // Security - nonce
  check_admin_referer('plugin-name-action_edit_' . $_POST['widget_id']);

  /* Checking basic stuff */
  // Role
	if ( $_POST['role'] == 'no' && count($_POST['role_act']) == 0 ) {
    wp_redirect( $_SERVER['REQUEST_URI'] . '&work=none' );
    die();
  }
  // Browser
	if ( $_POST['browser'] == 'no' && count($_POST['role_act']) == 0 ) {
		wp_redirect( $_SERVER['REQUEST_URI'] . '&work=none' );
		die();
	}
	// WPML
	if ( isset($_POST['wpml']) && $_POST['wpml'] == 'no' && count($_POST['wpml_act']) == 0 ) {
		wp_redirect( $_SERVER['REQUEST_URI'] . '&work=none' );
		die();
	}

  // Date check
  if ( $_POST['date'] == 'no' ) {
    $date_start = trim($_POST['date_start']);
    $date_end = trim($_POST['date_end']);

    if (! preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $date_start) && ! preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $date_end) ) {
      wp_redirect( $_SERVER['REQUEST_URI'] . '&work=none' );
      die();
    }

    if (! empty($date_start) ) {
      @list($date_start_year, $date_start_month, $date_start_day ) = explode('-', $date_start);
      if (! checkdate($date_start_month, $date_start_day, $date_start_year) ) {
        unset($date_start);
      }
    }
    if (! empty($date_end) ) {
      @list($date_end_year, $date_end_month, $date_end_day ) = explode('-', $date_end);
      if (! checkdate($date_end_month, $date_end_day, $date_end_year) ) {
        unset($date_end);
      }
    }

    if (! empty($date_start) && ! empty($date_end) ) {
      if ( mktime(0, 0, 0, $date_start_month, $date_start_day, $date_start_year) > mktime(0, 0, 0, $date_end_month, $date_end_day, $date_end_year) ) {
        wp_redirect( $_SERVER['REQUEST_URI'] . '&work=nonedate' );
        die();
      }
    }
  }

  // Removing already set options
  $DW->resetOptions($_POST['widget_id']);

  // Role
  if ( $_POST['role'] == 'no' && count($_POST['role_act']) > 0 ) {
    $DW->addMultiOption($_POST['widget_id'], 'role', 'no', $_POST['role_act']);
  }

  // Date
  if ( $_POST['date'] == 'no' ) {
    $dates = array();
    if ( preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $date_start) ) {
      $dates['date_start'] = $date_start;
    }
    if ( preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $date_end) ) {
      $dates['date_end'] = $date_end;
    }

    if ( count($dates) > 0 ) {
      $DW->addDate($_POST['widget_id'], $dates);
    }
  }

  // Browser
	if ( isset($_POST['browser_act']) && count($_POST['browser_act']) > 0 ) {
		$DW->addMultiOption($_POST['widget_id'], 'browser', $_POST['browser'], $_POST['browser_act']);
	} else if ( isset($_POST['browser']) && $_POST['browser'] == 'no' ) {
		$DW->addSingleOption($_POST['widget_id'], 'browser');
	}

  // Front Page
  if ( isset($_POST['front-page']) && $_POST['front-page'] == 'no' ) {
    $DW->addSingleOption($_POST['widget_id'], 'front-page');
  }

  // Single Post
  if ( $_POST['single'] == 'no' ) {
    $DW->addSingleOption($_POST['widget_id'], 'single');
  }

  // -- Author
  if ( isset($_POST['single_author_act']) && count($_POST['single_author_act']) > 0 ) {
    if ( $_POST['single'] == 'yes' ) {
      $DW->addSingleOption($_POST['widget_id'], 'single', '1');
    }
    $DW->addMultiOption($_POST['widget_id'], 'single-author', $_POST['single'], $_POST['single_author_act']);
  }

  // -- Category
  if ( isset($_POST['single_category_act']) && count($_POST['single_category_act']) > 0 ) {
    if ( $_POST['single'] == 'yes' && count($_POST['single_author_act']) == 0 ) {
      $DW->addSingleOption($_POST['widget_id'], 'single', '1');
    }
    $DW->addMultiOption($_POST['widget_id'], 'single-category', $_POST['single'], $_POST['single_category_act']);
  }

  // -- Individual / Posts / Tag
  if ( isset($_POST['individual']) && $_POST['individual'] == '1' ) {
    $DW->addSingleOption($_POST['widget_id'], 'individual', '1');
    if ( count($_POST['single_post_act']) > 0 ) {
      $DW->addMultiOption($_POST['widget_id'], 'single-post', $_POST['single'], $_POST['single_post_act']);
    }
    if ( count($_POST['single_tag_act']) > 0 ) {
      $DW->addMultiOption($_POST['widget_id'], 'single-tag', $_POST['single'], $_POST['single_tag_act']);
    }
  }

  // Attachment
	if ( $_POST['attachment'] == 'no' ) {
		$DW->addSingleOption($_POST['widget_id'], 'attachment');
	}

  // Pages
  if ( isset($_POST['page_act']) && count($_POST['page_act']) > 0 ) {
    $DW->addMultiOption($_POST['widget_id'], 'page', $_POST['page'], $_POST['page_act']);
  } else if ( $_POST['page'] == 'no' ) {
    $DW->addSingleOption($_POST['widget_id'], 'page');
  }

  // -- Childs
  if ( isset($_POST['page_act']) && count($_POST['page_act']) > 0 && isset($_POST['page_childs_act']) && count($_POST['page_childs_act']) > 0 ) {
  	$child_act = array();
		foreach ( $_POST['page_childs_act'] as $act ) {
  		if ( in_array($act, $_POST['page_act']) ) {
  			$childs_act[ ] = $act;
  		}
  	}
  	$DW->addMultiOption($_POST['widget_id'], 'page-childs', $_POST['page'], $childs_act);
  }

  // Author
  if ( isset($_POST['author_act']) && count($_POST['author_act']) > 0 ) {
    $DW->addMultiOption($_POST['widget_id'], 'author', $_POST['author'], $_POST['author_act']);
  } else if ( $_POST['author'] == 'no' ) {
    $DW->addSingleOption($_POST['widget_id'], 'author');
  }

  // Categories
  if ( isset($_POST['category_act']) && count($_POST['category_act']) > 0 ) {
    $DW->addMultiOption($_POST['widget_id'], 'category', $_POST['category'], $_POST['category_act']);
  } else if ( $_POST['category'] == 'no' ) {
    $DW->addSingleOption($_POST['widget_id'], 'category');
  }

  // Archive
  if ( $_POST['archive'] == 'no' ) {
    $DW->addSingleOption($_POST['widget_id'], 'archive');
  }

  // Error 404
  if ( $_POST['e404'] == 'no' ) {
  	$DW->addSingleOption($_POST['widget_id'], 'e404');
  }

  // Search
  if ( $_POST['search'] == 'no' ) {
    $DW->addSingleOption($_POST['widget_id'], 'search');
  }

  // Custom Types (WP >= 3.0)
  if ( version_compare($GLOBALS['wp_version'], '3.0', '>=') && isset($_POST['post_types']) ) {
    foreach ( $_POST['post_types'] as $type ) {
      $act_field = $type . '_act';
      if ( count($_POST[$act_field]) > 0 ) {
        $DW->addMultiOption($_POST['widget_id'], $type, $_POST[$type], $_POST[$act_field]);
      } else if ( $_POST[$type] == 'no' ) {
        $DW->addSingleOption($_POST['widget_id'], $type);
      }

    	// -- Childs
    	$act_childs_field = $type . '_childs_act';
    	if ( count($_POST[$act_field]) > 0 && isset($_POST[$act_childs_field]) && count($_POST[$act_childs_field]) > 0 ) {
    		$childs_act = array();
    		foreach ( $_POST[$act_childs_field] as $act ) {
    			if ( in_array($act, $_POST[$act_field]) ) {
    				$childs_act[ ] = $act;
    			}
    		}
    		$DW->addMultiOption($_POST['widget_id'], $type . '-childs', $_POST[$type], $childs_act);
    	}
    }

  	if ( isset($_POST['cp_archive_act']) && count($_POST['cp_archive_act']) > 0 ) {
  		$DW->addMultiOption($_POST['widget_id'], 'cp_archive', $_POST['cp_archive'], $_POST['cp_archive_act']);
  	} else if ( $_POST['cp_archive'] == 'no' ) {
  		$DW->addSingleOption($_POST['widget_id'], 'cp_archive');
  	}
  }

  // WPML PLugin support
	if ( isset($_POST['wpml_act']) && count($_POST['wpml_act']) > 0 ) {
		$DW->addMultiOption($_POST['widget_id'], 'wpml', $_POST['wpml'], $_POST['wpml_act']);
	} else if ( isset($_POST['wpml']) && $_POST['wpml'] == 'no' ) {
		$DW->addSingleOption($_POST['widget_id'], 'wpml');
	}

  // WPSC/WPEC Plugin support
	if ( isset($_POST['wpsc_act']) && count($_POST['wpsc_act']) > 0 ) {
		$DW->addMultiOption($_POST['widget_id'], 'wpsc', $_POST['wpsc'], $_POST['wpsc_act']);
	} else if ( isset($_POST['wpsc']) && $_POST['wpsc'] == 'no' ) {
		$DW->addSingleOption($_POST['widget_id'], 'wpsc');
	}

	// BP Plugin support
	if ( isset($_POST['bp_act']) && count($_POST['bp_act']) > 0 ) {
		$DW->addMultiOption($_POST['widget_id'], 'bp', $_POST['bp'], $_POST['bp_act']);
	} else if ( isset($_POST['bp']) && $_POST['bp'] == 'no' ) {
		$DW->addSingleOption($_POST['widget_id'], 'bp');
	}

	// BP Plugin support (Groups)
	if ( isset($_POST['bp_group_act']) && count($_POST['bp_group_act']) > 0 ) {
		$DW->addMultiOption($_POST['widget_id'], 'bp-group', $_POST['bp-group'], $_POST['bp_group_act']);
	} else if ( isset($_POST['bp-group']) && $_POST['bp-group'] == 'no' ) {
		$DW->addSingleOption($_POST['widget_id'], 'bp-group');
	}

  // Redirect to ReturnURL
  if (! empty($_POST['returnurl']) ) {
    $q = array();

    // Checking if there are arguments set
    $pos = strpos($_POST['returnurl'],'?');
    if ( $pos !== FALSE ) {
      // evaluate the args
      $query_string = substr($_POST['returnurl'], ($pos+1));
      $args = explode('&', $query_string);
      foreach ( $args as $arg ) {
        @list($name, $value) = explode('=', $arg);
        if ( $name != 'dynwid_save' && $name != 'widget_id' ) {
          $q[ ] = $name . '=' . $value;
        }
      }
      $script_url = substr($_POST['returnurl'],0,$pos);
    } else {
      $script_url = $_POST['returnurl'];
    }
    $q[ ] = 'dynwid_save=yes';
    $q[ ] = 'widget_id=' . $_POST['widget_id'];

    wp_redirect( $script_url . '?' . implode('&', $q) );
    die();
  }
?>