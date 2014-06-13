<?php
/**
 * Template name: Edit Profile
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage FlatAds
 * @since FlatAds 1.0
 */

if ( !is_user_logged_in() ) { 

	global $redux_demo; 
	$login = $redux_demo['login'];
	wp_redirect( $login ); exit;

}

global $user_ID, $user_identity, $user_level;

if ($user_ID) {

	if($_POST) 

	{

		$message = "Your profile updated successfully.";

		$first = $wpdb->escape($_POST['first_name']);

		$last = $wpdb->escape($_POST['last_name']);

		$email = $wpdb->escape($_POST['email']);

		$user_url = $wpdb->escape($_POST['website']);

		$user_phone = $wpdb->escape($_POST['phone']);

		$user_address = $wpdb->escape($_POST['address']);

		$description = $wpdb->escape($_POST['desc']);

		$password = $wpdb->escape($_POST['pwd']);

		$confirm_password = $wpdb->escape($_POST['confirm']);

		

		update_user_meta( $user_ID, 'first_name', $first );

		update_user_meta( $user_ID, 'last_name', $last );

		update_user_meta( $user_ID, 'phone', $user_phone );

		update_user_meta( $user_ID, 'address', $user_address );

		update_user_meta( $user_ID, 'description', $description );

		wp_update_user( array ('ID' => $user_ID, 'user_url' => $user_url) );

		

		if(isset($email)) {

			if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)){ 

				wp_update_user( array ('ID' => $user_ID, 'user_email' => $email) ) ;

			}

			else { $message = "<div id='error'>Please enter a valid email id.</div>"; }

		}

		if($password) {

			if (strlen($password) < 5 || strlen($password) > 15) {

				$message = "<div id='error'>Password must be 5 to 15 characters in length.</div>";

				}

			//elseif( $password == $confirm_password ) {

			elseif(isset($password) && $password != $confirm_password) {

				$message = "<div class='error'>Password Mismatch</div>";

			} elseif ( isset($password) && !empty($password) ) {

				$update = wp_set_password( $password, $user_ID );

				$message = "<div id='success'>Your profile updated successfully.</div>";

			}

		}

				

	}

}

