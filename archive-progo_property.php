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
<h4>Check Out What We Have To Offer</h4>
<p>Welcome to Montana Western Properties! This company was founded on the principles of hard work, honesty, and dedication to our clients. We understand the needs of our clients and continually strive to make sure our efforts secure for them their desired results.</p>
<p>Our area is such a wonderful part of the country and we are happy to share its' assets in hopes that you will want to visit and maybe make this your home too!</p>
<p>We combine years of experience and service to provide you with everything to enjoy all the opportunities our region has to offer. Many people look to our area for hunting and fishing. Many like to view wildlife and just take in all that nature has in store. Others are interested in hiking and exploring the vast wilderness areas we have here. Whatever the pursuit, we hope to show you what is available and help you find the property that is absolutely right for you!</p>
<?php } else {
echo '<h1>';
single_cat_title();
echo ' Property</h1>';
echo category_description();
}

get_sidebar('filter');
dynamic_sidebar('signup');
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
	global $wp_query;
	$wp_query->max_num_pages = 0;
	$newquery = array(
		'orderby' => 'menu_order',
		'order' => 'ASC'
	);
	if( isset($wp_query->query_vars[paged]) ) {
		$newquery[paged] = $wp_query->query_vars[paged];
	}
	
	if( isset($wp_query->query_vars[price]) ) {
		$newquery[meta_query] = array(
			array(
				'key' => '_progo_pricegroup',
				'value' => $wp_query->query_vars[price],
				'compare' => 'IN'
			)
		);
	}
	if( isset($wp_query->query_vars[acres]) ) {
		if( !isset($newquery[meta_query]) ) {
			$newquery[meta_query] = array();
		}
		$newquery[meta_query][] = array(
			'key' => '_progo_acregroup',
			'value' => $wp_query->query_vars[acres],
			'compare' => 'IN'
		);
	}
	
	if( isset($wp_query->query_vars[rec]) ) {
		$recs = $wp_query->query_vars[rec];
		$feats = '';
		for ( $i=0; $i < count($recs); $i++ ) {
			if($i>0) $feats .= '+';
			$term = get_term((int) $recs[$i], 'progo_recfeatures');
			$feats .= $term->slug;
		}
		$newquery['progo_recfeatures'] = $feats;
	}
	if( count($newquery) > 0 ) {
		$args = array_merge( $wp_query->query, $newquery );
		query_posts($args);
	}
	echo '<pre style="display:none">'. print_r($wp_query,true) .'</pre>';
	get_template_part( 'loop', 'properties' );
?>

			</div><!-- #main -->
<?php get_footer(); ?>
