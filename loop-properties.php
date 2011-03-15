<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package ProGo
 * @subpackage RealEstate
 * @since RealEstate 1.0
 */
?>

<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="listing error404 not-found">
		<h3 class="entry-title"><?php _e( 'No Results', 'progo' ); ?></h3>
		<div class="entry">
			<p><?php _e( 'Apologies, but no results were found for the requested archive.', 'progo' ); ?></p>
		</div><!-- .entry -->
	</div><!-- #post-0 -->
<?php endif; ?>

<?php while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" class="listing">
			<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'View %s', 'progo' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_post_thumbnail('post-thumbnail', array('class'=>'thm alignleft')); the_title(); ?></a></h3>
			<?php the_excerpt(); ?>
            <a href="<?php the_permalink(); ?>" class="ilnk" title="<?php printf( esc_attr__( 'View %s', 'progo' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">Property Info</a>
            <a href="<?php the_permalink(); ?>" class="view" title="<?php printf( esc_attr__( 'View %s', 'progo' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">VIEW</a>
<ul class="rec">
<?php
$features = get_the_terms($post->ID,'progo_recfeatures');
foreach ( $features as $f ) {
	echo '<li id="f'. $f->term_id .'"><a title="View '. esc_attr($f->name) .' Properties" href="'. get_bloginfo('url') .'/properties/features/'. $f->slug .'/">'. esc_html($f->name) .'</a></li>';
}
?>
</ul>
		</div><!-- #post-## -->
<?php endwhile; // End the loop. Whew. ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				<div id="nav-below" class="navigation">
					<div class="back"><?php previous_posts_link( '&laquo; PREVIOUS PAGE' ); ?></div>
					<div class="next"><?php next_posts_link( 'NEXT PAGE &raquo;' ); ?></div>
				</div><!-- #nav-below -->
<?php endif; ?>
