<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package ProGo
 * @subpackage RealEstate
 * @since RealEstate 1.0
 */

get_header(); ?>
<div id="left" class="grid_4">
<?php
global $wp_query;
if ( count($wp_query->query) == 1 ) { ?>
<h1>Montana Ranches For Sale</h1>
<h4>KEYWORD HEADLINE GOES RIGHT HERE</h4>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras fringilla nisi eu nulla ultrices vitae adipiscing lorem adipiscing. Donec orci arcu, consequat eget hendrerit quis, viverra sit amet ante. Donec vel viverra quam. Quisque at arcu ac urna ornare cursus a et turpis. Quisque nunc ante, elementum at congue tincidunt, tincidunt ac elit. Donec a massa nulla, eget pretium massa. Morbi sem odio, facilisis ut dictum quis, malesuada convallis tortor. Ut lobortis turpis eu quam viverra id fringilla lorem congue. Aliquam quis velit turpis. Morbi ullamcorper enim ut sapien tempus porttitor.</p>
<p>Morbi consequat tincidunt sagittis. Nulla non risus sit amet. Morbi consequat tincidunt sagittis. Nulla non risus sit amet.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras fringilla nisi eu nulla ultrices vitae adipiscing lorem adipiscing. Donec orci arcu, consequat eget hendrerit quis, viverra sit amet ante. Donec vel viverra quam. Quisque at arcu ac urna ornare cursus a et turpis. Quisque nunc ante, elementum at congue tincidunt, tincidunt ac elit. Donec a massa nulla, eget pretium massa. Morbi sem odio, facilisis ut dictum quis, malesuada convallis tortor. Ut lobortis turpis eu quam viverra id fringilla lorem congue. Aliquam quis velit turpis. Morbi ullamcorper enim ut sapien tempus porttitor.</p>
<p>Morbi consequat tincidunt sagittis. Nulla non risus sit amet. Morbi consequat tincidunt sagittis. Nulla non risus sit amet.</p>
<?php } else {
echo '<h1>';
single_cat_title();
echo ' Property</h1>';
echo category_description();
}
?>
</div>
			<div id="main" role="main" class="props">
<?php
	echo '<h2 class="ptitle">';
	single_cat_title();
	echo ' Property For Sale</h2>';
	/* Run the loop for the archives page to output the posts.
	 * If you want to overload this in a child theme then include a file
	 * called loop-archives.php and that will be used instead.
	 */
	 get_template_part( 'loop', 'properties' );
?>

			</div><!-- #main -->
<?php get_footer(); ?>