get_header(); ?>
	
	<?php while ( have_posts() ) : the_post(); ?>

	<section id="ad-page-title" class="add-new-post-header" >
        
        <div class="container">

        	<div class="span9 first"> 
        		<h2><?php the_title(); ?></h2>
        	</div>

        	<div class="span3"><h4 style="margin-top: 7px;"><?php _e('Account Overview', 'agrg') ?></h4></div>

        </div>

    </section>

    <section class="ads-main-page">

    	<div class="container">

	    	<div class="span9 first">

				<div id="edit-profile" class="ad-detail-content">

					<form class="form-item" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">

						<?php if ($user_ID) {

							$user_info = get_userdata($user_ID);

						?>

							<?php if($_POST) { 

								echo "<span class='error' style='color: #d20000; margin-bottom: 20px; font-size: 18px; font-weight: bold; float: left;'>".$message."</span><div class='clearfix'></div>";

							} ?>

							<fieldset class="input-title">

								<label for="edit-title" class="control-label"><?php _e('First name:', 'agrg') ?></label>
								<input type="text" name="first_name" class="text" value="<?php echo $user_info->first_name; ?>" />

							</fieldset>

							<fieldset class="input-title">

								<label for="edit-title" class="control-label"><?php _e('Last name:', 'agrg') ?></label>
								<input type="text" name="last_name" class="text" value="<?php echo $user_info->last_name; ?>"/> 

							</fieldset>

							<fieldset class="input-title">

								<label for="edit-title" class="control-label"><?php _e('E-mail:', 'agrg') ?></label>
								<input type="text" name="email" class="text" value="<?php echo $user_info->user_email; ?>" />

							</fieldset>

							<fieldset class="input-title">

								<label for="edit-title" class="control-label"><?php _e('Website:', 'agrg') ?></label>
								<input type="text" name="website" class="text" value="<?php echo $user_info->user_url; ?>"/>

							</fieldset>

							<fieldset class="input-title">

								<label for="edit-title" class="control-label"><?php _e('Phone:', 'agrg') ?></label>
								<input type="text" name="phone" class="text" value="<?php echo $user_info->phone; ?>" /> 

							</fieldset>

							<fieldset class="input-title">

								<label for="edit-title" class="control-label"><?php _e('Address:', 'agrg') ?></label>
								<input type="text" name="address" class="text" value="<?php echo $user_info->address; ?>" /> 

							</fieldset>

							<fieldset class="input-title">

								<label for="edit-title" class="control-label"><?php _e('About:', 'agrg') ?></label>
								<textarea name="desc" class="text" rows="10"><?php echo $user_info->description; ?></textarea>

							</fieldset>

							<fieldset class="input-title">

								<label for="edit-title" class="control-label"><?php _e('Change password:', 'agrg') ?></label>
								<input type="password" name="pwd" class="text" maxlength="15" />

							</fieldset>

							<fieldset class="input-title">

								<label for="edit-title" class="control-label"><?php _e('Retype password:', 'agrg') ?></label>
								<input type="password" name="confirm" class="text" maxlength="15" />

								<p class="help-block"><?php _e('If you would like to change the password type a new one. Otherwise leave this blank.', 'agrg') ?></p>

							</fieldset>

							<div class="hr-line"></div>

							<div class="publish-ad-button">
								<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
								<input type="hidden" name="submitted" id="submitted" value="true" />
								<button class="btn form-submit" id="edit-submit" name="op" value="Publish Ad" type="submit"><?php _e('Save', 'agrg') ?></button>
							</div>

						<?php } else { 

							$redirect_to = home_url()."/login";//change this to your custom login url

							wp_safe_redirect($redirect_to);	

						} ?>

					</form>

	    		</div>

	    	</div>

	    	<div class="span3">

	    		<span class="ad-detail-info"><?php _e( 'Regular Ads', 'agrg' ); ?>
					<span class="ad-detail"><?php echo $user_post_count = count_user_posts( $user_ID ); ?></span>
				</span>

				 <?php 

					global $redux_demo; 

					$featured_ads_option = $redux_demo['featured-options-on'];

				?>

				<?php if($featured_ads_option == 1) { ?>

				<?php

					global $paged, $wp_query, $wp;

					$args = wp_parse_args($wp->matched_query);

					$temp = $wp_query;

					$wp_query= null;

					$wp_query = new WP_Query();

					$wp_query->query('post_type=post&posts_per_page=-1&author='.$user_ID);

					$FeaturedAdsCount = 0;

				?>

				<?php while ($wp_query->have_posts()) : $wp_query->the_post(); 

					$featured_post = "0";

					$post_price_plan_activation_date = get_post_meta($post->ID, 'post_price_plan_activation_date', true);
					$post_price_plan_expiration_date = get_post_meta($post->ID, 'post_price_plan_expiration_date', true);
					$todayDate = strtotime(date('d/m/Y H:i:s'));
					$expireDate = strtotime($post_price_plan_expiration_date);  

					if(!empty($post_price_plan_activation_date)) {

						if(($todayDate < $expireDate) or empty($post_price_plan_expiration_date)) {
							$featured_post = "1";
						}

				} ?>

					<?php if($featured_post == "1") { $FeaturedAdsCount++; } ?>
					<?php endwhile; ?>
					<?php $wp_query = null; $wp_query = $temp;?>

				 	<span class="ad-detail-info"><?php _e( 'Featured Ads', 'agrg' ); ?>
						<span class="ad-detail"><?php echo $FeaturedAdsCount ?></span>
					</span>
				 <?php
				// set the meta_key to the appropriate custom field meta key

					global $wpdb;

					$result = $wpdb->get_results( "SELECT SUM(ads) AS sum FROM `wpcads_paypal`WHERE user_id = " . $current_user->ID);

					$allads = $result[0]->sum;

					$unlimited_ads = get_user_meta( $current_user->ID, 'unlimited', $single);

				?>

				<span class="ad-detail-info"><?php _e( 'Featured Ads left', 'agrg' ); ?>
					<span class="ad-detail"><?php if($unlimited_ads = "yes") { ?> ∞ <?php } else { echo $allads; } ?></span>
				</span>

				<div class="pricing-plans">
				 	<?php 

				    	global $redux_demo; 
						$featured_plans = $redux_demo['featured_plans'];

					?>
				 	<a href="<?php echo $featured_plans; ?>" class="btn" style="margin-bottom: 30px;">See Featured Ads Plans</a>
				</div>

		    	<div class="cat-widget" style="margin-top: 10px;">

		    		<div class="cat-widget-title"><h4><?php _e( 'Featured Ads', 'agrg' ); ?></h4></div>

		    		<div class="cat-widget-content">

		    			<ul> 

						  	<?php

								global $paged, $wp_query, $wp, $current, $current2;

								$args = wp_parse_args($wp->matched_query);

								if ( !empty ( $args['paged'] ) && 0 == $paged ) {

									$wp_query->set('paged', $args['paged']);

									$paged = $args['paged'];

								}


								$current = -1;
								$featuredCurrent = 0;


								$popularpost = new WP_Query( array( 'posts_per_page' => '-1', 'posts_type' => 'post', 'paged' => $paged, 'order' => 'DESC'  ) );
										

								while ( $popularpost->have_posts() ) : $popularpost->the_post(); 

								$featured_post = get_post_meta($post->ID, 'featured_post', true); 

								if($featured_post == "1") { $current++; $current2++; $featuredCurrent++; 

									if($featuredCurrent < 5) { ?>

										<li class="widget-ad-list">

											
								    		<?php require_once(TEMPLATEPATH . '/inc/BFI_Thumb.php'); ?>

											<?php 

												$thumb_id = get_post_thumbnail_id();
												$thumb_url = wp_get_attachment_image_src($thumb_id,'thumbnail-size', true);

												$params = array( 'width' => 48, 'height' => 48, 'crop' => true );
												echo "<img class='widget-ad-image' src='" . bfi_thumb( "$thumb_url[0]", $params ) . "'/>";
													
											?>

								    		<span class="widget-ad-list-content">

								    			<span class="widget-ad-list-content-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>

								    			<?php $post_price = get_post_meta($post->ID, 'post_price', true); ?>
												<span class="add-price"><?php echo $post_price; ?></span>

								    		</span>

										</li>

									<?php } ?>

								<?php } ?>

							<?php endwhile; ?>	       
						  									
						</ul>

		    		</div>

		    	</div>

		    	<?php } ?>

		    	<?php get_sidebar('pages'); ?>

	    	</div>

	    </div>

    </section>



    <?php endwhile; ?>

<?php get_footer(); ?>