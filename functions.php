<?php
/**
 * @package ProGo
 * @subpackage RealEstate
 * @since RealEstate 1.0
 *
 * Defines all the functions, actions, filters, widgets, etc., for ProGo Themes' RealEstate theme.
 *
 * Some actions for Child Themes to hook in to are:
 * progo_frontend_scripts, progo_frontend_styles
 *
 * Some overwriteable functions ( wrapped by "if(!function_exists(..." ) are:
 * progo_posted_on, progo_posted_in, progo_gateway_cleanup, progo_prepare_transaction_results,
 * progo_admin_menu_cleanup, progo_custom_login_logo, progo_custom_login_url, progo_metabox_cleanup ...
 *
 * Most Action / Filters hooks are set in the progo_setup function, below. overwriting that could cause quite a few things to go wrong.
 */

$content_width = 581;

/** Tell WordPress to run progo_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'progo_setup' );

if ( ! function_exists( 'progo_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_theme_support( 'post-thumbnails' ) To add support for post thumbnails.
 *
 * @since RealEstate 1.0
 */
function progo_setup() {
	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style( 'editor-style.css' );
	
	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'topnav' => 'Top Navigation'
	) );
	
	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'post-thumbnail', 197, 142, true );
	add_image_size( 'top', 589, 274, true );
	
	// add custom actions
	add_action( 'admin_init', 'progo_admin_init' );
	add_action( 'widgets_init', 'progo_realestate_widgets' );
	add_action( 'admin_menu', 'progo_admin_menu_cleanup' );
	add_action( 'login_head', 'progo_custom_login_logo' );
	add_action( 'login_headerurl', 'progo_custom_login_url' );
	add_action('wp_print_scripts', 'progo_add_scripts');
	add_action('wp_print_styles', 'progo_add_styles');
	add_action( 'admin_notices', 'progo_admin_notices' );
	add_action('save_post', 'progo_property_save_meta');
	add_action("manage_posts_custom_column",  "progo_realestate_columns");
	
	// add custom filters
	add_filter( 'default_content', 'progo_set_default_body' );
	add_filter( 'site_transient_update_themes', 'progo_update_check' );
	add_filter('body_class','progo_bodyclasses', 100 );
	add_filter( 'post_type_link', 'progo_realestate_links', 10, 3 );
	add_filter("manage_edit-progo_property_columns", "progo_property_edit_columns"); 
	add_filter( 'query_vars', 'progo_queryvars' );
	add_filter( 'pre_get_posts', 'progo_realestate_get_posts' );
	add_filter('the_content', 'progo_realestate_content_filter');
	add_filter('pre_get_posts','progo_searchposts');
	add_filter('get_the_excerpt','progo_excerpt');
	add_filter('post_limits', 'progo_listings_limit');
	
	if ( !is_admin() ) {
		// brick it if not activated
		if ( get_option( 'progo_realestate_apiauth' ) != 100 ) {
			add_action( 'template_redirect', 'progo_to_twentyten' );
		}
	}
}
endif;

/********* Front-End Functions *********/

if ( ! function_exists( 'progo_posted_on' ) ):
/**
 * Prints HTML with meta information for the current post—date/time and author.
 * @since ProGo RealEstate 1.0
 */
function progo_posted_on() {
	echo 'Posted by: <a class="url fn n" href="'. get_author_posts_url( get_the_author_meta( 'ID' ) ) .'">'. get_the_author() .'</a> on '. get_the_date();
}
endif;
if ( ! function_exists( 'progo_posted_in' ) ):
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 * @since ProGo RealEstate 1.0
 */
function progo_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'progo' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'progo' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'progo' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;
if ( ! function_exists( 'progo_realestate_content_filter' ) ):
function progo_realestate_content_filter($content) {
	if ( get_post_type() == 'progo_property' ) {
		$deallinks = '<a onclick="newWin=window.open(\''. get_permalink(401) .'\', \'mwpop\', \'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=480,height=480,left=50,top=50\'); return false;" href="#">Cooperative Broker Guidelines</a> &nbsp; &nbsp;|&nbsp; &nbsp; <a onclick="newWin=window.open(\''. get_permalink(404) .'\', \'mwpop\', \'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=480,height=480,left=50,top=50\'); return false;" href="#">Agency Disclosure Statement</a>';
		// prep work to separate H5s into TABS
		$tabs = explode('<h5>',$content);
		if(count($tabs) > 1) {
			$tabbed = '<div id="propcontent">';
			$switch = '</div><ul id="tabs">';
			foreach ( $tabs as $t ) {
				if ( strlen($t) > 0 ) {
					$h5c = strpos( $t, '</h5>' );
					$title = wp_kses( substr( $t, 0, $h5c ), array() );
					$anchor = sanitize_title_with_dashes($title);
					$body = substr( $t, $h5c + 5 );
					$tabbed .= '<div id="'. $anchor .'" class="tab"><a name="'. $anchor .'"></a>'. $body .'</div>';
					$switch .= '<li><a href="#'. $anchor .'">'. $title .'</a></li>';
				}
			}
			$content = $tabbed . $deallinks . $switch .'</ul>';
		} else {
			$content = '<div id="propcontent">'. $content . $deallinks .'</div>';	
		}
	}
	return $content;
}
endif;
/********* Back-End Functions *********/

if ( ! function_exists( 'progo_admin_menu_cleanup' ) ):
/**
 * hooked to 'admin_menu' by add_action in progo_setup()
 * @since RealEstate 1.0
 */
