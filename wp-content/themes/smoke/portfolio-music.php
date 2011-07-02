<?php /** Template Name: Portfolio Music */ get_header(); ?>
<script src="<?php bloginfo('template_directory'); ?>/js/portfolio.js"></script>
<script type="text/javascript">
$(document).ready(function(){
 
	$("a.switch_thumb").toggle(function(){
	  $(this).addClass("swap"); 
	  $("ul.display").fadeOut("fast", function() {
	  	$(this).fadeIn("fast").addClass("thumb_view"); 
		 });
	  }, function () {
      $(this).removeClass("swap");
	  $("ul.display").fadeOut("fast", function() {
	  	$(this).fadeIn("fast").removeClass("thumb_view");
		});
	}); 

});
</script>
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

<!-- Portfolio Slide Start -->
<div id="portfolio-slide-back"></div>
<div class="container_16">
  <div class="grid_16"> 
    <div id="ps_slider" class="ps_slider" style="margin-top:32px;">
      <a class="prev disabled"></a>
      <a class="next disabled"></a>
      <div id="ps_albums">
          
		  <?php 
          $category = get_option_tree('music_slide_categories');
          $number = get_option_tree('music_slide_show');
          ?>         
          <?php if (have_posts()) : ?>
          <?php query_posts("cat=$category&showposts=$number&somecat&paged=$paged"); ?>
          <?php while (have_posts()) : the_post(); ?>
          
          <?php if ( get_post_meta($post->ID, 'thumb', true) ) { ?>
          <div class="ps_album" style="opacity:0;"><a href="<?php the_permalink() ?>">
          <img src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo get_post_meta($post->ID, "thumb", $single = true); ?>&amp;h=258&amp;w=146" alt=""/>
          </a><div class="ps_desc"><h2><?php the_title_limit( 30, '...'); ?></h2></div></div>
          <?php } ?>         
          <?php endwhile; ?>
          
      </div>	
    </div>
  </div>
</div>
<div style="clear:both"></div>
<div style="margin-bottom:20px;"></div>
<!-- Portfolio Slide End -->

<!-- Social Start -->
<div class="container_16">
  <div class="grid_16" id="social-back">
    <div class="grid_10 social-area">
      <h1 style="margin-left:-60px;"><?php get_option_tree( 'music_portfoliotitle', '', 'true' ); ?></h1>
      <p style="margin-left:-61px;"><?php get_option_tree( 'music_portfoliosubtitle', '', 'true' ); ?></p>
    </div>
    <div class="grid_6 social-icon"></div>
  </div>
</div>
<div style="clear:both;"></div>
<div style="margin-bottom:112px;"></div>
<!-- Social End -->

<!-- Advert Start -->
<div class="container_16">
  <div class="grid_16" style="width:970px;">
    <a href="#" class="switch_thumb">Switch Thumb</a> 
      
      <ul class="display">
	  <?php 
          $category = get_option_tree('music_portfoliocategories');
          $number = get_option_tree('music_portfolioshow');
          ?>         

          <?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; query_posts("cat=$category&showposts=$number&somecat&paged=$paged"); ?>
          <?php while (have_posts()) : the_post(); ?>      
          <?php if ( get_post_meta($post->ID, 'thumb', true) ) { ?>    
      
      <li>
        <div class="content_block">
          <a href="<?php echo get_post_meta($post->ID, "thumb", $single = true); ?>" rel="lightbox" title="<?php the_title_limit( 30, '...'); ?>" class="an7_thumb"><img src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo get_post_meta($post->ID, "thumb", $single = true); ?>&amp;h=100&amp;w=146" alt="" /></a>
          <h2><a href="<?php the_permalink() ?>"><?php the_title_limit( 30, '...'); ?></a></h2>
          <p><?php content('10'); ?></p>
          <div class="mini-button-portfolio"><a href="<?php the_permalink() ?>" class="mini-red" style="margin-right:4px;">more</a> <?php if ( get_post_meta($post->ID, 'mp3', true) ) { ?><a href="<?php echo get_post_meta($post->ID, "mp3", $single = true); ?>" rel="lightbox[audio 50% 20]" title="<?php the_title_limit( 30, '...'); ?>" class="an7_thumb mini-yellow">listen</a><?php } ?> </div>
          <div style="clear:both;"></div>
        </div>
      </li>
      
      <?php } ?>      
      <?php endwhile; ?>
      </ul>
          

    </div>
    <div style="clear:both;"></div>
    <div style="margin-bottom:4px;"></div>
    
    <!-- Page Navi -->
    <div class="page-navi" style="margin:10px 0px 0px 14px;">
      <?php posts_nav_link( ' ', '<img src="' . get_bloginfo('stylesheet_directory') . '/image/b2.png" />', '<img src="' . get_bloginfo('stylesheet_directory') . '/image/b1.png" />' ); ?>
    </div>
    <?php else : ?>
    <?php endif; ?>   

</div>
<div style="clear:both;"></div>
<div style="margin-bottom:32px;"></div>
<!-- Advert End -->

<div style="clear:both;"></div>

<?php get_footer(); ?>