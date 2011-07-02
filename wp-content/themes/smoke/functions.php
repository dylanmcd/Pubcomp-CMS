<?php

function my_init() {
	if (!is_admin()) {
		wp_deregister_script('jquery');

		// load the local copy of jQuery in the footer
		wp_register_script('jquery-min', '/wp-content/themes/smoke/js/jquery-1.6.1.min.js');
		wp_register_script('jquery-ui-min', '/wp-content/themes/smoke/js/jquery-ui-1.8.13.custom.min.js');
		wp_register_script('jquery-spinner', '/wp-content/themes/smoke/js/jquery.spinner.js');
		wp_register_script('jquery-gritter', '/wp-content/themes/smoke/js/jquery.gritter.min.js');
		wp_register_script('pubcomp-core', '/wp-content/themes/smoke/js/pubcomp-core.js');
		wp_register_script('general', '/wp-content/themes/smoke/js/general.js');
		wp_register_script('function', '/wp-content/themes/smoke/js/function.js');

		// or load the Google API copy in the footer
		//wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js', false, '1.3.2', true);

		wp_enqueue_script('jquery-min');
		wp_enqueue_script('jquery-ui-min');
		wp_enqueue_script('jquery-gritter');
		wp_enqueue_script('jquery-spinner');
		wp_enqueue_script('pubcomp-core');
		wp_enqueue_script('general');
		wp_enqueue_script('function');
        wp_enqueue_style( 'bp-admin-bar', apply_filters( 'bp_core_admin_bar_css', BP_PLUGIN_URL . '/bp-themes/bp-default/_inc/css/adminbar.css' ) );
	}
}
add_action('init', 'my_init');

if ( function_exists('register_sidebar') )
register_sidebars(1,array('name' => 'Blog Top','before_widget' => '','after_widget' => '','before_title' => '<h2>','after_title' => '</h2>'));
register_sidebars(1,array('name' => 'Blog Bottom','before_widget' => '','after_widget' => '','before_title' => '<h2>','after_title' => '</h2>'));
register_sidebars(1,array('name' => 'Footer Left','before_widget' => '','after_widget' => '','before_title' => '<h2>','after_title' => '</h2>'));
register_sidebars(1,array('name' => 'Footer Center','before_widget' => '','after_widget' => '','before_title' => '<h2>','after_title' => '</h2>'));
register_sidebars(1,array('name' => 'Footer Right','before_widget' => '','after_widget' => '','before_title' => '<h2>','after_title' => '</h2>'));

 register_nav_menus(array(
'topmenu' => ''
));


//Başlıkları karakter bazlı kısaltma
function the_title_limit($length, $replacer = '...') {
 $string = the_title('','',FALSE);
 if(strlen($string) > $length)
 $string = (preg_match('/^(.*)\W.*$/', substr($string, 0, $length+1), $matches) ? $matches[1] : substr($string, 0, $length)) . $replacer;
 echo $string;
}

function content($num) {
$theContent = get_the_content();
$output = preg_replace('/<img[^>]+./','', $theContent);
$output = preg_replace( '/<blockquote>.*<\/blockquote>/', '', $output );
$output = preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $output );
$limit = $num+1;
$content = explode(' ', $output, $limit);
array_pop($content);
$content = implode(" ",$content)."...";
echo $content;
}

// Big Button 1 //
function sc_bigone($atts) {
extract(shortcode_atts(array(
"url" => '',
"title" => '',
"desc" => '',
"align" => ''
), $atts));

if ($align == '') {
$align='center';
}

$var_sHTML = '';
$var_sHTML .= ' <div class="big-message-button" style="width:' . $desc . '%;">
          <h1><a href="' . $url . '">' . $title . '</a></h1>
          <img src="wp-content/themes/smoke/image/subrss.png" alt="">
        </div> ';

if ($align == 'right' || $align == 'left') {
$var_sHTML .= '<div class="dlbutton-floatreset"></div>';
}

return $var_sHTML;
}

add_shortcode('big1', 'sc_bigone');

// Big Button 2 //
function sc_bigtwo($atts) {
extract(shortcode_atts(array(
"url" => '',
"title" => '',
"desc" => '',
"align" => ''
), $atts));

if ($align == '') {
$align='center';
}

$var_sHTML = '';
$var_sHTML .= ' <div class="big-message-button-red" style="width:' . $desc . '%;">
          <h1><a href="' . $url . '">' . $title . '</a></h1>
          <img src="wp-content/themes/smoke/image/subsupport.png" alt="">
        </div> ';

if ($align == 'right' || $align == 'left') {
$var_sHTML .= '<div class="dlbutton-floatreset"></div>';
}

return $var_sHTML;
}