function progo_admin_menu_cleanup() {
	global $menu;
	global $submenu;
	
	// lets go
	// Dashboard | ProGo Themes | Pages/Posts/Products/Media/Links/Comments | ...
	$menu[8] = $menu[5];
	$menu[7] = $menu[20];
	unset($menu[20]);
	$menu[9] = $menu[26];
	unset($menu[26]);
	
	
	add_menu_page( 'Site Settings', 'ProGo Themes', 'edit_theme_options', 'progo_site_settings', 'progo_site_settings_page', get_bloginfo( 'template_url' ) .'/images/logo_menu.png', 5 );
	add_submenu_page( 'progo_site_settings', 'Widgets', 'Widgets', 'edit_theme_options', 'widgets.php' );
	add_submenu_page( 'progo_site_settings', 'Menus', 'Menus', 'edit_theme_options', 'nav-menus.php' );
	
	$submenu['progo_site_settings'][0][0] = 'Site Settings';
	
	// add extra line
	$menu[6] = $menu[4];
	
//	wp_die('<pre>'. print_r($menu,true) .'</pre>');
}
endif;
if ( ! function_exists( 'progo_custom_login_logo' ) ):
/**
 * hooked to 'login_head' by add_action in progo_setup()
 * @since RealEstate 1.0
 */
function progo_custom_login_logo() {
	if ( get_option('progo_logo') != '' ) {
		#needswork
		echo "<!-- login screen here... overwrite logo with custom logo -->\n"; 
	} else { ?>
<style type="text/css">
#login { margin-top: 6em; }
h1 a { background: url(<?php bloginfo( 'template_url' ); ?>/images/logo_progo.png) no-repeat top center; height: 80px; }
</style>
<?php }
}
endif;
if ( ! function_exists( 'progo_custom_login_url' ) ):
/**
 * hooked to 'login_headerurl' by add_action in progo_setup()
 * @uses get_option() To check if a custom logo has been uploaded to the back end
 * @return the custom URL
 * @since RealEstate 1.0
 */
function progo_custom_login_url() {
	if ( get_option( 'progo_logo' ) != '' ) {
		return get_bloginfo( 'url' );
	} // else
	return 'http://www.progo.com';
}
endif;
if ( ! function_exists( 'progo_site_settings_page' ) ):
/**
 * outputs HTML for ProGo Themes "Site Settings" page
 * @uses settings_fields() for hidden form items for 'progo_options'
 * @uses do_settings_sections() for 'progo_site_settings'
 * @since RealEstate 1.0
 */
function progo_site_settings_page() {
?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"></div>
		<h2>Site Settings</h2>
		<form action="options.php" method="post" enctype="multipart/form-data"><?php
		settings_fields( 'progo_options' );
		do_settings_sections( 'progo_site_settings' );
		?><p class="submit"><input type="submit" name="updateoption" value="Update &raquo;" /></p>
		</form>
	</div>
<?php
}
endif;
if ( ! function_exists( 'progo_admin_page_styles' ) ):
/**
 * hooked to 'admin_print_styles' by add_action in progo_setup()
 * adds thickbox js for WELCOME screen styling
 * @since RealEstate 1.0
 */
function progo_admin_page_styles() {
	global $pagenow;
	if ( $pagenow == 'admin.php' && isset( $_GET['page'] ) ) {
		$thispage = $_GET['page'];
		if ( $thispage == 'progo_welcome' ) {
			wp_enqueue_style( 'dashboard' );
			wp_enqueue_style( 'global' );
			wp_enqueue_style( 'wp-admin' );
			wp_enqueue_style( 'thickbox' );
		}
	}
	wp_enqueue_style( 'progo_admin', get_bloginfo( 'template_url' ) .'/admin-style.css' );
}
endif;
if ( ! function_exists( 'progo_admin_page_scripts' ) ):
/**
 * hooked to 'admin_print_scripts' by add_action in progo_setup()
 * adds thickbox js for WELCOME screen Recommended Plugin info
 * @since RealEstate 1.0
 */
function progo_admin_page_scripts() {
	global $pagenow;
	if ( $pagenow == 'admin.php' && isset( $_GET['page'] ) && in_array( $_GET['page'], array( 'progo_welcome' ) ) ) {
		wp_enqueue_script( 'thickbox' );
	}
}
endif;
if ( ! function_exists( 'progo_admin_init' ) ):
/**
 * hooked to 'admin_init' by add_action in progo_setup()
 * removes meta boxes on EDIT PAGEs, and adds progo_realestate_box for RealEstate pages
 * creates CRM table if it does not exist yet
 * sets admin action hooks
 * registers Site Settings
 * @since RealEstate 1.0
 */
