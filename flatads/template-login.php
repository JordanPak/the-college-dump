<?php
/**
 * Template name: Login Page
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage FlatAds
 * @since FlatAds 1.0
 */

if ( is_user_logged_in() ) { 

	global $redux_demo; 
	$profile = $redux_demo['profile'];
	wp_redirect( $profile ); exit;

}

global $user_ID, $username, $password, $remember;

//We shall SQL escape all inputs
$username = esc_sql(isset($_REQUEST['username']) ? $_REQUEST['username'] : '');
$password = esc_sql(isset($_REQUEST['password']) ? $_REQUEST['password'] : '');
$remember = esc_sql(isset($_REQUEST['rememberme']) ? $_REQUEST['rememberme'] : '');
	
if($remember) $remember = "true";
else $remember = "false";
$login_data = array();
$login_data['user_login'] = $username;
$login_data['user_password'] = $password;
$login_data['remember'] = $remember;
$user_verify = wp_signon( $login_data, false ); 
//wp_signon is a wordpress function which authenticates a user. It accepts user info parameters as an array.
if($_POST){
	if ( is_wp_error($user_verify) ) {
		$UserError = "<?php _e( 'Invalid username or password. Please try again!', 'agrg' ); ?>";
	} else {

		global $redux_demo; 
		$profile = $redux_demo['profile'];
		wp_redirect( $profile ); exit;

	}
}

get_header(); ?>

	<section id="ad-page-title" class="add-new-post-header" >
        
        <div class="container">

        	<div class="span9 first"> 
        		<h2><?php the_title(); ?></h2> 

        	</div>

        </div>

    </section>

    <section class="ads-main-page">

    	<div class="container">

	    	<div class="span9 first">

				<div id="edit-profile" class="ad-detail-content">

					<div class="one_half first">

						<form class="form-item" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">

							<?php global $user_ID, $user_identity; get_currentuserinfo(); ?>

							<?php if(!empty($UserError)) { ?>
								<span class='error' style='color: #d20000; margin-bottom: 20px; font-size: 18px; font-weight: bold; float: left;'><?php echo $UserError; ?></span><div class='clearfix'></div>
							<?php } ?>

								

								<fieldset class="input-title">

									<label for="edit-title" class="control-label"><?php _e('Username:', 'agrg') ?></label>
									<input type="text" name="username" class="text" value="" />

								</fieldset>

								<fieldset class="input-title">

									<label for="edit-title" class="control-label"><?php _e('Password:', 'agrg') ?></label>
									<input type="password" name="password" class="text" value="" />

								</fieldset>

								<fieldset class="input-title">

									<label for="edit-title" class="remember-me">
										<input name="rememberme" type="checkbox" value="forever" style="float: left;"/><span style="margin-left: 10px; float: left;"><?php _e( 'Remember me', 'agrg' ); ?></span>

										<?php 

								    		global $redux_demo; 
											$reset = $redux_demo['reset'];

										?>

										<a style="float: right;" class="" href="<?php echo $reset; ?>"><?php printf( __( 'Forgot Password', 'agrg' )); ?></a>

									</label>

								</fieldset>

								

								<div class="hr-line"></div>

								<div class="publish-ad-button">
									<input type="hidden" id="submitbtn" name="submit" value="Login" />
									<button class="btn form-submit" id="edit-submit" name="op" value="Publish Ad" type="submit"><?php _e('Login', 'agrg') ?></button>
								</div>

							

						</form>

					</div>

					<div class="one_half social-links">

						<div class="register-page-title">

							<?php _e( 'Social account login', 'agrg' ); ?>

						</div>

						<?php
						/**
						 * Detect plugin. For use on Front End only.
						 */
						include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

						// check for plugin using plugin name
						if ( is_plugin_active( "nextend-facebook-connect/nextend-facebook-connect.php" ) ) {
						  //plugin is activated
						
						?>

						<fieldset class="input-full-width">

							<a class="register-social-button-facebook" href="<?php echo get_site_url(); ?>/wp-login.php?loginFacebook=1" onclick="window.location = '<?php echo get_site_url(); ?>/wp-login.php?loginFacebook=1&redirect='+window.location.href; return false;"><i class="fa fa-facebook-square"></i> Facebook</a>
							
						</fieldset>

						<?php } ?>

						<?php
						/**
						 * Detect plugin. For use on Front End only.
						 */
						include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

						// check for plugin using plugin name
						if ( is_plugin_active( "nextend-twitter-connect/nextend-twitter-connect.php" ) ) {
						  //plugin is activated
						
						?>

						<fieldset class="input-full-width">

							<a class="register-social-button-twitter" href="<?php echo get_site_url(); ?>/wp-login.php?loginTwitter=1" onclick="window.location = '<?php echo get_site_url(); ?>/wp-login.php?loginTwitter=1&redirect='+window.location.href; return false;"><i class="fa fa-twitter-square"></i> Twitter</a>

						</fieldset>

						<?php } ?>

						<?php
						/**
						 * Detect plugin. For use on Front End only.
						 */
						include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

						// check for plugin using plugin name
						if ( is_plugin_active( "nextend-google-connect/nextend-google-connect.php" ) ) {
						  //plugin is activated
						
						?>

						<fieldset class="input-full-width">

							<a class="register-social-button-google" href="<?php echo get_site_url(); ?>/wp-login.php?loginGoogle=1" onclick="window.location = '<?php echo get_site_url(); ?>/wp-login.php?loginGoogle=1&redirect='+window.location.href; return false;"><i class="fa fa-google-plus-square"></i> Google</a>

						</fieldset>

						<?php } ?>

						<div class="publish-ad-button">

							<?php

								global $redux_demo; 
								$register = $redux_demo['register'];
								$reset = $redux_demo['reset'];

							?>
							
							<p><a href="<?php echo $register; ?>"><?php _e( "Register an account", "agrg" ); ?></a></p>

						</div>

					</div>

	    		</div>

	    	</div>

	    	<div class="span3">

		    	<?php get_sidebar('pages'); ?>

	    	</div>

	    </div>

    </section>

<?php get_footer(); ?>