<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 * * @package WordPress
 * @subpackage Smoke
 * @since Smoke 1.0
 */

get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<!-- Breadcump Start -->
<div id="frame-slide-two"></div>
<div style=" clear:both;"></div>
<div class="container_16">
  <div class="grid_16 breadcump">
    <?php if(function_exists('breadcrumbs')) { breadcrumbs(); } else { bloginfo('name'); echo '(breadcrumbs are unavailable)'; } ?>
  </div>
</div>
<!-- Breadcump End -->

<!-- Page Top Navi Start -->
<div class="container_16">
  <div class="grid_16" id="tabnavi-back-two">
    <div class="grid_11">
      <div class="pagenavi-title"><span class="pagenavi-h1"><?php the_title(); ?></span>
      <span class="pagenavi-h2">
      <?php if ( get_post_meta($post->ID, 'title', true) ) { ?>
             <?php echo get_post_meta($post->ID, "title", $single = true); ?>
              <?php } ?>
      </span>
      </div>
    </div>
    <div class="grid_4">
      <form role="search" method="get" id="searchform" class="pagenavi-search" action="<?php echo home_url( '/' ); ?>">
      <input type="text" name="s" id="s" value="Search.." onfocus="if(this.value=='Search..')this.value='';" onblur=	"if(this.value=='')this.value='Search..';"/>
      <input type="submit" id="searchsubmit" value=">" />
      </form>
    </div>
  </div>
</div>
<div style="clear:both;"></div>
<div style="margin-bottom:166px;"></div>
<!-- Page Top Navi End -->

<!-- Blog and Siderbar Start -->
<div class="container_16">
  
  <!-- Start Blog List -->
  <div class="grid_16">
    
    <!-- #1 -->
    <div class="bloglist-panel">
      <div class="bloglist-title" style="border-bottom:1px solid #2b2c31;">
        <h2><?php printf( esc_attr__( 'Posted by %s', '' ), get_the_author() ); ?> on <span style="color:#777777"><?php the_time('M j, Y') ?>,</span> Categories: <?php the_category(', ') ?></h2>
      </div>
      
      <div class="blog-post">
        <p style="margin-top:-14px;"><?php the_content(); ?></p>
        
 
           

        <div style=" margin-top:26px;"></div>
      </div>
    </div>
    
  </div>
  
</div>
<!-- Blog and Siderbar Start -->
<div style="clear:both;"></div>
<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>