function progo_admin_init() {	
	//Removes meta boxes from pages
	remove_meta_box( 'postcustom', 'page', 'normal' );
	remove_meta_box( 'trackbacksdiv', 'page', 'normal' );
	remove_meta_box( 'commentstatusdiv', 'page', 'normal' );
	remove_meta_box( 'commentsdiv', 'page', 'normal' );
	remove_meta_box(  'authordiv', 'page', 'normal' );
	
	// ACTION hooks
	add_action( 'admin_print_styles', 'progo_admin_page_styles' );
	add_action( 'admin_print_scripts', 'progo_admin_page_scripts' );
	
	// Site Settings page
	register_setting( 'progo_options', 'progo_options', 'progo_options_validate' );
	
	add_settings_section( 'progo_api', 'ProGo Themes API Key', 'progo_section_text', 'progo_site_settings' );
	add_settings_field( 'progo_api_key', 'API Key', 'progo_field_apikey', 'progo_site_settings', 'progo_api' );

	add_settings_section( 'progo_info', 'Site Info', 'progo_section_text', 'progo_site_settings' );
	add_settings_field( 'progo_blogname', 'Site Name', 'progo_field_blogname', 'progo_site_settings', 'progo_info' );
	add_settings_field( 'progo_blogdescription', 'Slogan', 'progo_field_blogdesc', 'progo_site_settings', 'progo_info' );
	add_settings_field( 'progo_companyinfo', 'Footer Info', 'progo_field_compinf', 'progo_site_settings', 'progo_info' );
	
	// since there does not seem to be an actual THEME_ACTIVATION hook, we'll fake it here
	if ( get_option( 'progo_realestate_installed' ) != true ) {
		$menus = array( 'topnav' );
		$menu_ids = array();
		foreach ( $menus as $men ) {
			// create the menu in the Menu system
			$menu_ids[$men] = wp_create_nav_menu( $men );
		}
		set_theme_mod( 'nav_menu_locations' , $menu_ids );
		
		// set our default SITE options
		progo_options_defaults();
		
		// and redirect
		wp_redirect( get_option( 'siteurl' ) . '/wp-admin/admin.php?page=progo_site_settings' );
	}
}
endif;

if ( ! function_exists( 'progo_realestate_widgets' ) ):
/**
 * registers a sidebar area for the WIDGETS page
 * and registers various Widgets
 * @since RealEstate 1.0
 */
function progo_realestate_widgets() {
	register_sidebar(array(
		'name' => 'Right Column',
		'id' => 'sidebar',
		'description' => 'For the right column on the site\'s subpages',
		'before_widget' => '<div class="block %1$s %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title">',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => 'Blog Sidebar',
		'id' => 'blogside',
		'description' => 'Right Column for Blog area',
		'before_widget' => '<div class="block %1$s %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title">',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => 'Footer',
		'id' => 'fwidgets',
		'description' => 'Widgets for the Footer area',
		'before_widget' => '<li class="fblock %1$s %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="title">',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => 'Contact Page',
		'id' => 'contact',
		'description' => 'Right Column for Contact page',
		'before_widget' => '<div class="block %1$s %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title">',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => 'Newsletter Signup',
		'id' => 'signup',
		'description' => 'Widget-powered area on bottom left of Homepage',
		'before_widget' => '<div class="%1$s %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title">',
		'after_title' => '</h3>'
	));
	
	$included_widgets = array( 'Social' );
	foreach ( $included_widgets as $wi ) {
		require_once( 'widgets/widget-'. strtolower($wi) .'.php' );
		register_widget( 'ProGo_Widget_'. $wi );
	}
}
endif;

/********* core ProGo Themes' RealEstate functions *********/

if ( ! function_exists( 'progo_add_scripts' ) ):
/**
 * hooked to 'wp_print_scripts' by add_action in progo_setup()
 * adds front-end js
 * @since RealEstate 1.0
 */
function progo_add_scripts() {
	if ( !is_admin() ) {
		wp_enqueue_script( 'cufon-yui', get_bloginfo('template_url') .'/js/cufon-yui.js', array('jquery'), '1.09i', true );
		wp_enqueue_script( 'MrsEavesItalic', get_bloginfo('template_url') .'/js/MrsEavesItalic_italic_500.font.js', array('cufon-yui'), false, true );
		wp_enqueue_script( 'MrsEavesSmallCaps', get_bloginfo('template_url') .'/js/MrsEavesSmallCaps_400.font.js', array('cufon-yui'), false, true );
		
		wp_register_script( 'progo', get_bloginfo('template_url') .'/js/progo-frontend.js', array('jquery', 'cufon-yui'), '1.0' );
		wp_enqueue_script( 'progo' );
		
		do_action('progo_frontend_scripts');
	}
}
endif;
if ( ! function_exists( 'progo_add_styles' ) ):
/**
 * hooked to 'wp_print_styles' by add_action in progo_setup()
 * @since RealEstate 1.0
 */
function progo_add_styles() {
	do_action('progo_frontend_styles');
}
endif;
if ( ! function_exists( 'progo_arraytotop' ) ):
/**
 * helper function to bring a given element to the start of an array
 * @param parent array
 * @param element to bring to the top
 * @return sorted array
 * @since RealEstate 1.0
 */
function progo_arraytotop($arr, $totop) {
	// Backup and delete element from parent array
	$toparr = array($totop => $arr[$totop]);
	unset($arr[$totop]);
	// Merge the two arrays together so our widget is at the beginning
	return array_merge( $toparr, $arr );
}
endif;
/**
 * ProGo Site Settings Options defaults
 * @since RealEstate 1.0
 */
