<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package ProGo
 * @subpackage RealEstate
 * @since RealEstate 1.0
 */
?>
<div id="side" class="grid_4">
<?php
/* When we call the dynamic_sidebar() function, it'll spit out
 * the widgets for that widget area. If it instead returns false,
 * then the sidebar simply doesn't exist, so we'll hard-code in
 * some default sidebar stuff just in case.
 */

if ( is_page(41) ) {
dynamic_sidebar( 'contact' );
} else {
	
	global $post;
	$parentid = $post->ID;
	if(count($post->ancestors) > 0) {
		$parentid = $post->ancestors[0];
	}
	//now see if we have any sub menu
	$children = get_pages('child_of='. $parentid);
	if(count($children) > 0) { ?>
		<div class="block"><ul class="snav"><?php
		foreach($children as $p) {
			echo '<li'. ($p->ID == $post->ID ? ' class="active"': '') .'><a href="'. get_permalink($p->ID) .'">'. $p->post_title .'</a></li>';
		} ?></ul></div><?php
	}
}

dynamic_sidebar( 'sidebar' );
?>
</div>