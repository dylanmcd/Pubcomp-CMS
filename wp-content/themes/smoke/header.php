<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
//global $PubcompSteamLogin;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', '' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link href="<?php bloginfo('template_directory'); ?>/css/app/general.css" rel="stylesheet" type="text/css" />
<link href="<?php bloginfo('template_directory'); ?>/css/style/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/mediaboxAdvBlack21.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/ui-dark-striped/ui.css" type="text/css" />
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/jquery.gritter.css" type="text/css" />
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/pubcomp-core.css" type="text/css" />

<!--[if IE 8]> <style> @import url("/css/style/ie8.css"); </style> <![endif]-->
<!--[if IE 9]> <style> @import url("/css/style/ie9.css"); </style> <![endif]-->


<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
<?php echo pubcomp_get_finalize_js(); ?>
</head>

<body <?php body_class(); ?>>

<!-- Top Navigation Start -->
<div class="top-navigation"></div>
<div class="container_16">

  <!-- Logo -->
  <div class="grid_4">
  <?php
					// Check if this is a post or page, if it has a thumbnail, and if it's a big one
					if ( is_singular() && current_theme_supports( 'post-thumbnails' ) &&
							has_post_thumbnail( $post->ID ) &&
							( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' ) ) &&
							$image[1] >= HEADER_IMAGE_WIDTH ) :
						// Houston, we have a new header image!
						echo get_the_post_thumbnail( $post->ID );
					elseif ( get_header_image() ) : ?>
					<?php endif; ?>
  <a href="<?php bloginfo('url'); ?>">
		  <?php if ( function_exists( 'get_option_tree') ) :
          if( get_option_tree( 'logo') ) : ?>
          <a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php get_option_tree( 'logo', '', 'true' ); ?>" width="466" height="100" alt="" style="margin-bottom:15px;" /></a>
          <?php else : ?>
          <a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php bloginfo('template_directory'); ?>/image/logo.png" alt="" width="218" height="103"></a>
          <?php endif; endif; ?>
        </a>
  </div>
  
  <!-- Menu -->
  <div class="grid_12 menu-div">
    <?php wp_nav_menu( array( 'container_id' => 'topmenu', 'theme_location' => 'topmenu' ) ); ?>
  </div>
  <div id="signup-dialog" style="display: none; width: 600px;">
<?php echo pubcomp_get_finalize_html(); ?>
</div>

</div>
<!-- Top Navigation End -->
