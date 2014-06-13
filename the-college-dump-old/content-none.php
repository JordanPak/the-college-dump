<?php
/**
 * The template for displaying a "No posts found" message.
 *
 * @package WordPress
 * @subpackage FlatAds
 * @since FlatAds 1.0
 */
?>

<header class="page-header">
	<h1 style="margin-left: 5px;" class="page-title"><?php _e( 'Nothing Found', 'agrg' ); ?></h1>
</header>

<div class="page-content">
	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

	<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'agrg' ), admin_url( 'post-new.php' ) ); ?></p>

	<?php elseif ( is_search() ) : ?>

	<p><?php _e( 'Sorry, but nothing matched your search terms.', 'agrg' ); ?></p>

	<?php else : ?>

	<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'agrg' ); ?></p>

	<?php endif; ?>
</div><!-- .page-content -->
