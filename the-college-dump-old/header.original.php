<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage FlatAds
 * @since FlatAds 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="X-UA-Compatible" content="IE=9" />
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php global $redux_demo; $favicon = $redux_demo['favicon']['url']; ?>

	<?php if (!empty($favicon)) : ?>
	<link rel="shortcut icon" href="<?php echo $favicon; ?>" type="image/x-icon" />
	<?php endif; ?>

	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<?php 

global $redux_demo; 
$layout = $redux_demo['layout-version'];

?>

<body <?php if($layout == 2){ ?>id="boxed" <?php } ?> <?php body_class(); ?>>

	<section id="top-menu-block">

		<div class="container">

			<?php 

			global $redux_demo; 

			$header_version = $redux_demo['header-version'];

			?>

			<?php if($header_version == 1) { ?>

			<div class="main_menu">
				<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => 'false')); ?>
			</div>

			<?php } elseif($header_version == 2) { ?>

			<section id="register-login-block-top">
				<ul class="ajax-register-links inline">
					<?php 
						if ( is_user_logged_in() ) {

						global $redux_demo; 
						$profile = $redux_demo['profile'];

					?>
					<li class="first">
						<a href="<?php echo $profile; ?>" class="ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Login"><?php printf( __( 'My Account', 'agrg' )); ?></a>
					</li>
					<li class="last">
						<a href="<?php echo wp_logout_url(get_option('siteurl')); ?>" class="ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Logout"><?php printf( __( 'Log out', 'agrg' )); ?></a>
					</li>
					<?php } else { 

						global $redux_demo; 
						$login = $redux_demo['login'];
						$register = $redux_demo['register'];
					?>
					<li class="first">
						<a href="<?php echo $login; ?>" class="ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Login"><?php printf( __( 'Login', 'agrg' )); ?></a>
					</li>
					<li class="last">
						<a href="<?php echo $register; ?>" class="ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Register"><?php printf( __( 'Register', 'agrg' )); ?></a>
					</li>
				<?php } ?>
				</ul>  
			</section>

			<?php } ?>

			<div class="top-social-icons">

				<?php 

					global $redux_demo; 

					$facebook_link = $redux_demo['facebook-link'];
					$twitter_link = $redux_demo['twitter-link'];
					$dribbble_link = $redux_demo['dribbble-link'];
					$flickr_link = $redux_demo['flickr-link'];
					$github_link = $redux_demo['github-link'];
					$pinterest_link = $redux_demo['pinterest-link'];
					$youtube_link = $redux_demo['youtube-link'];
					$google_plus_link = $redux_demo['google-plus-link'];
					$linkedin_link = $redux_demo['linkedin-link'];
					$tumblr_link = $redux_demo['tumblr-link'];
					$vimeo_link = $redux_demo['vimeo-link'];

				?>

				<?php if(!empty($facebook_link)) { ?>

					<a class="target-blank" href="<?php echo $facebook_link; ?>"><i class="fa fa-facebook-square"></i></a>

				<?php } ?>

				<?php if(!empty($twitter_link)) { ?>

					<a class="target-blank" href="<?php echo $twitter_link; ?>"><i class="fa fa-twitter-square"></i></a>

				<?php } ?>

				<?php if(!empty($dribbble_link)) { ?>

					<a class="target-blank" href="<?php echo $dribbble_link; ?>"><i class="fa fa-dribbble"></i></a>

				<?php } ?>

				<?php if(!empty($flickr_link)) { ?>

					<a class="target-blank" href="<?php echo $flickr_link; ?>"><i class="fa fa-flickr"></i></a>

				<?php } ?>

				<?php if(!empty($github_link)) { ?>

					<a class="target-blank" href="<?php echo $github_link; ?>"><i class="fa fa-github-square"></i></a>

				<?php } ?>

				<?php if(!empty($pinterest_link)) { ?>

					<a class="target-blank" href="<?php echo $pinterest_link; ?>"><i class="fa fa-pinterest-square"></i></a>

				<?php } ?>

				<?php if(!empty($youtube_link)) { ?>

					<a class="target-blank" href="<?php echo $youtube_link; ?>"><i class="fa fa-youtube-square"></i></a>

				<?php } ?>

				<?php if(!empty($google_plus_link)) { ?>

					<a class="target-blank" href="<?php echo $google_plus_link; ?>"><i class="fa fa-google-plus-square"></i></a>

				<?php } ?>

				<?php if(!empty($linkedin_link)) { ?>

					<a class="target-blank" href="<?php echo $linkedin_link; ?>"><i class="fa fa-linkedin-square"></i></a>

				<?php } ?>

				<?php if(!empty($tumblr_link)) { ?>

					<a class="target-blank" href="<?php echo $tumblr_link; ?>"><i class="fa fa-tumblr-square"></i></a>

				<?php } ?>

				<?php if(!empty($vimeo_link)) { ?>

					<a class="target-blank" href="<?php echo $vimeo_link; ?>"><i class="fa fa-vimeo-square"></i></a>

				<?php } ?>

			</div>

		</div>

	</section>
																		
	<header id="navbar">

		<div class="container">

			<?php if($header_version == 2) { ?>

			<a class="logo pull-left" href="<?php echo home_url(); ?>" title="Home">
				<?php global $redux_demo; $logo = $redux_demo['logo']['url']; if (!empty($logo)) { ?>
					<img src="<?php echo $logo; ?>" alt="Logo" />
				<?php } else { ?>
					<img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" />
				<?php } ?>
			</a>

			<div id="version-two-menu" class="main_menu">
				<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => 'false')); ?>
			</div>

			<section id="new-post" class="block block-crystal-block" style="margin-top: 13px !important">
					<?php 
						global $redux_demo; 
						$new_post = $redux_demo['new_post'];
					?>
					<a href="<?php echo $new_post; ?>" class="btn button"><?php printf( __( 'Post your Ad!', 'agrg' )); ?></a>
				</section> <!-- /.block -->

			<?php } elseif($header_version == 1) { ?>

			<a id="version-one-header-logo" class="logo pull-left" href="<?php echo home_url(); ?>" title="Home">
				<?php global $redux_demo; $logo = $redux_demo['logo']['url']; if (!empty($logo)) { ?>
					<img src="<?php echo $logo; ?>" alt="Logo" />
				<?php } else { ?>
					<img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" />
				<?php } ?>
			</a>

			<a class="btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>

			<div class="nav-collapse">

				<section id="search-field">

					<div class="block-content clearfix">
						
						<form action="<?php echo home_url(); ?>" method="get" id="views-exposed-form-search-view-other-ads-page" accept-charset="UTF-8">

							<div class="views-exposed-form">
							  	<div class="views-exposed-widgets clearfix">
							         						
						         	<div id="edit-search-api-views-fulltext-wrapper" class="views-exposed-widget views-widget-filter-search_api_views_fulltext">
						                <div class="views-widget">
						          			<div class="control-group form-type-textfield form-item-search-api-views-fulltext form-item">
												<div class="controls"> 
													<input placeholder="Enter keyword..." type="text" id="edit-search-api-views-fulltext" name="s" value="" size="30" maxlength="128" class="form-text">
												</div>
											</div>
							        	</div>
							        </div>
							          						
							        <div id="edit-ad-location-wrapper" class="views-exposed-widget views-widget-filter-field_ad_location">
							            <div class="views-widget">
							          		<div class="control-group form-type-select form-item-ad-location form-item">
												<div class="controls"> 
													<select id="edit-ad-location" name="post_location" class="form-select" style="display: none;">
														<option value="All" selected="selected">Location...</option>

														<?php

															$args_location = array( 'posts_per_page' => -1 );
															$lastposts = get_posts( $args_location );

															$all_post_location = array();
															foreach( $lastposts as $post ) {
																$all_post_location[] = get_post_meta( $post->ID, 'post_location', true );
															}

															$directors = array_unique($all_post_location);
															foreach ($directors as $director) { ?>
																<option value="<?php echo $director; ?>"><?php echo $director; ?></option>
															<?php }

														?>

														<?php wp_reset_query(); ?>

													</select>
												</div>
											</div>
							        	</div>
							        </div>

							        <div id="edit-field-category-wrapper" class="views-exposed-widget views-widget-filter-field_category">
							            <div class="views-widget">
							          		<div class="control-group form-type-select form-item-field-category form-item">
												<div class="controls"> 
													<select id="edit-field-category" name="category_name" class="form-select" style="display: none;">
														
														<option value="All" selected="selected">Category...</option>
														<?php
														$args = array(
															'hierarchical' => '0',
															'hide_empty' => '0'
														);
														$categories = get_categories($args);
															foreach ($categories as $cat) {
																if ($cat->category_parent == 0) { 
																	$catID = $cat->cat_ID;
																	?>
																	<option value="<?php echo $cat->cat_name; ?>"><?php echo $cat->cat_name; ?></option>
																				
															<?php 
																$args2 = array(
																	'hide_empty' => '0',
																	'parent' => $catID
																);
																$categories = get_categories($args2);
																foreach ($categories as $cat) { ?>
																	<option value="<?php echo $cat->slug; ?>">- <?php echo $cat->cat_name; ?></option>
															<?php } ?>

															<?php } else { ?>
															<?php }
														} ?>

													</select>
												</div>
											</div>
							        	</div>
							        </div>

							        <input type="text" name="geo-location" id="geo-location" value="off" data-default-value="off">

							        <input type="text" name="geo-radius-search" id="geo-radius-search" value="500" data-default-value="500">

							        <input type="text" name="geo-search-lat" id="geo-search-lat" value="0" data-default-value="0">

							        <input type="text" name="geo-search-lng" id="geo-search-lng" value="0" data-default-value="0">

							        <div class="views-exposed-widget views-submit-button">
							      		<button class="btn btn-primary form-submit" id="edit-submit-search-view" name="" value="Search" type="submit"><?php printf( __( 'Search', 'agrg' )); ?></button>
							    	</div>

							    </div>
							</div>

						</form>

					</div>
				</section> <!-- /.block -->

				<section id="register-login-block">
					<ul class="ajax-register-links inline">
						<?php 
							if ( is_user_logged_in() ) {

							global $redux_demo; 
							$profile = $redux_demo['profile'];

						?>
						<li class="first">
							<a href="<?php echo $profile; ?>" class="ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Login"><?php printf( __( 'My Account', 'agrg' )); ?></a>
						</li>
						<li class="last">
							<a href="<?php echo wp_logout_url(get_option('siteurl')); ?>" class="ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Logout"><?php printf( __( 'Log out', 'agrg' )); ?></a>
						</li>
						<?php } else { 

							global $redux_demo; 
							$login = $redux_demo['login'];
							$register = $redux_demo['register'];
						?>
						<li class="first">
							<a href="<?php echo $login; ?>" class="ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Login"><?php printf( __( 'Login', 'agrg' )); ?></a>
						</li>
						<li class="last">
							<a href="<?php echo $register; ?>" class="ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Register"><?php printf( __( 'Register', 'agrg' )); ?></a>
						</li>
					<?php } ?>
					</ul>  
				</section>

				<section id="new-post" class="block block-crystal-block">
					<?php 
						global $redux_demo; 
						$new_post = $redux_demo['new_post'];
					?>
					<a href="<?php echo $new_post; ?>" class="btn button"><?php printf( __( 'Post your Ad!', 'agrg' )); ?></a>
				</section> <!-- /.block -->

				<?php } ?>

			</div>
				
		</div>

	</header><!-- #masthead -->