function progo_options_defaults() {
	// Define default option settings
	$tmp = get_option( 'progo_options' );
    if ( !is_array( $tmp ) ) {
		$def = array(
			"logo" => "",
			"favicon" => "",
			"blogname" => get_option( 'blogname' ),
			"blogdescription" => get_option( 'blogdescription' ),
			"credentials" => "",
			"companyinfo" => "&copy; 2011 - Our Company - All Rights Reserved | 123 Main St, San Diego, CA 92101\nPhone : 858.555.1234 | Fax : 858.555.1234 | Toll Free : 800.555.1234 | Email : info@site.com",
		);
		update_option( 'progo_options', $def );
	}
	
	update_option( 'progo_realestate_installed', true );
	update_option( 'progo_realestate_apikey', '' );
	update_option( 'progo_realestate_apiauth', 'new' );
	
	update_option( 'wpsc_ignore_theme', true );
	
	// set large image size
	update_option( 'large_size_w', 584 );
	update_option( 'large_size_h', 354 );
	// set embed size
	update_option( 'embed_size_w', 589 );
	update_option( 'embed_size_h', 373 );
}

if ( ! function_exists( 'progo_options_validate' ) ):
/**
 * ProGo Site Settings Options validation function
 * from register_setting( 'progo_options', 'progo_options', 'progo_options_validate' );
 * in progo_admin_init()
 * also handles uploading of custom Site Logo
 * @param $input options to validate
 * @return $input after validation has taken place
 * @since RealEstate 1.0
 */
function progo_options_validate( $input ) {
	// do validation here...
	$arr = array( 'blogname', 'blogdescription', 'apikey', 'companyinfo' );
	foreach ( $arr as $opt ) {
		$input[$opt] = wp_kses( $input[$opt], array() );
	}
	
	// save blogname & blogdescription to other options as well
	$arr = array( 'blogname', 'blogdescription' );
	foreach ( $arr as $opt ) {
		if ( $input[$opt] != get_option( $opt ) ) {
			update_option( $opt, $input[$opt] );
		}
	}
	
	// store API KEY in its own option
	if ( $input['apikey'] != get_option( 'progo_realestate_apikey' ) ) {
		update_option( 'progo_realestate_apikey', substr( $input['apikey'], 0, 39 ) );
	}
	unset( $input['apikey'] );
	
	// check SUPPORT field & set option['support_email'] flag if we have an email
	$input['support_email'] = is_email( $input['support'] );
	update_option('progo_settings_just_saved',1);
	
	return $input;
}
endif;

/********* more helper functions *********/

/**
 * outputs HTML for "API Key" field on Site Settings page
 * @since RealEstate 1.0
 */
function progo_field_apikey() {
	$opt = get_option( 'progo_realestate_apikey', true );
	echo '<input id="apikey" name="progo_options[apikey]" class="regular-text" type="text" value="'. esc_html( $opt ) .'" maxlength="39" />';
	$apiauth = get_option( 'progo_realestate_apiauth', true );
	switch($apiauth) {
		case 100:
			echo ' <img src="'. get_bloginfo('template_url') .'/images/check.jpg" alt="aok" class="kcheck" />';
			break;
		default:
			echo ' <img src="'. get_bloginfo('template_url') .'/images/x.jpg" alt="X" class="kcheck" title="'. $apiauth .'" />';
			break;
	}
	echo '<br /><span class="description">You API Key was sent via email when you purchased the RealEstate theme from ProGo Themes.</span>';
}

if ( ! function_exists( 'progo_field_blogname' ) ):
/**
 * outputs HTML for "Site Name" field on Site Settings page
 * @since RealEstate 1.0
 */
function progo_field_blogname() {
	$opt = get_option( 'blogname' );
	echo '<input id="blogname" name="progo_options[blogname]" class="regular-text" type="text" value="'. esc_html( $opt ) .'" />';
}
endif;
if ( ! function_exists( 'progo_field_blogdesc' ) ):
/**
 * outputs HTML for "Slogan" field on Site Settings page
 * @since RealEstate 1.0
 */
function progo_field_blogdesc() {
	$opt = get_option( 'blogdescription' ); ?>
<input id="blogdescription" name="progo_options[blogdescription]" class="regular-text" type="text" value="<?php esc_html_e( $opt ); ?>" />
<?php }
endif;
if ( ! function_exists( 'progo_field_compinf' ) ):
/**
 * outputs HTML for "Company Info" field on Site Settings page
 * @since BookIt 1.0
 */
function progo_field_compinf() {
	$options = get_option( 'progo_options' ); ?>
<textarea id="progo_companyinfo" name="progo_options[companyinfo]" style="width: 95%;" rows="5"><?php esc_html_e( $options['companyinfo'] ); ?></textarea><br />
<span class="description">This text appears in the Footer site-wide</span>
<?php }
endif;
if ( ! function_exists( 'progo_section_text' ) ):
/**
 * (dummy) function called by 
 * add_settings_section( 'progo_theme', 'Theme Customization', 'progo_section_text', 'progo_site_settings' );
 * and
 * add_settings_section( 'progo_info', 'Site Info', 'progo_section_text', 'progo_site_settings' );
 * @since RealEstate 1.0
 */
function progo_section_text() {
	// echo '<p>intro text...</p>';	
}
endif;
if ( ! function_exists( 'progo_set_default_body' ) ):
/**
 * hooked to 'default_content' by add_filter in progo_setup()
 * adds default bullet point copy to BODY field for new PRODUCTS
 * @since RealEstate 1.0
 */
function progo_set_default_body( $content ) {
	global $post_type;
	/*
	if ( $post_type == 'wpsc-product' ) {
		$default_line = "Add a 1-2 Line Benefit Point About Your Product";
		$content = "<ul>";
		for ( $i=0; $i<3; $i++ ) {
			$content .="
	<li>". $default_line ."</li>";
		}
		$content .= "
</ul>";
	}
	*/
	return $content;
}
endif;

