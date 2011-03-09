<?php
/**
 * Template Name: Meet the Family
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
<?php the_content(); ?>
<br />
<?php
$args = array('post_type' => 'progo_family', 'numberposts' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' );
$fam = get_posts($args);
foreach($fam as $t) {
	echo '<div class="fam"><a name="'. $t->post_name .'"></a>';
	echo get_the_post_thumbnail($t->ID, 'medium', array('class'=>'alignleft') );
	echo '<h4>'. strtoupper(strip_tags($t->post_title));
	if(current_user_can('edit_pages')) {
		echo ' <small style="font-size:11px; font-weight:normal">(<a href="'. get_bloginfo('url') .'/wp-admin/post.php?post='. $t->ID .'&action=edit">Edit Bio</a>)</small>';
	}
	echo '</h4>';
	echo apply_filters('the_content',$t->post_content);
	
	echo '</div>';
}
//edit_post_link('Edit this entry.', '<p>', '</p>');
?>
</div><!-- .entry -->
</div><!-- #post-## -->
<?php endwhile; ?>
</div><!-- #main -->
<?php get_sidebar('blog'); ?>
<?php get_footer(); ?>
