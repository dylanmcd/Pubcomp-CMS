<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 * * @package WordPress
 * @subpackage Smoke
 * @since Smoke 1.0
 */
?>

<!-- Footer Start -->
<div id="footer-back"></div>
<div class="container_16">
<?php if ( function_exists( 'get_option_tree') ) : if( get_option_tree( 'disable_footer') ) : ?>
<?php else : ?>
  <div id="footer-light"></div>
   
  <!-- Blog Categories -->
  <div class="blog-categories">
    <?php dynamic_sidebar(3); ?>
  </div>
  
  <!-- App Shop -->
  <div class="app-shop">
    <?php dynamic_sidebar(4); ?>
  </div>
  
  <!-- Twitter -->
  <div class="twitter-area">
    <?php dynamic_sidebar(5); ?>
  </div>
<?php endif; endif; ?>

<div style="clear:both;"></div>
  <div style="margin-bottom:40px;"></div>
  
  <!-- Footer Register -->
  <div class="grid_16 footer-register">
    <div class="register grid_12">
      <p>Copright 2011 pubcomp.com. All rights reserved.
    </div>
    <div class="grid_4 footer-logo">
    </div>
  </div>

</div>
<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>
<?php // var_dump(get_included_files()); ?>