/**
 * hooked to 'admin_notices' by add_action in progo_setup()
 * used to display "Settings updated" message after Site Settings page has been saved
 * @uses get_option() To check if our Site Settings were just saved.
 * @uses update_option() To save the setting to only show the message once.
 * @since RealEstate 1.0
 */
function progo_admin_notices() {	
	// api auth check
	$apiauth = get_option( 'progo_realestate_apiauth', true );
	if( $apiauth != '100' ) {
	?>
	<div id="message" class="error">
		<p><?php
        switch($apiauth) {
			case 'new':	// key has not been entered yet
				echo '<a href="admin.php?page=progo_site_settings" title="Site Settings">Please enter your ProGo Themes API Key to Activate your theme.</a>';
				break;
			case '999': // invalid key?
				echo 'Your ProGo Themes API Key appears to be invalid. <a href="admin.php?page=progo_site_settings" title="Site Settings">Please double check it.</a>';
				break;
			case '300': // wrong site URL?
				echo '<a href="admin.php?page=progo_site_settings" title="Site Settings">The ProGo Themes API Key you entered</a> is already bound to another URL.';
				break;
		}
		?></p>
	</div>
<?php
	}
	
	if( get_option('progo_settings_just_saved')==true ) {
	?>
	<div id="message" class="updated fade">
		<p>Settings updated. <a href="<?php bloginfo('url'); ?>/">View site</a></p>
	</div>
<?php
		update_option('progo_settings_just_saved',false);
	}
}

/**
 * hooked to 'site_transient_update_themes' by add_filter in progo_setup()
 * checks ProGo-specific URL to see if our theme is up to date!
 * @param array of checked Themes
 * @uses get_allowed_themes() To retrieve list of all installed themes.
 * @uses wp_remote_post() To check remote URL for updates.
 * @return checked data array
 * @since RealEstate 1.0
 */
function progo_update_check($data) {
	if ( is_admin() == false ) {
		return $data;
	}
	
	$themes = get_allowed_themes();
	
	if ( isset( $data->checked ) == false ) {
		$checked = array();
		// fill CHECKED array - not sure if this is necessary for all but doesnt take a long time?
		foreach ( $themes as $thm ) {
			// we don't care to check CHILD themes
			if( $thm['Parent Theme'] == '') {
				$checked[$thm[Template]] = $thm[Version];
			}
		}
		$data->checked = $checked;
	}
	if ( isset( $data->response ) == false ) {
		$data->response = array();
	}
	
	$request = array(
		'slug' => "realestate",
		'version' => $data->checked[realestate],
		'siteurl' => get_bloginfo('url')
	);
	
	// Start checking for an update
	global $wp_version;
	$apikey = get_option('progo_realestate_apikey',true);
	if ( $apikey != '' ) {
		$apikey = substr( strtolower( str_replace( '-', '', $apikey ) ), 0, 32);
	}
	$checkplz = array(
		'body' => array(
			'action' => 'theme_update', 
			'request' => serialize($request),
			'api-key' => $apikey
		),
		'user-agent' => 'WordPress/'. $wp_version .'; '. get_bloginfo('url')
	);

	$raw_response = wp_remote_post('http://www.progo.com/updatecheck/', $checkplz);
	
	if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
		$response = unserialize($raw_response['body']);
		
	if ( !empty( $response ) ) {
		// got response back. check authcode
		//wp_die('response:<br /><pre>'. print_r($response,true) .'</pre><br /><br />apikey: '. $apikey );
		// only save AUTHCODE if APIKEY is not blank.
		if ( $apikey != '' ) {
			update_option( 'progo_realestate_apiauth', $response[authcode] );
		} else {
			update_option( 'progo_realestate_apiauth', 'new' );
		}
		if ( version_compare($data->checked[realestate], $response[new_version], '<') ) {
			$data->response[realestate] = array(
				'new_version' => $response[new_version],
				'url' => $response[url],
				'package' => $response[package]
			);
		}
	}
	
	return $data;
}

function progo_to_twentyten() {
	$msg = 'This ProGo Themes site is currently not Activated.';
	
	if(current_user_can('edit_pages')) {
		$msg .= '<br /><br /><a href="'. trailingslashit(get_bloginfo('url')) .'wp-admin/admin.php?page=progo_site_settings">Click here to update your API Key</a>';
	}
	wp_die($msg);
}

