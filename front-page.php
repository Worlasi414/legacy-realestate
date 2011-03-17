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
?>
<div id="left" class="grid_4">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<?php
the_content();
edit_post_link('Edit this entry.', '<p>', '</p>');
?>
<?php endwhile;
get_sidebar('filter');
dynamic_sidebar('signup');
?>
</div><!-- #left -->
<?php
global $wp_query;
$oldquery = $wp_query;
$wp_query = new WP_Query(array ( 'post_type' => 'progo_property', 'meta_key' => '_progo_featured', 'meta_value' => 'yes' ));
//echo '<pre style="display:none">'. print_r($wp_query,true) .'</pre>';
the_post();

$custom = get_post_meta($post->ID,'_progo_property');
$prop = $custom[0];
?>
<div id="pmedia" class="home">
<ul id="mtabs">
<li>Featured Listing</li>
<?php if( $prop[vimeo] != '' ) { ?>
<li><a href="<?php the_permalink(); ?>#Video">Watch The Video</a></li>
<?php } ?>
</ul>
<div class="mtab on" id="mfeat">
<?php the_post_thumbnail('top'); ?>
<div id="hframe"></div>
<div id="hnfo">
<h3 class="entry-title"><?php the_title(); ?></h3>
<?php
echo number_format( (float) $prop[acres] ) . ' acres';
$tags = get_the_terms($post->ID,'progo_recfeatures');
$tagcount = 3;
foreach ( $tags as $t ) {
	if($tagcount > 0) {
		echo ' | <a href="'. get_bloginfo('url') .'/properties/features/'. $t->slug .'/" title="View '. esc_attr($t->name) .' Properties">'. esc_html($t->name) .'</a>';
		if($tagcount-- == 1) echo '...';
	}
} ?>
<a href="<?php the_permalink(); ?>" class="view" title="<?php printf( esc_attr__( 'View %s', 'progo' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">VIEW</a>
</div>
</div>
</div>
<div id="main" role="main" class="props home">
<h2 class="ptitle">Featured Properties</h2>
<?php
get_template_part( 'loop', 'properties' );
$wp_query = $oldquery;
?>
</div><!-- #main -->
<?php get_footer(); ?>
