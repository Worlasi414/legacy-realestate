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
	</div><!-- #page -->
</div><!-- #wrap -->
<div id="fwrap">
	<ul id="ftr" class="container_12">
    <?php dynamic_sidebar('footer'); ?>
	</ul><!-- #ftr -->
</div><!-- #fwrap -->

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>
