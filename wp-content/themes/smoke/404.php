<?php
/**
 * The template for displaying 404 pages (Not Found).
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
  <h1 style="text-align:center; font-size:140px; margin-top:50px; margin-bottom:20px;">404</h1>
  <h2 style="text-align:center;"><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', '' ); ?></h2>
  </div>
  </div>
  </div>
  </div>
  

	<script type="text/javascript">
		// focus on search field after it has loaded
		document.getElementById('s') && document.getElementById('s').focus();
	</script>