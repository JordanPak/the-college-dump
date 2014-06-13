<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage FlatAds
 * @since FlatAds 1.0
 */
?>

<div class="span3 grid-box" id="post-<?php the_ID(); ?>">
	<div class="box">
		<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
		<div class="views-field views-field-field-images">
			<div class="field-content">
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail(); ?>
				</a>
			</div>
		</div>
		<?php endif; ?>

		<div>
			<h2 class="ad-type">
				<?php $post_category_type = get_post_meta($post->ID, 'post_category_type', true); ?>
				<?php 
					if($post_category_type == "For Sale") {
						?><img src="<?php echo get_template_directory_uri(); ?>/images/for-sale.png" width="50" height="50" alt=""><?php
					} elseif($post_category_type == "For Rent") {
						?><img src="<?php echo get_template_directory_uri(); ?>/images/for-rent.png" width="50" height="50" alt=""><?php
					} elseif($post_category_type== "Want to Buy") {
						?><img src="<?php echo get_template_directory_uri(); ?>/images/want-to-buy.png" width="50" height="50" alt=""><?
					} elseif($post_category_type== "Want to Rent") {
						?><img src="<?php echo get_template_directory_uri(); ?>/images/want-to-rent.png" width="50" height="50" alt=""><?php
					} 
				?>
			</h2>
		</div>

		<div>
			<div class="grid-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</div>
		</div>

		<div class="price-wrapp">
			<?php $post_price = get_post_meta($post->ID, 'post_price', true); ?>
			<div class="price-item"><?php echo $post_price; ?></div>
		</div>
	</div>
</div><!-- #post -->
