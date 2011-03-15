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
<li><a href="#fsearch" title="Coming Soon">Filter Results</a></li>
</ul>
<div class="ftab" id="fprops">
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
	echo '<li><a href="'. get_bloginfo('url') .'/properties/features/'. $k .'/">'. $v .'</a></li>';
}
?>
<li><a href="<?php bloginfo('url'); ?>/properties/" class="all">View All Properties</a></li>
</ul>
</div>
</div>