function progo_realestate_init() {	
	// add taxonomy for Property - Recreational Features
	register_taxonomy('progo_recfeatures',array('progo_property'), array(
		'hierarchical' => true,
		'labels' => array(
			'name' => 'Recreational Features',
			'singular_name' => 'Feature',
			'edit_item' => 'Edit Feature',
			'add_new_item' => 'Add New Feature',
			'new_item_name' => 'New Feature Name',
			'menu_name' => 'Rec. Features'
		),
		'public' => true,
		'rewrite' => array(
			'slug' => 'montana-properties-for-sale/features',
			'with_front' => false
		)
	  ));	
	// Property Locations will be another custom taxonomy
	register_taxonomy('progo_locations',array('progo_property'), array(
		'hierarchical' => true,
		'labels' => array(
			'name' => 'Location',
			'singular_name' => 'Location',
			'edit_item' => 'Edit Location',
			'add_new_item' => 'Add New Location',
			'new_item_name' => 'New Location Name',
			'menu_name' => 'Locations',
			'all_items' => 'All Locations'
		),
		'public' => true,
		'rewrite' => array(
			'slug' => 'montana-properties-for-sale',
			'with_front' => false
		)
	  ));
	// add "Property" Custom Post Type
	register_post_type( 'progo_property',
		array(
			'labels' => array(
				'name' => 'Properties',
				'singular_name' => 'Listing',
				'add_new_item' => 'Add New Listing',
				'edit_item' => 'Edit Listing',
				'new_item' => 'New Listing',
				'view_item' => 'View Listings',
				'search_items' => 'Search Listings',
				'not_found' =>  'No listings found',
				'not_found_in_trash' => 'No listings found in Trash', 
				'parent_item_colon' => ''
			),
			'public' => true,
			'public_queryable' => true,
			'exclude_from_search' => true,
			'show_in_menu' => true,
			'menu_position' => 10,
			'hierarchical' => false,
			'supports' => array('title','editor','thumbnail','revisions','page-attributes'),
			'taxonomies' => array( 'progo_recfeatures', 'progo_locations' ),
			'register_meta_box_cb' => 'progo_property_memberboxes',
			'has_archive' => true,
			'rewrite' => array(
				'slug' => 'montana-properties-for-sale/%progo_locations%/',
				'with_front' => false
			)
		)
	);
}
add_action( 'init', 'progo_realestate_init' );

function progo_property_memberboxes() {
	add_meta_box("progo_property_box", "Property Info", "progo_property_box", "progo_property", "normal", "high");
}

function progo_property_box() {
	global $post;
	$custom = get_post_meta($post->ID,'_progo_featured');
	$feat = $custom[0];
	
	$custom = get_post_meta($post->ID,'_progo_property');
	$inf = $custom[0];
	if($inf=='') $inf = array(
		'price' => '',
		'acres' => '',
		'loc' => array(
			'addr' => '',
			'city' => '',
			'state' => '',
			'zip' => ''
		),
		'bullets' => '',
		'brochure' => '',
		'vimeo' => '',
		'gall' => ''
	);
	?>
    <table>
    <tr valign="top"><td width="50%">
    <p><input class="checkbox" type="checkbox" <?php checked($feat, 'yes') ?> name="progo_featured" value="yes" /> <label for="progo_featured"> Featured Property?</label></p>
    <p><label for="progo_property[price]"><strong>Price</strong></label><br />$ <input type="text" name="progo_property[price]" value="<?php echo absint($inf[price]); ?>" size="18" /></p>
    <p><label for="progo_property[acres]"><strong>Acreage</strong> <em>(deeded acres)</em></label><br />
<input type="text" name="progo_property[acres]" value="<?php echo (float) $inf[acres]; ?>" size="20" /></p>
<p><strong>Location</strong><br />
<label for="progo_loc[addr]">Street Address</label><br />
<input type="text" name="progo_loc[addr]" value="<?php echo esc_attr($inf[loc][addr]); ?>" size="50" /></p>
<table>
<tr valign="top"><td><p><label for="progo_loc[city]">City</label><br />
<input type="text" name="progo_loc[city]" value="<?php echo esc_attr($inf[loc][city]); ?>" size="23" /></p></td>
<td><p><label for="progo_loc[state]">State</label><br />
<input type="text" name="progo_loc[state]" value="<?php echo esc_attr($inf[loc][state]); ?>" size="2" /></p></td>
<td><p><label for="progo_loc[zip]">Zip</label><br />
<input type="text" name="progo_loc[zip]" value="<?php echo esc_attr($inf[loc][zip]); ?>" size="10" /></p></td></tr>
</table>
<p><label for="progo_property[brochure]"><strong>Brochure</strong></label><br />
<select name="progo_property[brochure]" style="width:97%"><option value="">- please select -</option></select></p>
<p class="howto"><a title="Add Media" class="thickbox" href="media-upload.php?post_id=<?php echo $post->ID; ?>&amp;TB_iframe=1&amp;width=640&amp;height=281">Upload a new file</a>. If you have just uploaded a new file, first <a href="#save" onclick="jQuery('#save,#save-post').click(); return false;">Save</a> the page to see your new file in the dropdown.</p>
<p><label for="progo_property[vimeo]"><strong>Vimeo Video URL</strong> <em>( <?php
	if ( strpos($inf[vimeo],'http://vimeo.com/')===0 && strlen($inf[vimeo])==25 ) {
		echo '<a href="'. esc_url($inf[vimeo]) .'" target="_blank">view video</a>';
	} else {
		echo 'http://vimeo.com/########';
	}
?> )</em></label><br />
<input type="text" name="progo_property[vimeo]" value="<?php echo esc_url($inf[vimeo]); ?>" size="50" /></p>
<p><label for="progo_property[gall]"><strong>Gallery</strong></label><br />
<select name="progo_property[gall]" style="width:97%"><option value="">- please select -</option><?php
    global $nggdb;
	$gallerylist = $nggdb->find_all_galleries();//'gid', 'asc', TRUE, 25, 0, false);
	$oot = '';
	foreach($gallerylist as $g) $oot = '<option value="'. $g->gid .'"'. ($g->gid==$inf[gall] ? ' selected="selected"' : '') .'>'. $g->title .'</option>'. $oot;
	echo $oot;
	?></select></p>
<p class="howto"><a href="admin.php?page=nggallery-add-gallery#addgallery" target="_blank">Create a new Gallery</a></p></td><td width="50%"><label for="progo_property[bullets]">Feature Bullet Points</label><br />
<textarea cols="50" rows="9" name="progo_property[bullets]"><?php echo esc_attr($inf[bullets]); ?></textarea>
<p class="howto">Enter bullet points above, 1 per line. Text will be displayed in upper left hand of Property Details page</p></td></tr>
    </table>
<?php
}

