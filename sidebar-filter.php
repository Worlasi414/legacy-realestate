<?php
/**
 * filter form
 *
 * @package ProGo
 * @subpackage RealEstate
 * @since RealEstate 1.0
 */
?>
<div id="filter">
<ul id="ftabs">
<li class="first on"><a href="#fprops">Properties For Sale</a></li>
<li><a href="#fsearch">Options</a></li>
</ul>
<div class="ftab on" id="fprops">
<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'filtercats' ) ); ?>
<a href="<?php bloginfo('url'); ?>/land-in-montana/" class="allprops">View All Properties</a>
</div>
<div class="ftab" id="fsearch">
<?php global $wp_query; ?>
<form action="<?php
if ( is_front_page() ) {
	echo get_bloginfo('url') .'/land-in-montana/';
} else {
	echo "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
} ?>" method="get">
<div class="col"><div class="title">Price</div>
<?php
$prices = array(
	1 => 'Less than $200K',
	2 => '$201K to $400K',
	3 => '$401K to $600K',
	4 => '$601K to $1 Million',
	5 => '$1 Million Plus'
);
foreach ( $prices as $k => $v ) {
	echo '<label for="price['. absint($k) .']"><input type="checkbox" name="price[]" value="'. absint($k) .'"';
	if( isset($wp_query->query_vars[price]) && in_array($k, $wp_query->query_vars[price]) ) {
		echo ' checked="checked"';
	}
	echo ' /> '. esc_html($v) .'</label>';
}
?>
</div>
<div class="col"><div class="title">Acreage</div>
<?php
$acres = array(
	1 => '10 Acres or less',
	2 => '11 to 50 Acres',
	3 => '51 to 100 Acres',
	4 => '101 to 500 Acres',
	5 => '501 or more Acres'
);
foreach ( $acres as $k => $v ) {
	echo '<label for="acres['. absint($k) .']"><input type="checkbox" name="acres[]" value="'. absint($k) .'"';
	if( isset($wp_query->query_vars[acres]) && in_array($k, $wp_query->query_vars[acres]) ) {
		echo ' checked="checked"';
	}
	echo ' /> '. esc_html($v) .'</label>';
}
?>
</div>
<div class="title">Recreational Features</div>
<?php
$feats = get_terms( 'progo_recfeatures' ); // yay cats
$count = 0;
echo '<div class="col">';
		foreach ( $feats as $f ) {
			if($count++ == 7) echo '</div><div class="col">';
			echo '<label for="rec['. absint($f->term_id) .']"><input type="checkbox" name="rec[]" value="'. absint($f->term_id) .'"';
			if( isset($wp_query->query_vars[rec]) && in_array($f->term_id, $wp_query->query_vars[rec]) ) {
				echo ' checked="checked"';
			}
			echo ' /> '. esc_html($f->name) .'</label>';
		}
		echo '</div>';
?>
<input type="submit" value="Search" class="btn67" />
</form>
</div>
</div>