add_shortcode('big2', 'sc_bigtwo');

// Big Button 3 //
function sc_bigthree($atts) {
extract(shortcode_atts(array(
"url" => '',
"title" => '',
"desc" => '',
"align" => ''
), $atts));

if ($align == '') {
$align='center';
}

$var_sHTML = '';
$var_sHTML .= ' <div class="big-message-button-yellow" style="width:' . $desc . '%;">
          <h1><a href="' . $url . '">' . $title . '</a></h1>
          <img src="wp-content/themes/smoke/image/subwiki.png" alt="">
        </div> ';

if ($align == 'right' || $align == 'left') {
$var_sHTML .= '<div class="dlbutton-floatreset"></div>';
}

return $var_sHTML;
}

add_shortcode('big3', 'sc_bigthree');

// Big Button 4 //
function sc_bigfour($atts) {
extract(shortcode_atts(array(
"url" => '',
"title" => '',
"desc" => '',
"align" => ''
), $atts));

if ($align == '') {
$align='center';
}

$var_sHTML = '';
$var_sHTML .= ' <div class="big-message-button" style="width:' . $desc . '%;">
          <h1><a href="' . $url . '">' . $title . '</a></h1>
          <img src="wp-content/themes/smoke/image/postfile.png" alt="">
        </div> ';

if ($align == 'right' || $align == 'left') {
$var_sHTML .= '<div class="dlbutton-floatreset"></div>';
}

return $var_sHTML;
}

add_shortcode('big4', 'sc_bigfour');

// Big Button 5 //
function sc_bigfive($atts) {
extract(shortcode_atts(array(
"url" => '',
"title" => '',
"desc" => '',
"align" => ''
), $atts));

if ($align == '') {
$align='center';
}

$var_sHTML = '';
$var_sHTML .= ' <div class="big-message-button-blue" style="width:' . $desc . '%;">
          <h1><a href="' . $url . '">' . $title . '</a></h1>
          <img src="wp-content/themes/smoke/image/buy.png" alt="">
        </div> ';

if ($align == 'right' || $align == 'left') {
$var_sHTML .= '<div class="dlbutton-floatreset"></div>';
}

return $var_sHTML;
}

add_shortcode('big5', 'sc_bigfive');

// Button 6 //
function sc_one($atts) {
extract(shortcode_atts(array(
"url" => '',
"title" => '',
"desc" => '',
"align" => ''
), $atts));

if ($align == '') {
$align='center';
}

$var_sHTML = '';
$var_sHTML .= ' <a href="' . $url . '" class="button-white" style="margin-right:20px;"><img src="wp-content/themes/smoke/image/b1.png"> ' . $title . ' </a> ';

if ($align == 'right' || $align == 'left') {
$var_sHTML .= '<div class="dlbutton-floatreset"></div>';
}

return $var_sHTML;
}

add_shortcode('b1', 'sc_one');

// Button 7 //
function sc_two($atts) {
extract(shortcode_atts(array(
"url" => '',
"title" => '',
"desc" => '',
"align" => ''
), $atts));

if ($align == '') {
$align='center';
}

$var_sHTML = '';
$var_sHTML .= ' <a href="' . $url . '" class="mini-red" style="margin-right:4px;"> ' . $title . ' </a> ';

if ($align == 'right' || $align == 'left') {
$var_sHTML .= '<div class="dlbutton-floatreset"></div>';
}

return $var_sHTML;
}

add_shortcode('b2', 'sc_two');

// Button 8 //
function sc_three($atts) {
extract(shortcode_atts(array(
"url" => '',
"title" => '',
"desc" => '',
"align" => ''
), $atts));

if ($align == '') {
$align='center';
}

$var_sHTML = '';
$var_sHTML .= ' <a href="' . $url . '" class="mini-yellow" style="margin-right:3px;"> ' . $title . ' </a> ';

if ($align == 'right' || $align == 'left') {
$var_sHTML .= '<div class="dlbutton-floatreset"></div>';
}

return $var_sHTML;
}

add_shortcode('b3', 'sc_three');

