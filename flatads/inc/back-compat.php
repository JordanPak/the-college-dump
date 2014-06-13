<?php
/**
 * WPAds back compat functionality.
 *
 * Prevents WPAds from running on WordPress versions prior to 3.6,
 * since this theme is not meant to be backwards compatible and relies on
 * many new functions and markup changes introduced in 3.6.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since WPAds 1.0
 */

/**
 * Prevent switching to WPAds on old versions of WordPress. Switches
 * to the default theme.
 *
 * @since WPAds 1.0
 *
 * @return void
 */
function wpads_switch_theme() {
	switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'wpads_upgrade_notice' );
}
add_action( 'after_switch_theme', 'wpads_switch_theme' );

/**
 * Prints an update nag after an unsuccessful attempt to switch to
 * WPAds on WordPress versions prior to 3.6.
 *
 * @since WPAds 1.0
 *
 * @return void
 */
function wpads_upgrade_notice() {
	$message = sprintf( __( 'WPAds requires at least WordPress version 3.6. You are running version %s. Please upgrade and try again.', 'agrg' ), $GLOBALS['wp_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Prevents the Customizer from being loaded on WordPress versions prior to 3.6.
 *
 * @since WPAds 1.0
 *
 * @return void
 */
function wpads_customize() {
	wp_die( sprintf( __( 'WPAds requires at least WordPress version 3.6. You are running version %s. Please upgrade and try again.', 'agrg' ), $GLOBALS['wp_version'] ), '', array(
		'back_link' => true,
	) );
}
add_action( 'load-customize.php', 'wpads_customize' );

/**
 * Prevents the Theme Preview from being loaded on WordPress versions prior to 3.4.
 *
 * @since WPAds 1.0
 *
 * @return void
 */
function wpads_preview() {
	if ( isset( $_GET['preview'] ) ) {
		wp_die( sprintf( __( 'WPAds requires at least WordPress version 3.6. You are running version %s. Please upgrade and try again.', 'agrg' ), $GLOBALS['wp_version'] ) );
	}
}
add_action( 'template_redirect', 'wpads_preview' );