function progo_property_save_meta($post_id){
	// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
	// to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
	return $post_id;
	
	// Check permissions
	if ( $_POST['post_type'] == 'progo_property' ) {
		if ( !current_user_can( 'edit_page', $post_id ) ) return $post_id;
	} else {
	//if ( !current_user_can( 'edit_post', $post_id ) )
	  return $post_id;
	}
	
	// OK, we're authenticated: we need to find and save the data
	$inf = $_POST['progo_featured'];
	if ( $inf != 'yes' ) {
		$inf = 'no';
	}
	update_post_meta($post_id, "_progo_featured", $inf);
	
	$inf = $_POST['progo_property'];
	$inf[vimeo] = esc_url($inf[vimeo]);
	foreach( array('price','brochure','gall') as $f) {
		$inf[$f] = absint($inf[$f]);
	}
	// save _progo_pricegroup for better filtering
	$pricegroup = 0;
	if($inf[price] < 1000000) {
		$pricegroup = 1;
	} elseif($inf[price] <= 3000000) {
		$pricegroup = 2;
	} elseif($inf[price] <= 5000000) {
		$pricegroup = 3;
	} elseif($inf[price] <= 10000000) {
		$pricegroup = 4;
	} else {
		$pricegroup = 5;
	}
	update_post_meta($post_id, "_progo_pricegroup", $pricegroup);
	
	$inf[acres] = (float) $inf[acres];
	// save _progo_acregroup for better filtering
	$acregroup = 0;
	if($inf[acres] < 10000) {
		$acregroup = 1;
	} elseif($inf[acres] <= 20000) {
		$acregroup = 2;
	} elseif($inf[acres] <= 40000) {
		$acregroup = 3;
	} elseif($inf[acres] <= 80000) {
		$acregroup = 4;
	} else {
		$acregroup = 5;
	}
	update_post_meta($post_id, "_progo_acregroup", $acregroup);
	
	foreach( array('location','bullets') as $f) {
		$inf[$f] = strip_tags($inf[$f]);
	}
	
	$loc = $_POST['progo_loc'];
	$inf[loc] = $loc;
	
	update_post_meta($post_id, "_progo_property", $inf);
	return $inf;
}

function progo_bodyclasses($classes) {
	if( ( is_archive() || is_single() ) && ( get_post_type() == 'post' ) ) {
		$classes[] = 'blog';
		$classes[] = 'rcol';
	}
	
	if( in_array( $classes[0], array('page', 'blog') ) ) {
		$classes[] = 'rcol';
	}
	
	//wp_die('<pre>'. print_r($classes,true) .'</pre>');
	
	return $classes;
}


/**
 * based off the wpsc_product_link function
 * Gets the product link, hooks into post_link
 * Uses the currently selected, only associated or first listed category for the term URL
 * If the category slug is the same as the product slug, it prefixes the product slug with "product/" to counteract conflicts
 *
 * @access public
 * @return void
 * @since RealEstate 1.0
 */
function progo_realestate_links( $permalink, $post, $leavename ) {
	global $wp_query, $wpsc_page_titles;
	$term_url = '';
	$rewritecode = array(
		'%progo_locations%',
		'%postname%'
	);
	if ( is_object( $post ) ) {
		// In wordpress 2.9 we got a post object
		$post_id = $post->ID;
	} else {
		// In wordpress 3.0 we get a post ID
		$post_id = $post;
		$post = get_post( $post_id );
	}

	// Only applies to WPSC products, don't stop on permalinks of other CPTs
	// Fixes http://code.google.com/p/wp-e-commerce/issues/detail?id=271
	if ($post->post_type != 'progo_property') 
		return $permalink;

	$permalink_structure = get_option( 'permalink_structure' );
	// This may become customiseable later

	$our_permalink_structure = "montana-properties-for-sale/%progo_locations%/%postname%/";
	// Mostly the same conditions used for posts, but restricted to items with a post type of "wpsc-product "

	if ( '' != $permalink_structure && !in_array( $post->post_status, array( 'draft', 'pending' ) ) ) {
		$product_categories = wp_get_object_terms( $post_id, 'progo_locations' );
		$product_category_slugs = array( );
		foreach ( $product_categories as $product_category ) {
			$product_category_slugs[] = $product_category->slug;
		}
		// If the product is associated with multiple categories, determine which one to pick

		if ( count( $product_categories ) == 0 ) {
			$category_slug = 'uncategorized';
		} elseif ( count( $product_categories ) > 1 ) {
			if ( (isset( $wp_query->query_vars['products'] ) && $wp_query->query_vars['products'] != null) && in_array( $wp_query->query_vars['products'], $product_category_slugs ) ) {
				$product_category = $wp_query->query_vars['products'];
			} else {
				if(isset($wp_query->query_vars['progo_locations']))
					$link = $wp_query->query_vars['progo_locations'];
				else
					$link = $product_categories[0]->slug;

				$product_category = $link;
			}
			$category_slug = $product_category;
			$term_url = get_term_link( $category_slug, 'progo_locations' );
		} else {
			// If the product is associated with only one category, we only have one choice
			if ( !isset( $product_categories[0] ) )
				$product_categories[0] = '';

			$product_category = $product_categories[0];

			if ( !is_object( $product_category ) )
				$product_category = new stdClass();

			if ( !isset( $product_category->slug ) )
				$product_category->slug = null;

			$category_slug = $product_category->slug;

			$term_url = get_term_link( $category_slug, 'progo_locations' );
		}

		$post_name = $post->post_name;

		if(isset($category_slug) && empty($category_slug)) $category_slug = 'montana-properties-for-sale';

		$rewritereplace = array(
			$category_slug,
			$post_name
		);

		$permalink = str_replace( $rewritecode, $rewritereplace, $our_permalink_structure );
		$permalink = user_trailingslashit( $permalink, 'single' );
		$permalink = home_url( $permalink );
	}
	return $permalink;
}

