<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage FlatAds
 * @since FlatAds 1.0
 */
?>
	<footer>

		<div class="container">
					
			<div class="full">
				
				<?php get_sidebar( 'footer-one' ); ?>

				<?php get_sidebar( 'footer-two' ); ?>

				<?php get_sidebar( 'footer-three' ); ?>

				<?php get_sidebar( 'footer-four' ); ?>

			</div>

			

		</div>
					
	</footer>

	<section class="socket">

		<div class="container">

			<div class="site-info">
				<?php 

					global $redux_demo; 
					$footer_copyright = $redux_demo['footer_copyright'];

				?>

				<?php if(!empty($footer_copyright)) { 
						echo $footer_copyright;
					} else {
				?>
				 @ 2014 FlatAds - by <a class="target-blank" href="http://themeforest.net/user/agurghis/portfolio?ref=agurghis">Alex Gurghis</a>
				<?php } ?>
				
			</div><!-- .site-info -->

			<div class="footer_menu">
				<?php wp_nav_menu(array('theme_location' => 'secondary', 'container' => 'false')); ?>
			</div>

			<div class="backtop">
				<a href="#backtop"><i class="fa fa-chevron-up"></i></a>
			</div>

		</div>

	</section>

	<?php wp_footer(); ?>
</body>
</html>