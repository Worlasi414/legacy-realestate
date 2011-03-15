<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package ProGo
 * @subpackage RealEstate
 * @since RealEstate 1.0
 */
?>
	</div><!-- #container -->
<div id="fwrap">
<div id="ftxt">
<?php
$options = get_option( 'progo_options' );
echo wp_kses(nl2br($options['companyinfo']), array('br'=>array()));
?>
</div>
	<ul id="ftr" class="container_12">
    <?php dynamic_sidebar('footer'); ?>
	</ul><!-- #ftr -->
</div><!-- #fwrap -->
	</div><!-- #page -->
</div><!-- #wrap -->
</div></div>
<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>
