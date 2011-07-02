<?php
/**
 * The template for displaying Search Results pages.
 * * @package WordPress
 * @subpackage Smoke
 * @since Smoke 1.0
 */

get_header(); ?>

<?php if (have_posts()) : ?>

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
      <div class="pagenavi-title"><span class="pagenavi-h1"><?php printf( __( 'Search Results for: %s', '' ), '<span>' . get_search_query() . '</span>' ); ?></span>
      <span class="pagenavi-h2">
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
  <div class="grid_11">
    


	
	<?php while (have_posts()) : the_post(); ?>
    
    <!-- #1 -->
    <div class="bloglist-panel">
      <div class="bloglist-title">
        <h1><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
        <h2><?php printf( esc_attr__( 'Posted by %s', '' ), get_the_author() ); ?> on <span style="color:#777777"><?php the_time('M j, Y') ?>,</span> Categories: <?php the_category(', ') ?> </h2>
      </div>
      <div class="bloglist-image">
        
        <?php if ( get_post_meta($post->ID, 'ribbon', true) ) { ?>
        <div class="bloglist-ribbon">
        <img src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo get_post_meta($post->ID, "ribbon", $single = true); ?>&amp;h=77&amp;w=77" alt=""/>
        </div>
		<?php } ?>
        
        <div class="bloglist-comment"><div class="bloglist-comment-number"><?php comments_number(__('0'), __('1'), __('%')); ?></div></div>
        <?php if ( get_post_meta($post->ID, 'thumb', true) ) { ?>
        <img src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo get_post_meta($post->ID, "thumb", $single = true); ?>&amp;h=190&amp;w=596" alt="<?php the_title_limit( 30, '...'); ?>"/>
        <?php } ?>
      </div>
      <div class="bloglist-post">
        <p><?php content('100'); ?></p>
        <a href="<?php the_permalink() ?>" class="button-white"><img src="<?php bloginfo('template_directory'); ?>/image/b1.png"> <?php get_option_tree( 'blog_buttonname', '', 'true' ); ?></a>
        <div style=" margin-top:26px;"></div>
      </div>
    </div>
    
    <?php endwhile; ?>


    <div style="clear:both;"></div>
    
    <!-- Page Navi -->
    <div class="page-navi">
      <?php posts_nav_link( ' ', '<img src="' . get_bloginfo('stylesheet_directory') . '/image/b2.png" />', '<img src="' . get_bloginfo('stylesheet_directory') . '/image/b1.png" />' ); ?>
    </div>    
    
  </div>
  
  <!-- Start Sidebar -->
  <div class="grid_5">
    <div class="sidebar-panel">
      
      <!-- Sucrible -->
      <div class="sucrible">
        <div class="big-message-button" style="margin-bottom:0px;">
          <h1><a href="<?php get_option_tree( 'sub_link', '', 'true' ); ?>"><?php get_option_tree( 'sub_title', '', 'true' ); ?></a></h1>
          <img src="<?php bloginfo('template_directory'); ?>/image/subrss.png" alt="">
        </div>
      </div>
      
      <!-- Readers -->
      <div class="sidebar-image">
        <div class="readers">
          <ul style="margin-bottom:6px;">
            <li><img src="<?php bloginfo('template_directory'); ?>/image/mini-rss.png" alt=""> <?php echo stcounter('feedburner') ?> Readers</li>
            <li><img src="<?php bloginfo('template_directory'); ?>/image/mini-twitter.png" alt=""> <?php echo stcounter('twitter') ?> Followers</li>
          </ul>
        </div>
        <div style="clear:both;"></div>
      </div>
      <div style="clear:both;"></div>
      
      <!-- Categories -->
      <div class="categories">
        <?php dynamic_sidebar(1); ?>
      </div>
      
      <!-- Tags -->
      <div class="tags">
        <h2>Populer Tag's</h2>
        <?php wp_tag_cloud('smallest=10&largest=10&number=25&orderby=name'); ?>
      </div>
      <div style="clear:both;"></div>
      
      <!-- Support Button -->
      <div class="sucrible" style="margin-bottom:-18px; margin-top:20px;">
        <?php dynamic_sidebar(2); ?>
      </div>
      
      
    </div>
  </div>
</div>

<?php else : ?>
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
      <div class="pagenavi-title"><span class="pagenavi-h1"><?php printf( __( 'Search Results for: %s', '' ), '<span>' . get_search_query() . '</span>' ); ?></span>
      <span class="pagenavi-h2">
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
  <div class="bloglist-panel" style="width:100%; margin-top:0px;">
  <div class="bloglist-title">
  <h1 style="text-align:center; font-size:140px; margin-top:50px; margin-bottom:20px;">UPPS!</h1>
  <h2 style="text-align:center;"><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', '' ); ?></h2>
  </div>
  </div>
  </div>
  </div>
    
<?php endif; ?>
    
<!-- Blog and Siderbar Start -->
<div style="clear:both;"></div>
<?php get_footer(); ?>
