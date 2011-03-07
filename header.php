<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package ProGo
 * @subpackage RealEstate
 * @since RealEstate 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="wrap" class="container_12">
	<div id="page" class="container_12">
        <div id="hdr"><a name="top"></a>
            <a href="<?php bloginfo('url'); ?>/" id="logo"><?php esc_html_e( get_bloginfo( 'name' ) ); ?></a>
            <a href="<?php bloginfo('url'); ?>/" id="book"><?php esc_html_e( get_bloginfo( 'name' ) ); ?></a>
<?php  wp_nav_menu( array( 'container' => false, 'menu_id' => 'topnav', 'theme_location' => 'topnav' ) );
if(is_front_page()) { ?>
<div id="htext">
<p>The journey you're about to take with Big Mike and my family is not entirely fictitious at all. Learn how our family made over $150 million buying investment- grade life insurance, and what you need to know about the greatest asset you don't already own, don't own enough of, or own incorrectly.</p>
<h4><a href="#download-investors-4"><strong>Download a FREE Chapter</strong></a></h4>
</div>
<div id="hbtns">
<a href="<?php echo get_permalink(4); ?>" class="btn">Buy The Book</a><a href="<?php echo get_permalink(4); ?>#download-book" class="btn">Download The Book</a><a href="<?php echo get_permalink(4); ?>#silver-package" class="btn bulk">Buy In Bulk</a>
</div>
<?php } else { ?>
			<a href="<?php echo get_permalink(4); ?>" class="btn now">Buy Now</a>
            <?php } ?>
        </div>