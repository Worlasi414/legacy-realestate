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
<p id="map"><img src="<?php bloginfo('template_url'); ?>/images/map.png" alt="Interactive Map Coming Soon" title="Interactive Map Coming Soon" /></p>
</div><!-- .entry -->
</div><!-- #post-## -->
<?php endwhile; ?>
</div><!-- #main -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>