<?php
/**
 * Template name: Reset Password Page
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

global $resetSuccess;

if (!$user_ID) {

	if($_POST) 

	{

		// First, make sure the email address is set
		if ( isset( $_POST['email'] ) && ! empty( $_POST['email'] ) ) {

		  	// Next, sanitize the data
		  	$email_addr = trim( strip_tags( stripslashes( $_POST['email'] ) ) );

		  	$user = get_user_by( 'email', $email_addr );
		  	$user_ID = $user->ID;

		  	if( !empty($user_ID)) {

				$new_password = wp_generate_password( 12, false ); 

				if ( isset($new_password) ) {

					wp_set_password( $new_password, $user_ID );

					$message = "Check your email for new password.";

			      	$from = get_option('admin_email');
					$headers = 'From: '.$from . "\r\n";
					$subject = "Password reset!";
					$msg = "Reset password.\nYour login details\nNew Password: $new_password";
					wp_mail( $email_addr, $subject, $msg, $headers );

					$resetSuccess = 1;

				}

		    } else {

		      	$message = "There is no user available for this email.";

		    } // end if/else

		} else {
			$message = "Email should not be empty.";
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

						<?php if($_POST) { 

							echo "<div id='result' style='margin-bottom: 30px;'><div class='message'><h3>".$message."</h3></div></div>";

						} ?>

							<?php if($resetSuccess == 1) { 

						} else { ?>

							<fieldset class="input-title">

								<label for="edit-title" class="control-label"><?php _e('E-mail:', 'agrg') ?></label>
								<input type="text" name="email" class="text" value="" maxlength="30" />

							</fieldset>

							<div class="hr-line"></div>

							<div class="publish-ad-button">
								<input type="hidden" name="submit" value="Reset" id="submit" />
								<button class="btn form-submit" id="edit-submit" name="op" value="Publish Ad" type="submit"><?php _e('Submit', 'agrg') ?></button>
							</div>

						<?php } ?>

					</form>

	    		</div>

	    	</div>

	    	<div class="span3">

		    	<?php get_sidebar('pages'); ?>

	    	</div>

	    </div>

    </section>

<?php get_footer(); ?>