function progo_queryvars( $qvars ) {
	$qvars[] = 'price';
	$qvars[] = 'acres';
	$qvars[] = 'rec';
	return $qvars;
}

function progo_realestate_get_posts( $query ) {
	if( is_admin() ) return $query;
	
	if ( isset($query->query_vars[progo_locations]) ) {
		$query->query_vars[post_type] = 'progo_property';
		$query->query[post_type] = 'progo_property';
	} elseif ( $query->query_vars[pagename] == 'montana-properties-for-sale' ) {
		$query->query_vars[post_type] = 'progo_property';
		$query->query_vars[meta_query] = array();
		//unset($query->tax_query);
		unset($query->is_page);
		unset($query->query_vars[page]);
		$query->is_page = $query->is_singular = $query->query_vars[pagename] = '';
		$query->is_archive = $query->is_post_type_archive = $query->parsed_tax_query = 1;
		$query->query = array(
			'post_type' => 'progo_property'
		);
	} elseif ( strpos( $query->query_vars[pagename], 'montana-properties-for-sale/' ) === 0 ) {
		
		$lastslash = strrpos( $query->query_vars[pagename], '/' ) + 1;
		$pagename = substr( $query->query_vars[pagename], $lastslash );
		$query->query_vars[progo_property] = $query->query_vars[name] = $query->query[progo_property] = $query->query[name] = $pagename;
		unset($query->query[pagename]);
		$query->query_vars[pagename] = '';
		$query->is_single = 1;
		$query->is_page = '';
		$query->query_vars[post_type] = $query->query[post_type] = 'progo_property';
		
	} elseif ( isset( $query->query_vars[progo_recfeatures] ) ) {
		$query->query_vars[post_type] = $query->query[post_type] = 'progo_property';
	}
	//wp_die('<pre>'.print_r($query,true).'</pre>');
	return $query;
}

function progo_realestate_columns($column){
	global $post;
	
	switch ($column) {
		case "loc":
			$tags = get_the_terms($post->ID,'progo_locations');
			// http://www.ninthlink.net/mwp/wp-admin/edit.php?progo_locations=gold-west-country&post_type=progo_property
			$onedone = false;
			$tagz = '';
			foreach($tags as $t) {
			if($onedone) $tagz .= ", ";
			$tagz .= '<a href="edit.php?progo_locations='. esc_attr($t->slug) .'&post_type=progo_property">'. esc_html($t->name) .'</a>';
			$onedone = true;
			}
			echo $tagz;
			break;
		case "price":
			$custom = get_post_meta($post->ID,'_progo_property');
			echo '$'. number_format( (float) $custom[0][price] );
			break;
		case "acres":
			$custom = get_post_meta($post->ID,'_progo_property');
			echo number_format( (float) $custom[0][acres] );
			break;
		case "feat":
			$custom = get_post_meta($post->ID,'_progo_featured');
			if ( $custom[0] == 'yes' ) {
				echo '<img src="'. get_bloginfo('template_url') .'/images/star.png" alt="yes" />';
			} else {
				echo '<img src="'. get_bloginfo('template_url') .'/images/star_off.png" alt="no" />';
			}
			break;
	}
}

function progo_property_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Title",
    "feat" => "Featured",
    "price" => "Price",
    "acres" => "Acreage",
    "loc" => "Location",
    "date" => "Date"
  );
 
  return $columns;
}

function progo_searchposts($query) {
	if ($query->is_search) {
		$query->set('post_type', 'post');
	}
	return $query;
}


function progo_hr( $atts ) {
	return '<div class="hr"></div>';
}
add_shortcode( 'hr', 'progo_hr' );

function progo_excerpt( $excerpt ) {
	global $post;
	if ( get_post_type() != 'progo_property' ) return $excerpt;
	
	$noot = str_replace('<h5>Location</h5>','', $post->post_content);
	$noot = wp_kses($noot, array());
	$noot = trim( preg_replace( '/\s+/', ' ', $noot ) );  
	if( strlen($noot) > 282 ) {
		$noot = substr( $noot, 0, 282 );
		$noot = substr( $noot, 0, strrpos($noot, ' ')) .'...';
	}
	return $noot;
}

function progo_listings_limit($limit){
	if(is_admin()) return $limit;
	
	$perPage = 6; // The number of posts per page
	
	$page = $GLOBALS['wp_query']->query_vars['paged'];
	
	if(!$page){
		$page = 1;
	}
	
	if($GLOBALS['wp_query']->query[post_type] == 'progo_property'){
		return "LIMIT ".(($page-1)*$perPage).", ".$perPage;
	}
	
	return $limit;
}