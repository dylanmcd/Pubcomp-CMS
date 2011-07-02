<?php
/**
 * dynwid_class.php - Dynamic Widgets Class (PHP5)
 *
 * @version $Id: dynwid_class.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

  class dynWid {
    public  $bp;				// BuddyPress Plugin support
  	public  $bp_groups;	// BuddyPress Plugin support (groups)
  	public	$custom_post_type;
    private $dbtable;
  	public  $dwoptions;
    public  $dynwid_list;
  	public  $enabled;
    private $firstmessage;
  	public  $listmade;
		public  $overrule_maintype;
    private $registered_sidebars;
    public  $registered_widget_controls;
    public  $registered_widgets;
  	public  $removelist;
    public  $sidebars;
    public  $plugin_url;
  	public  $useragent;
    public  $userrole;
  	public  $whereami;
    private $wpdb;
    public  $wpml;		// WPML Plugin support
    public  $wpsc;		// WPSC/WPEC Plugin support

    public function __construct() {
      if ( is_user_logged_in() ) {
				$this->userrole = $GLOBALS['current_user']->roles;
      } else {
        $this->userrole = array('anonymous');
      }

    	$this->custom_post_type = FALSE;
      $this->firstmessage = TRUE;
    	$this->listmade = FALSE;
    	$this->overrule_maintype = array('date', 'role', 'browser', 'wpml');
      $this->registered_sidebars = $GLOBALS['wp_registered_sidebars'];
      $this->registered_widget_controls = &$GLOBALS['wp_registered_widget_controls'];
      $this->registered_widgets = &$GLOBALS['wp_registered_widgets'];
    	$this->removelist = array();
      $this->sidebars = wp_get_sidebars_widgets();
    	$this->useragent = $this->getBrowser();
      $this->plugin_url = WP_PLUGIN_URL . '/' . str_replace( basename(__FILE__), '', plugin_basename(__FILE__) );

    	$this->dwoptions = array(
	    	'role'        => __('Role'),
	    	'date'        => __('Date'),
	    	'browser'			=> __('Browser', DW_L10N_DOMAIN),
	    	'wpml'				=> __('Language', DW_L10N_DOMAIN),
	    	'front-page'  => __('Front Page', DW_L10N_DOMAIN),
	    	'single'      => __('Single Posts', DW_L10N_DOMAIN),
	    	'attachment'	=> __('Attachments', DW_L10N_DOMAIN),
	    	'page'        => __('Pages'),
	    	'author'      => __('Author Pages', DW_L10N_DOMAIN),
	    	'category'    => __('Category Pages', DW_L10N_DOMAIN),
	    	'archive'     => __('Archive Pages', DW_L10N_DOMAIN),
	    	'e404'        => __('Error Page', DW_L10N_DOMAIN),
	    	'search'      => __('Search page', DW_L10N_DOMAIN),
	    	'wpsc'				=> __('WPSC Category', DW_L10N_DOMAIN)
	    );

    	// Adding Custom Post Types to $this->dwoptions
    	if ( version_compare($GLOBALS['wp_version'], '3.0', '>=') ) {
    		$args = array(
    		          'public'   => TRUE,
    		          '_builtin' => FALSE
    		        );
    		$post_types = get_post_types($args, 'objects', 'and');
    		foreach ( $post_types as $ctid ) {
    			$this->dwoptions[key($post_types)] = $ctid->label;
    		}
    	}

    	// DB init
      $this->wpdb = $GLOBALS['wpdb'];
      $this->dbtable = $this->wpdb->prefix . DW_DB_TABLE;
    	$query = "SHOW TABLES LIKE '" . $this->dbtable . "'";
    	$result = $this->wpdb->get_var($query);

    	if ( is_null($result) ) {
    		$this->enabled = FALSE;
    	} else {
    		$this->enabled = TRUE;
    	}

    	// BuddyPress Plugin support
    	$this->bp = FALSE;
    	$this->bp_groups = FALSE;

			// WPML Plugin support
      $this->wpml = FALSE;

    	// WPSC/WPEC Plugin support
    	$this->wpsc = FALSE;
    }

    public function addDate($widget_id, $dates) {
      $query = "INSERT INTO " . $this->dbtable . "
                    (widget_id, maintype, name, value)
                  VALUES
                    ('" . $widget_id . "', 'date', 'default', '0')";
      $this->wpdb->query($query);

      foreach ( $dates as $name => $date ) {
        $query = "INSERT INTO " . $this->dbtable . "
                    (widget_id, maintype, name, value)
                  VALUES
                    ('" . $widget_id . "', 'date', '" . $name . "', '" . $date . "')";
        $this->wpdb->query($query);
      }
    }

    public function addMultiOption($widget_id, $maintype, $default, $act) {
      if ( $default == 'no' ) {
        $opt_default = '0';
        $opt_act = '1';
      } else {
        $opt_default = '1';
        $opt_act = '0';
      }

      $query = "INSERT INTO " . $this->dbtable . "
                      (widget_id, maintype, name, value)
                    VALUES
                      ('" . $widget_id . "', '" . $maintype . "', 'default', '" . $opt_default . "')";
      $this->wpdb->query($query);
      foreach ( $act as $option ) {
        $query = "INSERT INTO " . $this->dbtable . "
                      (widget_id, maintype, name, value)
                    VALUES
                      ('" . $widget_id . "', '" . $maintype . "', '" . $option . "', '" . $opt_act . "')";
        $this->wpdb->query($query);
      }
    }

    public function addSingleOption($widget_id, $maintype, $value = '0') {
      $query = "INSERT INTO " . $this->dbtable . "
                    (widget_id, maintype, value)
                  VALUES
                    ('" . $widget_id . "', '" . $maintype . "', '" . $value . "')";
      $this->wpdb->query($query);
    }

    public function checkWPhead() {
      $ct = current_theme_info();
      $headerfile = $ct->template_dir . '/header.php';
      if ( file_exists($headerfile) ) {
        $buffer = file_get_contents($headerfile);
        if ( strpos($buffer, 'wp_head()') ) {
          // wp_head() found
          return 1;
        } else {
          // wp_head() not found
          return 0;
        }
      } else {
        // wp_head() unable to determine
        return 2;
      }
    }

    private function createList() {
      $this->dynwid_list = array();

      foreach ( $this->sidebars as $sidebar_id => $widgets ) {
        if ( count($widgets) > 0 ) {
          foreach ( $widgets as $widget_id ) {
            if ( $this->hasOptions($widget_id) ) {
              $this->dynwid_list[ ] = $widget_id;
            }
          } // END foreach widgets
        }
      } // END foreach sidebars
    }

    public function deleteOption($widget_id, $maintype, $name = '') {
      $query = "DELETE FROM " . $this->dbtable . " WHERE widget_id = '" .$widget_id . "' AND maintype = '" .$maintype ."'";
      if (! empty($name) ) {
        $query .= " AND (name = '" . $name . "' OR name = 'default')";
      }
      $this->wpdb->query($query);
    }

    public function detectPage() {
    	if ( is_front_page() && get_option('show_on_front') == 'posts' ) {
        return 'front-page';
      } else if ( is_home() && get_option('show_on_front') == 'page' ) {
      	return 'home';
      } else if ( is_attachment() ) {
      	return 'attachment';					// must be before is_single(), otherwise detects as 'single'
      } else if ( is_single() ) {
        return 'single';
      } else if ( is_page() ) {
        return 'page';
      } else if ( is_author() ) {
        return 'author';
      } else if ( is_category() ) {
        return 'category';
      } else if ( function_exists('is_post_type_archive') && is_post_type_archive() ) {
    		return 'cp_archive';				// must be before is_archive(), otherwise detects as 'archive' in WP 3.1.0
      } else if ( is_archive() && ! is_category() && ! is_author() ) {
        return 'archive';
      } else if ( is_404() ) {
      	return 'e404';
      } else if ( is_search() ) {
        return 'search';
      } else {
        return 'undef';
      }
    }

    public function dump() {
    	echo "wp version: " . $GLOBALS['wp_version'] . "\n";
      echo "wp_head: " . $this->checkWPhead() . "\n";
    	echo "dw version: " . DW_VERSION . "\n";
    	echo "php version: " . PHP_VERSION . "\n";
      echo "\n";
      echo "front: " . get_option('show_on_front') . "\n";
      if ( get_option('show_on_front') == 'page' ) {
        echo "front page: " . get_option('page_on_front') . "\n";
        echo "posts page: " . get_option('page_for_posts') . "\n";
      }

    	echo "\n";
      echo "list: \n";
      $list = array();
      $this->createList();
      foreach ( $this->dynwid_list as $widget_id ) {
        $list[$widget_id] = strip_tags($this->getName($widget_id));
      }
      print_r($list);

      echo "wp_registered_widgets: \n";
      print_r($this->registered_widgets);

      echo "options: \n";
      print_r( $this->getOptions('%', NULL) );

      echo "\n";
      echo serialize($this->getOptions('%', NULL));
    }

    public function dumpOpt($opt) {
      if ( DW_DEBUG && count($opt) > 0 ) {
        echo '<pre>';
        print_r($opt);
        echo '</pre>';
      }
    }

    // replacement for createList() to make the worker faster
    public function dwList($whereami) {
      $this->dynwid_list = array();
      if ( $whereami == 'home' ) {
        $whereami = 'page';
      }

      $query = "SELECT DISTINCT widget_id FROM " . $this->dbtable . "
                  WHERE  maintype LIKE '" . $whereami . "%'
                  		OR maintype = 'role'
                  		OR maintype = 'date'
                  		OR maintype = 'browser'
                  		OR maintype = 'wpml'";
      $results = $this->wpdb->get_results($query);
      foreach ( $results as $myrow ) {
        $this->dynwid_list[ ] = $myrow->widget_id;
      }
    }

  	private function getBrowser() {
  		global $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome;

  		if ( $is_gecko ) {
  			return 'gecko';
  		} else if ( $is_IE ) {
  			return 'msie';
  		} else if ( $is_opera ) {
  			return 'opera';
  		} else if ( $is_NS4 ) {
  			return 'ns';
  		} else if ( $is_safari ) {
  			return 'safari';
  		} else if ( $is_chrome ) {
  			return 'chrome';
  		} else {
  			return 'undef';
  		}
  	}

    public function getName($id, $type = 'W') {
      switch ( $type ) {
        case 'S':
          $lookup = $this->registered_sidebars;
        break;

        default:
          $lookup = $this->registered_widgets;
        // end default
      }

      $name = $lookup[$id]['name'];

      if ( $type == 'W' && isset($lookup[$id]['params'][0]['number']) ) {
        // Retrieve optional set title
        $number = $lookup[$id]['params'][0]['number'];
        $option_name = $lookup[$id]['callback'][0]->option_name;
        $option = get_option($option_name);
        if (! empty($option[$number]['title']) ) {
          $name .= ': <span class="in-widget-title">' . $option[$number]['title'] . '</span>';
        }
      }

      return $name;
    }

    public function getOptions($widget_id, $maintype, $admin = TRUE) {
      $opt = array();

      if ( $admin ) {
        $query = "SELECT widget_id, maintype, name, value FROM " . $this->dbtable . "
                  WHERE widget_id LIKE '" . $widget_id . "'
                    AND maintype LIKE '" . $maintype . "%'
                  ORDER BY maintype, name";

      } else {
      	if ( $maintype == 'home' ) {
      		$maintype = 'page';
      	}
        $query = "SELECT widget_id, maintype, name, value FROM " . $this->dbtable . "
                  WHERE widget_id LIKE '" . $widget_id . "'
                    AND (maintype LIKE '" . $maintype . "%'
                    	OR maintype = 'role'
                    	OR maintype = 'date'
                    	OR maintype = 'browser'
                    	OR maintype = 'wpml')
                  ORDER BY maintype, name";
      }
      $results = $this->wpdb->get_results($query);

      foreach ( $results as $myrow ) {
        $opt[ ] = array('widget_id' => $myrow->widget_id,
                        'maintype' => $myrow->maintype,
                        'name' => $myrow->name,
                        'value' => $myrow->value
                  );
      }

      return $opt;
    }

  	public function getParents($type, $arr, $id) {
  		if ( $type == 'page' ) {
  			$obj = get_page($id);
  		} else {
  			$obj = get_post($id);
  		}

  		if ( $obj->post_parent > 0 ) {
  			$arr[ ] = $obj->post_parent;
  			$a = &$arr;
  			$a = $this->getParents($type, $a, $obj->post_parent);
  		}

  		return $arr;
  	}

    public function hasOptions($widget_id) {
      $query = "SELECT COUNT(1) AS total FROM " . $this->dbtable . "
                  WHERE widget_id = '" . $widget_id . "' AND
                        maintype != 'individual'";
      $count = $this->wpdb->get_var($this->wpdb->prepare($query));

      if ( $count > 0 ) {
        return TRUE;
      } else {
        return FALSE;
      }
    }

  	public function housekeeping() {
  		$widgets = array_keys($this->registered_widgets);

  		$query = "SELECT DISTINCT widget_id FROM " . $this->dbtable;
  		$results = $this->wpdb->get_results($query);
  		foreach ( $results as $myrow ) {
  			if (! in_array($myrow->widget_id, $widgets) ) {
  				$this->resetOptions($myrow->widget_id);
  			}
  		}
  	}

    public function message($text) {
      if ( DW_DEBUG ) {
        if ( $this->firstmessage ) {
          echo "\n";
          $this->firstmessage = FALSE;
        }
        echo '<!-- ' . $text . ' //-->';
        echo "\n";
      }
    }

    public function resetOptions($widget_id) {
      $query = "DELETE FROM " . $this->dbtable . " WHERE widget_id = '" . $widget_id . "'";
      $this->wpdb->query($query);
    }
  }
?>