<?php
/**
 * The Template for displaying all single posts.
 * * @package WordPress
 * @subpackage Smoke
 * @since Smoke 1.0
 */

get_header(); ?>

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
      <div class="pagenavi-title"><span class="pagenavi-h1"><?php get_option_tree( 'single_title', '', 'true' ); ?></span>
      <span class="pagenavi-h2">
      <?php get_option_tree( 'single_subtitle', '', 'true' ); ?>
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
    
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    
    <!-- #1 -->
    <div class="bloglist-panel">
      <div class="bloglist-title">
        <h1><?php the_title(); ?></h1>
        <h2><?php printf( esc_attr__( 'Posted by %s', '' ), get_the_author() ); ?> on <span style="color:#777777"><?php the_time('M j, Y') ?>,</span> Categories: <?php the_category(', ') ?> </h2>
      </div>
      <div class="bloglist-image">
        <div class="bloglist-comment"><div class="bloglist-comment-number"><?php comments_number(__('0'), __('1'), __('%')); ?></div></div>
        <?php if ( get_post_meta($post->ID, 'thumb', true) ) { ?>
        <img src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo get_post_meta($post->ID, "thumb", $single = true); ?>&amp;w=596" alt="<?php the_title_limit( 30, '...'); ?>"/>
        <?php } ?>
      </div>
      <div class="blog-post">
        <p><?php the_content(); ?></p>    

        <div style=" margin-top:26px;"></div>
      </div>
    </div>
    
    <div style="clear:both;"></div>
    
    <!-- Comment Title Start -->
      <div id="tabnavi-back-three">
          <div class="pagenavi-title"><span class="pagenavi-h1" style="margin-left:12px;">Comments</span><span class="pagenavi-h2"><?php
			printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), '' ),
			number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' );
			?></span></div>
      </div>
      <div style="clear:both;"></div>
    <!-- Comment Title End -->
    
    <!-- Comment List -->
    <div class=" bloglist-panel">
      <div class="comment-panel">
        <?php comments_template( '', true ); ?>
       
        
      </div>
    </div>
    <div style="clear:both"></div>
    <!-- Comment List And -->
     
    <!-- Page Navi -->
    <div class="page-navi">
      
    </div>
    
    <?php endwhile; // end of the loop. ?>
    
  </div>
  
  <!-- Start Sidebar -->
  <div class="grid_5">
    <div class="sidebar-panel" style="width:276px;">
      
      <!-- Sucrible -->
      <div class="sucrible">
        <div class="big-message-button" style="margin-bottom:0px;">
          <h1><a href="#"><?php get_option_tree( 'single_postbutton', '', 'true' ); ?></a></h1>
          <img src="<?php bloginfo('template_directory'); ?>/image/postfile.png" alt="">
        </div>
      </div>
      
      <!-- Readers -->
      <div class="sidebar-image">
        <div class="file">
          <ul>          
            <?php if ( get_post_meta($post->ID, 'thumb', true) ) { ?>           
            <li><a href="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo get_post_meta($post->ID, "thumb", $single = true); ?>&amp;w=900" rel="lightbox" title="<?php the_title_limit( 30, '...'); ?>" class="an7_thumb"><img src="<?php bloginfo('template_directory'); ?>/image/a1.png" alt=""></a></li>
            <?php } ?>
            
            <?php if ( get_post_meta($post->ID, 'pdf', true) ) { ?>
            <li><a href="<?php echo get_post_meta($post->ID, "pdf", $single = true); ?>" rel="lightbox[external 80% 660]" title="<?php echo get_post_meta($post->ID, "pdf", $single = true); ?>"><img src="<?php bloginfo('template_directory'); ?>/image/a2.png" alt=""></a></li>
            <?php } ?>
            
            
            <?php if ( get_post_meta($post->ID, 'web', true) ) { ?>
            <li><a href="<?php echo get_post_meta($post->ID, "web", $single = true); ?>" rel="lightbox[external 900 550]" title="<?php echo get_post_meta($post->ID, "web", $single = true); ?>"><img src="<?php bloginfo('template_directory'); ?>/image/a3.png" alt=""></a></li>
            <?php } ?>
            
            <?php if ( get_post_meta($post->ID, 'rar', true) ) { ?>
            <li><a href="<?php echo get_post_meta($post->ID, "rar", $single = true); ?>" rel="lightbox[external 900 550]" title="<?php echo get_post_meta($post->ID, "rar", $single = true); ?>"><img src="<?php bloginfo('template_directory'); ?>/image/a4.png" alt=""></a></li>
            <?php } ?>
            
            <?php if ( get_post_meta($post->ID, 'mp3', true) ) { ?>
            <li><a href="<?php echo get_post_meta($post->ID, "mp3", $single = true); ?>" rel="lightbox[audio 50% 20]" title="MP3 audio::<?php echo get_post_meta($post->ID, "mp3", $single = true); ?>"><img src="<?php bloginfo('template_directory'); ?>/image/a5.png" alt=""></a></li>
            <?php } ?>
            
            <?php if ( get_post_meta($post->ID, 'zip', true) ) { ?>
            <li><a href="<?php echo get_post_meta($post->ID, "zip", $single = true); ?>" rel="lightbox[external 82% 660]" title="<?php echo get_post_meta($post->ID, "zip", $single = true); ?>"><img src="<?php bloginfo('template_directory'); ?>/image/a6.png" alt=""></a></li>
            <?php } ?>
             
            <?php if ( get_post_meta($post->ID, 'video', true) ) { ?>
            <li><a href="<?php echo get_post_meta($post->ID, "video", $single = true); ?>" rel="lightbox[social]" title="<?php echo get_post_meta($post->ID, "video", $single = true); ?>"><img src="<?php bloginfo('template_directory'); ?>/image/a7.png" alt=""></a></li>
            <?php } ?>                  
          </ul>

        </div>
        <div style="clear:both;"></div>
      </div>
      <div style="clear:both;"></div>
      
      <!-- Tags -->
      <div class="tags" style="margin-top:10px;">
        <h2>Post Tag's</h2>
        <?php the_tags('', '', '<br />'); ?>
        <div style="clear:both;"></div> 
      </div>
      <div style="clear:both;"></div>
      
      <?php if ( get_post_meta($post->ID, 'buy-button', true) ) { ?>
      <!-- Download Button -->
      <div class="sucrible" style="margin-bottom:-14px;">
        <div class="big-message-button-blue">
          <h1><?php echo get_post_meta($post->ID, "buy-button", $single = true); ?></h1>
          <img src="<?php bloginfo('template_directory'); ?>/image/buy.png" alt="">
        </div>
      </div>
      <?php } ?>
      
    </div>
  </div>
</div>
<!-- Blog and Siderbar Start -->
<div style="clear:both;"></div>

<?php get_footer(); ?>
