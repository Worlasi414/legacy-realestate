<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
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
edit_post_link('Edit this entry.', '<p>', '</p>');
?>
</div><!-- .entry -->
</div><!-- #post-## -->
<?php endwhile; ?>
</div><!-- #main -->
<?php get_sidebar(); ?>
<!-- #THISISTHEDEFAULTPAGE -->
<?php get_footer(); ?>