/**
 * TwentyTen functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, twentyten_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'twentyten_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640;

/** Tell WordPress to run twentyten_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'twentyten_setup' );

if ( ! function_exists( 'twentyten_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyten_setup() in a child theme, add your own twentyten_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Post Format support. You can also use the legacy "gallery" or "asides" (note the plural) categories.
	add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( '', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	

	// This theme allows users to set a custom background
	add_custom_background();

	// Your changeable header business starts here
	if ( ! defined( 'HEADER_TEXTCOLOR' ) )
		define( 'HEADER_TEXTCOLOR', '' );

	// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
	if ( ! defined( 'HEADER_IMAGE' ) )
		define( 'HEADER_IMAGE', '%s/images/headers/path.jpg' );

	// The height and width of your custom header. You can hook into the theme's own filters to change these values.
	// Add a filter to twentyten_header_image_width and twentyten_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyten_header_image_width', 940 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyten_header_image_height', 198 ) );

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be 940 pixels wide by 198 pixels tall.
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

	// Don't support text inside the header image.
	if ( ! defined( 'NO_HEADER_TEXT' ) )
		define( 'NO_HEADER_TEXT', true );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See twentyten_admin_header_style(), below.
	add_custom_image_header( '', 'twentyten_admin_header_style' );

	// ... and thus ends the changeable header business.

	
}
endif;

if ( ! function_exists( 'twentyten_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in twentyten_setup().
 *
 * @since Twenty Ten 1.0
 */
function twentyten_admin_header_style() {
?>
<style type="text/css">
/* Shows the same border as on front end */
#headimg {
	border-bottom: 1px solid #000;
	border-top: 4px solid #000;
}
/* If NO_HEADER_TEXT is false, you would style the text with these selectors:
	#headimg #name { }
	#headimg #desc { }
*/
</style>
<?php
}
endif;

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentyten_page_menu_args' );

/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @since Twenty Ten 1.0
 * @return int
 */
function twentyten_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'twentyten_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since Twenty Ten 1.0
 * @return string "Continue Reading" link
 */
function twentyten_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', '' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and twentyten_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Twenty Ten 1.0
 * @return string An ellipsis
 */
function twentyten_auto_excerpt_more( $more ) {
	return ' &hellip;' . twentyten_continue_reading_link();
}
add_filter( 'excerpt_more', 'twentyten_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Twenty Ten 1.0
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function twentyten_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= twentyten_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'twentyten_custom_excerpt_more' );

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Twenty Ten's style.css. This is just
 * a simple filter call that tells WordPress to not use the default styles.
 *
 * @since Twenty Ten 1.2
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Deprecated way to remove inline styles printed when the gallery shortcode is used.
 *
 * This function is no longer needed or used. Use the use_default_gallery_style
 * filter instead, as seen above.
 *
 * @since Twenty Ten 1.0
 * @deprecated Deprecated in Twenty Ten 1.2 for WordPress 3.1
 *
 * @return string The gallery style filter, with the styles themselves removed.
 */
function twentyten_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
// Backwards compatibility with WordPress 3.0.
if ( version_compare( $GLOBALS['wp_version'], '3.1', '<' ) )
	add_filter( 'gallery_style', 'twentyten_remove_gallery_css' );

if ( ! function_exists( 'twentyten_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyten_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
  
    <div class="comment-list">
      <?php echo get_avatar( $comment, 50 ); ?>
      <h2 style="margin-bottom:-14px;"><?php printf( __( '%s <span class="says">says:</span>', '' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?> <span style="font-size:11px;"> [<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', '' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', '' ), ' ' );
			?> ]</span></h2>
      
      <?php if ( $comment->comment_approved == '0' ) : ?>
			<p><?php _e( 'Your comment is awaiting moderation.', '' ); ?></p>
	  <?php endif; ?>
      
      <p><?php comment_text(); ?></p>
      
      
      <div style="clear:both;"></div>
      <div class="reply"><?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></div>
    </div>
    
	<?php
			break;
	endswitch;
}
endif;

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override twentyten_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @since Twenty Ten 1.0
 * @uses register_sidebar
 */
function twentyten_widgets_init() {
	

	
}
/** Register sidebars by running twentyten_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'twentyten_widgets_init' );

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 * This function uses a filter (show_recent_comments_widget_style) new in WordPress 3.1
 * to remove the default style. Using Twenty Ten 1.2 in WordPress 3.0 will show the styles,
 * but they won't have any effect on the widget in default Twenty Ten styling.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_remove_recent_comments_style() {
	add_filter( 'show_recent_comments_widget_style', '__return_false' );
}
add_action( 'widgets_init', 'twentyten_remove_recent_comments_style' );

if ( ! function_exists( 'twentyten_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', '' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', '' ), get_the_author() ),
			get_the_author()
		)
	);
}
endif;

if ( ! function_exists( 'twentyten_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Twenty Ten 1.0
 */
function twentyten_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', '' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', '' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', '' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;
