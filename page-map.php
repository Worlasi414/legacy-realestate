<?php
/**
 * Template Name: Ranches By Location
 *
 * @package ProGo
 * @subpackage RealEstate
 * @since RealEstate 1.0
 */

get_header();
$options = get_option('progo_options');
?>
<div id="main" role="main" class="grid_7">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="grid_7 entry">
<?php
the_content();
?>
<p id="map"><img src="<?php bloginfo('template_url'); ?>/images/map/off.png" alt="Interactive Map" title="Interactive Map" width="535" height="357" border="0" usemap="#mwpMap" /></p>
<map name="mwpMap" id="mwpMap">
  <area shape="poly" coords="8,10,164,18,164,45,149,48,147,58,117,58,132,78,125,103,112,103,112,123,117,130,117,148,92,168,97,198,105,203,67,238,57,231,57,165,15,113,2,71" href="/land-in-montana/glacier-country/" alt="Glacier Country" onmouseover="changeMap(1);" onmouseout="changeMap(0);" />
  <area shape="poly" coords="83,225,128,310,192,301,198,223,182,216,200,193,207,193,210,180,195,153,178,143,170,111,132,78,125,103,112,103,112,123,117,130,117,148,92,168,97,198,105,203" href="/land-in-montana/gold-west-country/" alt="Gold West Country" onmouseover="changeMap(2);" onmouseout="changeMap(0);" />
  <area shape="poly" coords="328,18,320,78,298,88,307,103,342,108,342,148,425,148,425,135,450,125,450,103,480,101,483,113,515,111,505,11" href="#missouri" alt="/land-in-montana/missouri-river-country/" onmouseover="changeMap(3);" onmouseout="changeMap(0);" />
  <area shape="poly" coords="164,18,164,45,149,48,147,58,117,58,132,78,170,111,178,143,195,153,210,180,207,193,282,190,285,158,345,158,342,148,342,108,307,103,298,88,320,78,328,18" href="/land-in-montana/russell-country/" alt="Russell Country" onmouseover="changeMap(4);" onmouseout="changeMap(0);" />
  <area shape="poly" coords="270,190,275,203,300,198,313,253,335,253,323,273,215,276,212,308,195,291,198,223,182,216,200,193,207,193" href="/land-in-montana/yellowstone-country/" alt="Yellowstone Country" onmouseover="changeMap(5);" onmouseout="changeMap(0);" />
  <area shape="poly" coords="515,111,483,113,480,101,450,103,450,125,425,135,425,148,342,148,345,158,285,158,282,190,270,190,275,203,300,198,313,253,335,253,323,273,525,263" href="/land-in-montana/custer-country/" alt="Custer Country" onmouseover="changeMap(6);" onmouseout="changeMap(0);" />
</map>
<script type="text/javascript">
function changeMap(num) {
	jQuery('#map img').attr('src','<?php bloginfo('template_url'); ?>/images/map/' + ((num==0) ? 'off' : num) + '.png');
}
</script>
</div><!-- .entry -->
</div><!-- #post-## -->
<?php endwhile; ?>
</div><!-- #main -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>