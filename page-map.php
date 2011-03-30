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
<p id="map"><img src="<?php bloginfo('template_url'); ?>/images/map.png" alt="Interactive Map Coming Soon" title="Interactive Map Coming Soon" width="535" height="363" border="0" usemap="#mwpMap" /></p>
<map name="mwpMap" id="mwpMap">
  <area shape="poly" coords="82,227,102,205,89,172,112,149,109,104,129,82,144,100,169,112,204,187,184,212,197,225,191,299,129,312,99,277" href="../land-in-montana/gold-west-country/" alt="Gold West Country" />
</map>
</div><!-- .entry -->
</div><!-- #post-## -->
<?php endwhile; ?>
</div><!-- #main -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>