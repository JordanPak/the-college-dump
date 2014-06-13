<?php
/**
 * Template name: Register Page
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

global $user_ID, $user_identity, $user_level, $registerSuccess;

$registerSuccess = "";


if (!$user_ID) {

	if($_POST) 

	{

		$message = "Registration successful.";

		$username = $wpdb->escape($_POST['username']);

		$email = $wpdb->escape($_POST['email']);

		$password = $wpdb->escape($_POST['pwd']);

		$confirm_password = $wpdb->escape($_POST['confirm']);

		$registerSuccess = 1;



		if(empty($username)) {
			$message = "User name should not be empty.";
			$registerSuccess = 0;
		}

		

		if(isset($email)) {

			if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)){ 

				wp_update_user( array ('ID' => $user_ID, 'user_email' => $email) ) ;

			}

			else { $message = "Please enter a valid email."; }

			$registerSuccess = 0;

		}

		if($password) {

			if (strlen($password) < 5 || strlen($password) > 15) {

				$message = "Password must be 5 to 15 characters in length.";

				$registerSuccess = 0;

				}

			//elseif( $password == $confirm_password ) {

			elseif(isset($password) && $password != $confirm_password) {

				$message = "Password Mismatch";

				$registerSuccess = 0;

			} elseif ( isset($password) && !empty($password) ) {

				$update = wp_set_password( $password, $user_ID );

				$message = "Registration successful.";

				$registerSuccess = 1;

			}

		}

		$status = wp_create_user( $username, $password, $email );
		if ( is_wp_error($status) ) {
			$registerSuccess = 0;
			$message = "Username or E-mail already exists. Please try another one.";
		} else {
			$from = get_option('admin_email');
			$headers = 'From: '.$from . "\r\n";
			$subject = "Registration successful";
			$msg = "Registration successful.\nYour login details\nUsername: $username\nPassword: $password";
			wp_mail( $email, $subject, $msg, $headers );

			$registerSuccess = 1;
		}


		if($registerSuccess == 1) {

			$login_data = array();
			$login_data['user_login'] = $username;
			$login_data['user_password'] = $password;
			$user_verify = wp_signon( $login_data, false ); 

			global $redux_demo; 
			$profile = $redux_demo['profile'];
			wp_redirect( $profile ); exit;

		}

				

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

						<?php 					
							if(get_option('users_can_register')) { //Check whether user registration is enabled by the administrator
						?>

							<?php if($_POST) { 

								global $redux_demo; 
								$login = $redux_demo['login'];

								echo "<div id='result' style='margin-bottom: 30px;'><div class='message'><h3>".$message." ";

								if($registerSuccess == 1) {
									echo "<a href='".$login."'>Login</a>.";
								}

								echo "</h3></div></div>";

							} ?>

								<?php if($registerSuccess == 1) { } else { ?>

								<fieldset class="input-title">

									<label for="edit-title" class="control-label"><?php _e('Username:', 'agrg') ?></label>
									<input type="text" name="username" class="text" value="" maxlength="30" />

								</fieldset>

								<fieldset class="input-title">

									<label for="edit-title" class="control-label"><?php _e('E-mail:', 'agrg') ?></label>
									<input type="text" name="email" class="text" value="" maxlength="30" />

								</fieldset>

								<fieldset class="input-title">

									<label for="edit-title" class="control-label"><?php _e('Password:', 'agrg') ?></label>
									<input type="password" name="pwd" class="text" maxlength="15" />

								</fieldset>

								<fieldset class="input-title">

									<label for="edit-title" class="control-label"><?php _e('Retype password:', 'agrg') ?></label>
									<input type="password" name="confirm" class="text" maxlength="15" />

								</fieldset>

								<div class="hr-line"></div>

								<div class="publish-ad-button">
									<input type="hidden" name="submit" value="Register" id="submit" />
									<button class="btn form-submit" id="edit-submit" name="op" value="Publish Ad" type="submit"><?php _e('Submit', 'agrg') ?></button>
								</div>

							<?php } ?>

						<?php }
						
								else echo "Registration is currently disabled. Please try again later.";

						?>

					</form>

	    		</div>

	    	</div>

	    	<div class="span3">

	    		<?php get_sidebar('pages'); ?>

	    	</div>

	    </div>

    </section>

<?php get_footer(); ?>