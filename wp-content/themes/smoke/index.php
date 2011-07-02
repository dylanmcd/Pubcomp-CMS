<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 * * @package WordPress
 * @subpackage Smoke
 * @since Smoke 1.0
 */

get_header(); ?>

<!-- Slider Start -->
<div class="container_16">
  <div class="grid_16">
    <div id="slider-wrapper">
      <div id="slider" class="nivoSlider">
        <?php
			if ( function_exists( 'get_option_tree' ) ) {
			  $slides = get_option_tree( 'slide', $option_tree, false, true, -1 );
			  foreach( $slides as $slide ) { ?>
				
				<a href="<?php echo $slide['link']; ?>"><img src="<?php echo $slide['image']; ?>" alt="<?php echo $slide['title']; ?>" width="992" height="442" /></a>
                
                <?php
			  }
			}
		  ?>
                  
      </div>
    </div>
  </div>
</div>
<div id="frame-slide"></div>
<div id="back-slide"></div>
<div id="back-pattern"></div>
<div style=" clear:both;"></div>
<?php if ( function_exists( 'get_option_tree') ) : if( get_option_tree( 'disable_advert') ) : ?>
<?php else : ?>
<div style="margin-bottom:100px;"></div>
<!-- Slider End -->


<!-- Advert Start -->
<div class="container_16">
  <div class="grid_16 iefix_advert">
    <!-- 1 -->
    <div class="advert">
      <img src="<?php get_option_tree( 'advert1_image', '', 'true' ); ?>" width="282" height="177">
      <h1><?php get_option_tree( 'advert1_title', '', 'true' ); ?></h1>
      <h2 style="color:#6ea4ca;"><?php get_option_tree( 'advert1_title_two', '', 'true' ); ?></h2>
      <p><?php get_option_tree( 'advert1_des', '', 'true' ); ?></p>
      <a href="<?php get_option_tree( 'advert1_link', '', 'true' ); ?>" class="button-white"><img src="<?php bloginfo('template_directory'); ?>/image/b1.png"> <?php get_option_tree( 'advert1_link_name', '', 'true' ); ?></a>
    </div>
    
    <!-- 2 -->
    <div class="advert" style="margin:0px 0px 0px 48px;">
      <img src="<?php get_option_tree( 'advert2_image', '', 'true' ); ?>" width="282" height="177">
      <h1><?php get_option_tree( 'advert2_title', '', 'true' ); ?></h1>
      <h2 style="color:#6ea4ca;"><?php get_option_tree( 'advert2_title_two', '', 'true' ); ?></h2>
      <p><?php get_option_tree( 'advert2_des', '', 'true' ); ?></p>
      <a href="<?php get_option_tree( 'advert2_link', '', 'true' ); ?>" class="button-white"><img src="<?php bloginfo('template_directory'); ?>/image/b1.png"> <?php get_option_tree( 'advert2_link_name', '', 'true' ); ?></a>
    </div>
    
    <!-- 3 -->
    <div class="advert" style="float:right;">
      <img src="<?php get_option_tree( 'advert3_image', '', 'true' ); ?>" width="282" height="177">
      <h1><?php get_option_tree( 'advert3_title', '', 'true' ); ?></h1>
      <h2 style="color:#6ea4ca;"><?php get_option_tree( 'advert3_title_two', '', 'true' ); ?></h2>
      <p><?php get_option_tree( 'advert3_des', '', 'true' ); ?></p>
      <a href="<?php get_option_tree( 'advert3_link', '', 'true' ); ?>" class="button-white"><img src="<?php bloginfo('template_directory'); ?>/image/b1.png"> <?php get_option_tree( 'advert3_link_name', '', 'true' ); ?></a>
    </div>
  </div>
</div>
<div style="clear:both;"></div>
<?php endif; endif; ?>
<div style="margin-bottom:60px;"></div>
<!-- Advert End -->


<?php if ( function_exists( 'get_option_tree') ) : if( get_option_tree( 'disable_tabmenu') ) : ?>
<?php else : ?>
<!-- Tab Menu Start -->
<div id="tabmenu-back"></div>
<div class="container_16"> 
  <div class="grid_16" id="tabnavi-back"></div>
  <div class="grid_16" id="lighttab"></div>
  <div id="tabmenu-dot"></div>
  <div class="container_16" id="tabmenu"> 
  <div class="coda-slider-wrapper">
    <div class="coda-slider preload" id="coda-slider-1">
      <?php 
    $category = get_option_tree('slider_tab_menu_categorie');
    $number = 4;
    ?>
    <?php $showPostsInCategory = new WP_Query(); $showPostsInCategory->query('cat='. $category .'&showposts='. $number .'');  ?>

      <?php if ($showPostsInCategory->have_posts()) :?>
	  <?php while ($showPostsInCategory->have_posts()) : $showPostsInCategory->the_post(); ?>
      <?php $data = get_post_meta( $post->ID, 'key', true ); ?>
      <!-- Who Are We -->
      <div class="panel">
        <div class="panel-wrapper">
          <h2 class="title"><?php the_title_limit( 20, '...'); ?></h2>
            <div class="grid_10 tabtext">
              
			  <?php if ( get_post_meta($post->ID, 'info', true) ) { ?>
              <h4>'' <?php echo get_post_meta($post->ID, "info", $single = true); ?> ''</h4>
              <?php } ?>
              
              <p><?php content('100'); ?></p>
              
              <a href="<?php the_permalink() ?>" class="button-white"><img src="<?php bloginfo('template_directory'); ?>/image/b1.png"> Read More</a>
            </div>
            <div class="grid_6 tabimage">
              <?php if ( get_post_meta($post->ID, 'ribbon', true) ) { ?>
              <div id="ribbon-about">
              <img src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo get_post_meta($post->ID, "ribbon", $single = true); ?>&amp;h=77&amp;w=77" alt=""/>
              </div>
              <?php } ?>
              <?php if ( get_post_meta($post->ID, 'tabimage', true) ) { ?>
              <img src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo get_post_meta($post->ID, "tabimage", $single = true); ?>&amp;h=260&amp;w=340" alt="<?php the_title_limit( 30, ''); ?>"/>
              <?php } ?>
            </div>
        </div>
        <?php endwhile; endif; ?>
      </div>
      
    </div><!-- .coda-slider -->
  </div><!-- .coda-slider-wrapper -->
  </div>
</div></div></div></div>
<div style="clear:both;"></div>
<div style="margin-bottom:60px;"></div>
<!-- Tab Menu End -->
<?php endif; endif; ?>

<!-- Social Start -->
<!--
<div class="container_16">
  <div class="grid_16" id="social-back-two">
    <div id="sociallike"></div>
    <div class="grid_10 social-area">
      <h1><?php get_option_tree( 'social_title', '', 'true' ); ?></h1>
      <p><?php get_option_tree( 'social_subtitle', '', 'true' ); ?></p>
    </div>
    <div class="grid_6 social-icon">
      <ul>
        <?php get_option_tree( 'social_list', '', 'true' ); ?>
      </ul>
    </div>
  </div>
</div>
<div style="clear:both;"></div>
<div style="margin-bottom:160px;"></div>
-->
<!-- Social End -->

<?php get_footer(); ?>
