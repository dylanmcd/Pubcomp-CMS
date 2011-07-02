<?php
/**
 * dynwid_worker.php - The worker does the actual work.
 *
 * @version $Id: dynwid_worker.php 348295 2011-02-20 20:13:21Z qurl $
 * @copyright 2011 Jacco Drabbe
 */

	$DW->message('Worker START');
	$DW->message('WhereAmI = ' . $DW->whereami);

	// WPML Plugin support
	if ( defined('ICL_PLUGIN_PATH') ) {
		$wpml_api = ICL_PLUGIN_PATH . DW_WPML_API;

		if ( file_exists($wpml_api) ) {
			require_once($wpml_api);
			$curlang = wpml_get_current_language();
		}
	}

  foreach ( $sidebars as $sidebar_id => $widgets ) {
    // Only processing active sidebars with widgets
    if ( $sidebar_id != 'wp_inactive_widgets' && count($widgets) > 0 ) {
      foreach ( $widgets as $widget_key => $widget_id ) {
        // Check if the widget has options set
        if ( in_array($widget_id, $DW->dynwid_list) ) {
          $act = array();
          $opt = $DW->getOptions($widget_id, $DW->whereami, FALSE);
          $DW->message('Number of rules to check for widget ' . $widget_id . ': ' . count($opt));
          $display = TRUE;
          $role = TRUE;
          $date = TRUE;
        	$browser = TRUE;
        	$wpml = TRUE;

          foreach ( $opt as $condition ) {
            if ( empty($condition['name']) && $condition['value'] == '0' ) {
              $DW->message('Default for ' . $widget_id . ' set to FALSE (rule D1)');
              $display = FALSE;
              $other = TRUE;
              break;
            } else if (! in_array($condition['maintype'], $DW->overrule_maintype) ) {
              // Get default value
              if ( $condition['name'] == 'default' ) {
                $default = $condition['value'];
              	if ( $default == '0' ) {
              		$DW->message('Default for ' . $widget_id . ' set to FALSE (rule D2)');
              		$display = FALSE;
              		$other = TRUE;
              	} else {
              		$DW->message('Default for ' . $widget_id . ' set to TRUE (rule D3)');
              		$display = TRUE;
              		$other = FALSE;
              	}
              } else {
                $act[ ] = $condition['name'];
              }
            } else if ( $condition['maintype'] == 'role' && $condition['name'] == 'default' ) {
              $DW->message('Default for ' . $widget_id . ' set to FALSE (rule R1)');
              $role = FALSE;
            } else if ( $condition['maintype'] == 'date' && $condition['name'] == 'default' ) {
              $DW->message('Default for ' . $widget_id . ' set to FALSE (rule DT1)');
              $date = FALSE;
            } else if ( $condition['maintype'] == 'browser' && $condition['name'] == 'default' ) {
            	$DW->message('Default for ' . $widget_id . ' set to ' . ( (bool) $condition['value'] ? 'TRUE' : 'FALSE' ) . ' (rule DB1)');
            	$browser = (bool) $condition['value'];
            } else if ( $condition['maintype'] == 'wpml' && $condition['name'] == 'default' ) {
            	$DW->message('Default for ' . $widget_id . ' set to ' . ( (bool) $condition['value'] ? 'TRUE' : 'FALSE' ) . ' (rule DML1)');
            	$wpml = (bool) $condition['value'];
            }
          }

          // Act the condition(s) when there are options set
          if ( count($opt) > 0 ) {
            // Role exceptions
          	if (! $role ) {
          		foreach ( $opt as $condition ) {
          			if ( $condition['maintype'] == 'role' && in_array($condition['name'], $DW->userrole) ) {
          				$DW->message('Role set to TRUE (rule ER1)');
          				$role = TRUE;
          			}
          		}
          	}

            // Date exceptions
						if (! $date ) {
							$dates = array();
							foreach ( $opt as $condition ) {
								if ( $condition['maintype'] == 'date' ) {
									switch ( $condition['name'] ) {
										case 'date_start':
											$date_start = $condition['value'];
											break;

										case 'date_end':
											$date_end = $condition['value'];
											break;
									}
								}
							}
							$now = time();
							if (! empty($date_end) ) {
								@list($date_end_year, $date_end_month, $date_end_day) = explode('-', $date_end);
								if ( mktime(23, 59, 59, $date_end_month, $date_end_day, $date_end_year) > $now ) {
									$date = TRUE;
									$DW->message('End date is in the future, sets Date to TRUE (rule EDT1)');
									if (! empty($date_start) ) {
										@list($date_start_year, $date_start_month, $date_start_day) = explode('-', $date_start);
										if ( mktime(0, 0, 0, $date_start_month, $date_start_day, $date_start_year) > $now ) {
											$date = FALSE;
											$DW->message('From date is in the future, sets Date to FALSE (rule EDT2)');
										}
									}
								}
							} else if (! empty($date_start) ) {
								@list($date_start_year, $date_start_month, $date_start_day) = explode('-', $date_start);
								if ( mktime(0, 0, 0, $date_start_month, $date_start_day, $date_start_year) < $now ) {
									$date = TRUE;
									$DW->message('From date is in the past, sets Date to TRUE (rule EDT3)');
								}
							}
						}

          	// WPML
          	if ( isset($curlang) ) {
          		foreach ( $opt as $condition ) {
          			if ( $condition['maintype'] == 'wpml' && $condition['name'] == $curlang ) {
          				(bool) $wpml_tmp = $condition['value'];
          			}
          		}

          		if ( isset($wpml_tmp) && $wpml_tmp != $wpml ) {
          			$DW->message('Exception triggered for WPML language, sets display to ' . ( $wpml_tmp ? 'TRUE' : 'FALSE' ) . ' (rule EML1)');
          			$wpml = $wpml_tmp;
          		}
          	}
          	unset($wpml_tmp);

          	// Browser
          	foreach ( $opt as $condition ) {
          		if ( $condition['maintype'] == 'browser' && $condition['name'] == $DW->useragent ) {
          			(bool) $browser_tmp = $condition['value'];
          		}
          	}

          	if ( isset($browser_tmp) && $browser_tmp != $browser ) {
          		$DW->message('Exception triggered for browser, sets display to ' . ( $wpml_tmp ? 'TRUE' : 'FALSE' ) . ' (rule EB1)');
          		$browser = $browser_tmp;
          	}
          	unset($browser_tmp);

            // For debug messages
            $e = ( $other ) ? 'TRUE' : 'FALSE';

            // Display exceptions (custom post type)
            if ( $DW->custom_post_type ) {
              // Custom Post Type behaves the same as a single post
              $post = $GLOBALS['post'];
              if ( count($act) > 0 ) {
                $id = $post->ID;
                $DW->message('PostID: ' . $id);
                if ( $DW->wpml ) {
                  $id = dw_wpml_get_id($id, 'post_' . $post_type);
                  $DW->message('WPML ObjectID: ' . $id);
                }

              	foreach ( $opt as $condition ) {
              		if ( $condition['name'] != 'default' ) {
              			switch ( $condition['maintype'] ) {
              				case $DW->whereami:
              					$act_custom[ ] = $condition['name'];
              					break;

              				case $DW->whereami . '-childs':
              					$act_childs[ ] = $condition['name'];
              					break;
              			}
              		}
              	}

                if ( in_array($id, $act_custom) ) {
                  $display = $other;
                  $DW->message('Exception triggered for ' . $widget_id . ' sets display to ' . $e . ' (rule ECP1)');
                } else if ( count($act_childs) > 0 ) {
                	$parents = $DW->getParents('post', array(), $id);
                	if ( (bool) array_intersect($act_childs, $parents) ) {
                		$display = $other;
                		$DW->message('Exception triggered for ' . $widget_id . ' sets display to ' . $e . ' (rule ECP2)');
                	}
                }
              }
            } else {
              // no custom post type
              switch ( $DW->whereami ) {
                case 'single':
                	$post = $GLOBALS['post'];
                  $act_author = array();
                  $act_category = array();
                  $act_post = array();
                  $act_tag = array();
                  $post_category = array();
                  $post_tag = array();

                  // Get the categories from the post
                  $categories = get_the_category();
                  foreach ( $categories as $category ) {
                    $id =  $category->cat_ID;
                    if ( $DW->wpml ) {
                      $id = dw_wpml_get_id($id, 'tax_category');
                    }
                    $post_category[ ] = $id;
                  }

                  // Get the tags form the post
                  if ( has_tag() ) {
                    $tags = get_the_tags();
                    foreach ( $tags as $tag ) {
                      $post_tag[ ] = $tag->term_id;
                    }
                  } else {
                    $tags = array();
                  }

                  // Split out the conditions
                  foreach ( $opt as $condition ) {
                    if ( $condition['name'] != 'default' ) {
                      switch ( $condition['maintype'] ) {
                        case 'single-author':
                          $act_author[ ] = $condition['name'];
                          break;

                        case 'single-category':
                          $act_category[ ] = $condition['name'];
                          break;

                        case 'single-tag':
                          $act_tag[ ] = $condition['name'];
                          break;

                        case 'single-post':
                          $act_post[ ] = $condition['name'];
                          break;
                      } // END switch
                    }
                  }

                  /* Author AND Category */
                  if ( count($act_author) > 0 && count($act_category) > 0 ) {
                    // Use of array_intersect to be sure one value in both arrays returns true
                    if ( in_array($post->post_author, $act_author) && (bool) array_intersect($post_category, $act_category) ) {
                      $display = $other;
                      $DW->message('Exception triggered for ' . $widget_id . ' sets display to ' . $e . ' (rule ES1)');
                    }
                    /* Only Author */
                  } else if ( count($act_author) > 0 && count($act_category == 0) ) {
                    if ( in_array($post->post_author, $act_author) ) {
                      $display = $other;
                      $DW->message('Exception triggered for ' . $widget_id . ' sets display to ' . $e . ' (rule ES2)');
                    }
                    /* Only Category */
                  } else if ( count($act_author) == 0 && count($act_category) > 0 ) {
                    if ( (bool) array_intersect($post_category, $act_category) ) {
                      $display = $other;
                      $DW->message('Exception triggered for ' . $widget_id . ' sets display to ' . $e . ' (rule ES3)');
                    }
                    /* None or individual checked - individual is not included in the $opt */
                  } else {
                    /* Tags */
                    if ( count($act_tag) > 0 ) {
                      if ( (bool) array_intersect($post_tag, $act_tag) ) {
                        $display = $other;
                        $DW->message('Exception triggered for ' . $widget_id . ' sets display to ' . $e . ' (rule ES4)');
                      }
                    }
                    /* Posts */
                    if ( count($act_post) > 0 ) {
                      if ( in_array($post->ID, $act_post) ) {
                        $display = $other;
                        $DW->message('Exception triggered for ' . $widget_id . ' sets display to ' . $e . ' (rule ES5)');
                      }
                    }
                  }
                  break;

                case 'home':
                  if ( count($act) > 0 ) {
                    $home_id = get_option('page_for_posts');
                  	$DW->message('ID = ' . $home_id);
                    if ( $DW->wpml ) {
                      $home_id = dw_wpml_get_id($home_id);
                      $DW->message('WPML ObjectID: ' . $home_id);
                    }

                    if ( in_array($home_id, $act) ) {
                      $display = $other;
                      $DW->message('Exception triggered for ' . $widget_id . ' sets display to ' . $e . ' (rule EH1)');
                    }
                  }
                  break;

                case 'page':
                  if ( count($act) > 0 ) {
                  	$act_page = array();
                  	$act_childs = array();

                    $post = $GLOBALS['post'];
                    $id = $post->ID;
                  	$DW->message('ID = ' . $id);
                    if ( $DW->wpml ) {
                      $id = dw_wpml_get_id($id);
                      $DW->message('WPML ObjectID: ' . $id);
                    }

                  	foreach ( $opt as $condition ) {
                  		if ( $condition['name'] != 'default' ) {
                  			switch ( $condition['maintype'] ) {
                  				case 'page':
                  					$act_page[ ] = $condition['name'];
                  					break;

                  				case 'page-childs':
                  					$act_childs[ ] = $condition['name'];
                  					break;
                  			}
                  		}
                  	}

                    if ( in_array($id, $act_page) ) {
                      $display = $other;
                      $DW->message('Exception triggered for ' . $widget_id . ' sets display to ' . $e . ' (rule EP1)');
                    } else if ( count($act_childs) > 0 ) {
                    	$parents = $DW->getParents('page', array(), $id);
                    	if ( (bool) array_intersect($act_childs, $parents) ) {
                    		$display = $other;
                    		$DW->message('Exception triggered for ' . $widget_id . ' sets display to ' . $e . ' (rule EP2)');
                    	}
                    }
                  }
                  break;

                case 'author':
                  if ( count($act) > 0 && is_author($act) ) {
                    $display = $other;
                    $DW->message('Exception triggered for ' . $widget_id . ' sets display to ' . $e . ' (rule EA1)');
                  }
                  break;

                case 'category':
                  if ( count($act) > 0 ) {
                    $id = get_query_var('cat');
                    $DW->message('CatID: ' . $id);
                    if ( $DW->wpml ) {
                      $id = dw_wpml_get_id($id, 'tax_category');
                      $DW->message('WPML ObjectID: ' . $id);
                    }

                    if ( in_array($id, $act) ) {
                      $display = $other;
                      $DW->message('Exception triggered for ' . $widget_id . ' sets display to ' . $e . ' (rule EC1)');
                    }
                  }
                  break;

                case 'cp_archive':
                	if ( count($act) > 0 ) {
                		/*
                		   is_post_type_archive() is natively supported in WP 3.1
                		   WP 3.0.x gets is_post_type_archive() via plugin
                		   'Custom Post Type Archive', but does not accept array
                		*/
                		$is_cpa = FALSE;

                		if ( version_compare(substr($GLOBALS['wp_version'], 0, 3), '3.1', '>=') ) {
                			if ( is_post_type_archive($act) ) {
                				$is_cpa = TRUE;
                			}
                		} else {
                			$post_type = get_query_var('post_type');
                			if ( in_array($post_type, $act) ) {
                				$is_cpa = TRUE;
                			}
                		}

                		if ( $is_cpa ) {
                			$display = $other;
                			$DW->message('Exception triggered for ' . $widget_id . ' sets display to ' . $e . ' (rule ECPA1)');
                		}
                	}
                	break;

              	case 'wpsc':
              		if ( count($act) > 0 ) {
              			require_once(DW_PLUGIN . 'wpsc.php');

              			if ( is_dw_wpsc_category($act) ) {
              				$display = $other;
              				$DW->message('Exception triggered for ' . $widget_id . ' sets display to ' . $e . ' (rule ESC1)');
              			}
              		}
              		break;

              	case 'bp':
              		// We have to split out the conditions as we don't want the bp-groups to interfere
              		$act = array();
              		foreach ( $opt as $condition ) {
              			if ( $condition['name'] != 'default' && $condition['maintype'] == 'bp' ) {
              				$act[ ] = $condition['name'];
              			}
              		}

              		if ( count($act) > 0 ) {
              			require_once(DW_PLUGIN . 'bp.php');

              			if ( is_dw_bp_component($act) ) {
              				$display = $other;
              				$DW->message('Exception triggered for ' . $widget_id . ' sets display to ' . $e . ' (rule EBP1)');
              			}
              		}
              		break;

              	case 'bp-group':
              		if ( count($act) > 0 ) {
              			require_once(DW_PLUGIN . 'bp.php');

              			if ( is_dw_bp_group($act) ) {
              				$display = $other;
              				$DW->message('Exception triggered for ' . $widget_id . ' sets display to ' . $e . ' (rule EBPG1)');
              			}
              		}
              		break;


              } // END switch ( $DW->whereami )
            } // END if/else ( $DW->custom_post_type )
          } /* END if ( count($opt) > 0 ) */

          if (! $display || ! $role || ! $date || ! $browser || ! $wpml ) {
            $DW->message('Removed ' . $widget_id . ' from display, SID = ' . $sidebar_id . ' / WID = ' . $widget_id . ' / KID = ' . $widget_key);
          	if ( DW_OLD_METHOD ) {
          		unset($DW->registered_widgets[$widget_id]);
          	} else {
          		unset($sidebars[$sidebar_id][$widget_key]);
          		if (! isset($DW->removelist[$sidebar_id])  ) {
          			$DW->removelist[$sidebar_id] = array();
          		}
          		$DW->removelist[$sidebar_id][ ] = $widget_key;
          	}
          }
        } // END if ( in_array($widget_id, $DW->dynwid_list) )
      } // END foreach ( $widgets as $widget_id )
    } // END if ( $sidebar_id != 'wp_inactive_widgets' && count($widgets) > 0 )
  } // END foreach ( $DW->sidebars as $sidebar_id => $widgets )

  $DW->listmade = TRUE;
  $DW->message('Dynamic Widgets END');
?>