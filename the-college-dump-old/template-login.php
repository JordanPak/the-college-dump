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
		$UserError = "Invalid username or password. Please try again!";
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
									<input name="rememberme" type="checkbox" value="forever" style="float: left;"/><span style="margin-left: 10px; float: left;">Remember me</span>

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

	    	</div>

	    	<div class="span3">

		    	<?php get_sidebar('pages'); ?>

	    	</div>

	    </div>

    </section>

<?php get_footer(); ?>