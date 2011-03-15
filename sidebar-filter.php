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
<li class="first on"><a href="#fprops">Ranches For Sale</a></li>
<li><a href="#fsearch">Filter Results</a></li>
</ul>
<div class="ftab on" id="fprops">
<ul>
<?php
$feats = array(
	'trout-fishing' => 'Fly-Fishing Property',
	'montana-big-game-hunting' => 'Hunting Property',
	'mountain-property' => 'Mountain Property',
	'waterfront' => 'Waterfront Property',
	'residential' => 'Residential Property',
	'farm-land-for-sale' => 'Cattle Ranch',
	'horse-property-for-sale' => 'Horse Property'
);
foreach ( $feats as $k => $v ) {
	echo '<li><a href="'. get_bloginfo('url') .'/montana-properties-for-sale/features/'. $k .'/">'. $v .'</a></li>';
}
?>
<li><a href="<?php bloginfo('url'); ?>/properties/" class="all">View All Properties</a></li>
</ul>
</div>
<div class="ftab" id="fsearch">
<?php global $wp_query; ?>
<form action="<?php
if ( is_front_page() ) {
	echo get_bloginfo('url') .'/montana-properties-for-sale/';
} else {
	echo "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
} ?>" method="get">
<div class="col"><div class="title">Price</div>
<?php
$prices = array(
	1 => '&lt; 1 million',
	2 => '1-3 million',
	3 => '3-5 million',
	4 => '5-10 million',
	5 => '10+ million'
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
	1 => '&lt; 10k acres',
	2 => '10k - 20k acres',
	3 => '20k - 40k acres',
	4 => '40k - 80k acres',
	5 => '80k - 100k+ acres'
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
<input type="submit" value="Search" />
</form>
</div>
</div>