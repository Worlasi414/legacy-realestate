<?php
/**
 * The Template for displaying single Properties.
 *
 * @package ProGo
 * @subpackage RealEstate
 * @since RealEstate 1.0
 */

get_header();

if ( have_posts() ) while ( have_posts() ) : the_post();

$custom = get_post_meta($post->ID,'_progo_property');
$prop = $custom[0];
?>

<div id="nav-above" class="navigation">
    <div class="back"><?php previous_post_link( '%link', '&laquo; PREVIOUS PROPERTY' ); ?></div>
    <div class="next"><?php next_post_link( '%link', 'NEXT PROPERTY &raquo;'); ?></div>
</div><!-- #nav-above -->
<div id="main" role="main" class="prop">
<?php the_content();?>
<div id="pinfo">
<h3><?php the_title(); ?></h3>
<table>
<tr><td width="78"><strong>Location :</strong></td><td><?php
$tags = get_the_terms($post->ID,'progo_locations');
$onedone = false;
foreach ( $tags as $t ) {
	if($onedone) echo ', ';
	esc_html_e($t->name);
}
?></td></tr>
<tr><td><strong>Price :</strong></td><td>$<?php echo number_format( (float) $prop[price] ); ?></td></tr>
<tr><td><strong>Acreage :</strong></td><td><?php echo number_format( (float) $prop[acres] ); ?> deeded acres</td></tr>
</table>
<ul class="rec">
<?php
$features = get_the_terms($post->ID,'progo_recfeatures');
foreach ( $features as $f ) {
	echo '<li id="f'. $f->term_id .'"><a title="'. esc_attr($f->name) .'">'. esc_html($f->name) .'</a></li>';
}
?>
</ul>
<ul>
<li><?php
echo str_replace('<br />','</li><li>',nl2br(wp_kses($prop[bullets],array())));
?></li>
</ul>
<div id="plnx">
<a href="<?php echo get_permalink(41); ?>">Email us about this property</a>
</div>
</div>
<div id="pmedia">
<ul id="mtabs">
<li id="mt0"><a href="#mphotos" title="Photos">View Photos</a></li>
<?php if ( strlen( $prop[vimeo] ) > 0 ) { ?>
<li id="mt1"><a href="#mvideo" title="Video">MWP Video</a></li>
<?php } ?>
<li id="mt2"><a href="#map" title="Location">Location</a></li>
</ul>
<div class="mtab on" id="mphotos">
<?php if ( absint($prop[gall]) > 0 ) {
	echo nggShowGallery($prop[gall], 'pmedia');
} else {
	the_post_thumbnail();
} ?><div id="mframe"></div>
</div>
<?php if ( strlen( $prop[vimeo] ) > 0 ) { ?>
<div class="mtab" id="mvideo">
<?php $vidcode = '[embed width="250" height="165"]'.$prop[vimeo].'[/embed]';
global $wp_embed; echo $wp_embed->autoembed($prop[vimeo]); ?>
</div>
<?php } ?>
<div class="mtab" id="map">
<?php
$loc = $prop[loc][addr] .', '. $prop[loc][city] .', '. $prop[loc][state] .' '. $prop[loc][zip];
echo do_shortcode('[gmap a="'. $loc .'" w=589 h=373 z=11]');
?>
</div>
</div>
</div><!-- #main -->
<?php 
endwhile; // end of the loop.  ?>
<?php get_footer(); ?>
