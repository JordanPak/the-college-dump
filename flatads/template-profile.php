<?php
/**
 * Template name: Profile Page
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

global $current_user, $user_id, $permalink;

$permalink = get_permalink();

get_currentuserinfo();
$user_id = $current_user->ID; // You can set $user_id to any users, but this gets the current users ID.


if( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
	$delete_post_id = esc_attr(strip_tags($_POST['deletepostid']));
	wp_delete_post( $delete_post_id, true );  /* delete the post we choosed   */
};

get_header(); 

global $redux_demo, $maximRange; 
	$max_range = $redux_demo['max_range'];
	if(!empty($max_range)) {
		$maximRange = $max_range;
	} else {
		$maximRange = 1000;
	}

?>

	<section id="big-map">

		<div id="directory-main-map"></div>

		<script type="text/javascript">
		var mapDiv,
			map,
			infobox;
		jQuery(document).ready(function($) {

			mapDiv = $("#directory-main-map");
			mapDiv.height(500).gmap3({
				map: {
					options: {
						"draggable": true
						,"mapTypeControl": true
						,"mapTypeId": google.maps.MapTypeId.ROADMAP
						,"scrollwheel": false
						,"panControl": true
						,"rotateControl": false
						,"scaleControl": true
						,"streetViewControl": true
						,"zoomControl": true
						<?php global $redux_demo; $map_style = $redux_demo['map-style']; if(!empty($map_style)) { ?>,"styles": <?php echo $map_style; ?> <?php } ?>
					}
				}
				,marker: {
					values: [

					<?php

						$wp_query= null;

						$wp_query = new WP_Query();

						$wp_query->query('post_type=post&posts_per_page=-1&author='.$user_id);

						


						while ($wp_query->have_posts()) : $wp_query->the_post(); 

						$post_latitude = get_post_meta($post->ID, 'post_latitude', true);
						$post_longitude = get_post_meta($post->ID, 'post_longitude', true);

						$theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 40) ? substr($theTitle,0,37).'...' : $theTitle;

						$post_price = get_post_meta($post->ID, 'post_price', true);


						$category = get_the_category();

						if ($category[0]->category_parent == 0) {

							$tag = $category[0]->cat_ID;

							$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
							if (isset($tag_extra_fields[$tag])) {
								$your_image_url = $tag_extra_fields[$tag]['your_image_url']; //i added this line.
							}

						} else {

							$tag = $category[0]->category_parent;

							$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
							if (isset($tag_extra_fields[$tag])) {
								$your_image_url = $tag_extra_fields[$tag]['your_image_url']; //i added this line.
							}

						}

						if(!empty($your_image_url)) {

					    	$iconPath = $your_image_url;

					    } else {

					    	$iconPath = get_template_directory_uri() .'/images/icon-services.png';

					    }

						if(!empty($post_latitude)) { ?>

							 	{
							 		<?php require_once(TEMPLATEPATH . "/inc/BFI_Thumb.php"); ?>
									<?php $params = array( "width" => 230, "height" => 150, "crop" => true ); $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "single-post-thumbnail" ); ?>

									latLng: [<?php echo $post_latitude; ?>,<?php echo $post_longitude; ?>],
									options: {
										icon: "<?php echo $iconPath; ?>",
										shadow: "<?php echo get_template_directory_uri() ?>/images/shadow.png",
									},
									data: '<div class="marker-holder"><div class="marker-content"><div class="marker-image"><img src="<?php echo bfi_thumb( "$image[0]", $params ) ?>" /></div><div class="marker-info-holder"><div class="marker-info"><div class="marker-info-title"><?php echo $theTitle; ?></div><div class="marker-info-extra"><div class="marker-info-price"><?php echo $post_price; ?></div><div class="marker-info-link"><a href="<?php the_permalink(); ?>"><?php _e( "Details", "agrg" ); ?></a></div></div></div></div><div class="arrow-down"></div><div class="close"></div></div></div>'
								}
							,

					<?php } endwhile; ?>	

					<?php wp_reset_query(); ?>
						
					],
					options:{
						draggable: false
					},
					cluster:{
		          		radius: 20,
						// This style will be used for clusters with more than 0 markers
						0: {
							content: "<div class='cluster cluster-1'>CLUSTER_COUNT</div>",
							width: 62,
							height: 62
						},
						// This style will be used for clusters with more than 20 markers
						20: {
							content: "<div class='cluster cluster-2'>CLUSTER_COUNT</div>",
							width: 82,
							height: 82
						},
						// This style will be used for clusters with more than 50 markers
						50: {
							content: "<div class='cluster cluster-3'>CLUSTER_COUNT</div>",
							width: 102,
							height: 102
						},
						events: {
							click: function(cluster) {
								map.panTo(cluster.main.getPosition());
								map.setZoom(map.getZoom() + 2);
							}
						}
		          	},
					events: {
						click: function(marker, event, context){
							map.panTo(marker.getPosition());

							var ibOptions = {
							    pixelOffset: new google.maps.Size(-125, -88),
							    alignBottom: true
							};

							infobox.setOptions(ibOptions)

							infobox.setContent(context.data);
							infobox.open(map,marker);

							// if map is small
							var iWidth = 260;
							var iHeight = 300;
							if((mapDiv.width() / 2) < iWidth ){
								var offsetX = iWidth - (mapDiv.width() / 2);
								map.panBy(offsetX,0);
							}
							if((mapDiv.height() / 2) < iHeight ){
								var offsetY = -(iHeight - (mapDiv.height() / 2));
								map.panBy(0,offsetY);
							}

						}
					}
				}
				 		 	},"autofit");

			map = mapDiv.gmap3("get");
		    infobox = new InfoBox({
		    	pixelOffset: new google.maps.Size(-50, -65),
		    	closeBoxURL: '',
		    	enableEventPropagation: true
		    });
		    mapDiv.delegate('.infoBox .close','click',function () {
		    	infobox.close();
		    });

		    if (Modernizr.touch){
		    	map.setOptions({ draggable : false });
		        var draggableClass = 'inactive';
		        var draggableTitle = "Activate map";
		        var draggableButton = $('<div class="draggable-toggle-button '+draggableClass+'">'+draggableTitle+'</div>').appendTo(mapDiv);
		        draggableButton.click(function () {
		        	if($(this).hasClass('active')){
		        		$(this).removeClass('active').addClass('inactive').text("Activate map");
		        		map.setOptions({ draggable : false });
		        	} else {
		        		$(this).removeClass('inactive').addClass('active').text("Deactivate map");
		        		map.setOptions({ draggable : true });
		        	}
		        });
		    }

		jQuery( "#advance-search-slider" ).slider({
		      	range: "min",
		      	value: 500,
		      	min: 1,
		      	max: <?php echo $maximRange; ?>,
		      	slide: function( event, ui ) {
		       		jQuery( "#geo-radius" ).val( ui.value );
		       		jQuery( "#geo-radius-search" ).val( ui.value );

		       		jQuery( ".geo-location-switch" ).removeClass("off");
		      	 	jQuery( ".geo-location-switch" ).addClass("on");
		      	 	jQuery( "#geo-location" ).val("on");

		       		mapDiv.gmap3({
						getgeoloc:{
							callback : function(latLng){
								if (latLng){
									jQuery('#geo-search-lat').val(latLng.lat());
									jQuery('#geo-search-lng').val(latLng.lng());
								}
							}
						}
					});

		      	}
		    });
		    jQuery( "#geo-radius" ).val( jQuery( "#advance-search-slider" ).slider( "value" ) );
		    jQuery( "#geo-radius-search" ).val( jQuery( "#advance-search-slider" ).slider( "value" ) );

		    jQuery('.geo-location-button .fa').click(function()
			{
				
				if(jQuery('.geo-location-switch').hasClass('off'))
			    {
			        jQuery( ".geo-location-switch" ).removeClass("off");
				    jQuery( ".geo-location-switch" ).addClass("on");
				    jQuery( "#geo-location" ).val("on");

				    mapDiv.gmap3({
						getgeoloc:{
							callback : function(latLng){
								if (latLng){
									jQuery('#geo-search-lat').val(latLng.lat());
									jQuery('#geo-search-lng').val(latLng.lng());
								}
							}
						}
					});

			    } else {
			    	jQuery( ".geo-location-switch" ).removeClass("on");
				    jQuery( ".geo-location-switch" ).addClass("off");
				    jQuery( "#geo-location" ).val("off");
			    }
		           
		    });

		});
		</script>

		<?php 

			global $redux_demo; 

			$header_version = $redux_demo['header-version'];

		?>

		<?php if($header_version == 1) { ?>

		<div id="advanced-search-widget">

			<div class="container">

				<div class="advanced-search-widget-content">

					<div class="advanced-search-title">

						<?php _e( 'Search around my position', 'agrg' ); ?>

					</div>

					<div class="advanced-search-slider">

						<div class="geo-location-button">

							<div class="geo-location-switch off"><i class="fa fa-location-arrow"></i></div>

						</div>

						<div id="advance-search-slider" class="value-slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" aria-disabled="false">
							<a class="ui-slider-handle ui-state-default ui-corner-all" href="#">
								<span class="range-pin">
									<input type="text" name="geo-radius" id="geo-radius" value="100" data-default-value="100">
								</span>
							</a>
						</div>

					</div>

				</div>

			</div>

		</div>

		<?php } elseif($header_version == 2) { ?>

		<div id="advanced-search-widget-version2">

			<div class="container">

				<div class="advanced-search-widget-content">

					<form action="<?php echo home_url(); ?>" method="get" id="views-exposed-form-search-view-other-ads-page" accept-charset="UTF-8">

						<div id="edit-search-api-views-fulltext-wrapper" class="views-exposed-widget views-widget-filter-search_api_views_fulltext">
					        <div class="views-widget">
					          	<div class="control-group form-type-textfield form-item-search-api-views-fulltext form-item">
									<div class="controls"> 
										<input placeholder="<?php _e( 'Enter keyword...', 'agrg' ); ?>" type="text" id="edit-search-api-views-fulltext" name="s" value="" size="30" maxlength="128" class="form-text">
									</div>
								</div>
						    </div>
						</div>
						          						
						<div id="edit-ad-location-wrapper" class="views-exposed-widget views-widget-filter-field_ad_location">
						   	<div class="views-widget">
						        <div class="control-group form-type-select form-item-ad-location form-item">
									<div class="controls"> 
										<select id="edit-ad-location" name="post_location" class="form-select" style="display: none;">
											<option value="All" selected="selected"><?php _e( 'Location...', 'agrg' ); ?></option>

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
													
											<option value="All" selected="selected"><?php _e( 'Category...', 'agrg' ); ?></option>
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

						<div class="advanced-search-slider">

							<div class="geo-location-button">

								<div class="geo-location-switch off"><i class="fa fa-location-arrow"></i></div>

							</div>

							<div id="advance-search-slider" class="value-slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" aria-disabled="false">
								<a class="ui-slider-handle ui-state-default ui-corner-all" href="#">
									<span class="range-pin">
										<input type="text" name="geo-radius" id="geo-radius" value="100" data-default-value="100">
									</span>
								</a>
							</div>

						</div>


						<input type="text" name="geo-location" id="geo-location" value="off" data-default-value="off">

						<input type="text" name="geo-radius-search" id="geo-radius-search" value="500" data-default-value="500">

						<input type="text" name="geo-search-lat" id="geo-search-lat" value="0" data-default-value="0">

						<input type="text" name="geo-search-lng" id="geo-search-lng" value="0" data-default-value="0">


						<div class="views-exposed-widget views-submit-button">
						    <button class="btn btn-primary form-submit" id="edit-submit-search-view" name="" value="Search" type="submit"><?php printf( __( 'Search', 'agrg' )); ?></button>
						</div>

					</form>

				</div>

			</div>

		</div>

		<?php } ?>

	</section>

	<?php 

		global $redux_demo; 

		$featured_ads_option = $redux_demo['featured-options-on'];

	?>

	<?php if($featured_ads_option == 1) { ?>

    <section id="featured-ads-author">
        
        <div class="container">
            
            <h3><?php echo $user_identity; ?> <?php _e( 'Premium Featured Ads', 'agrg' ); ?></h3>
            
            <div id="tabs" class="full">
			    	
                <?php $cat_id = get_cat_ID(single_cat_title('', false)); ?>
			    	
                <ul class="tabs quicktabs-tabs quicktabs-style-nostyle"> 
			    	<li class="grid-feat-ad-style"><a class="" href="#">Grid View</a></li>
			    	<li class="list-feat-ad-style"><a class="" href="#">List View</a></li>
                </ul>

                <div class="pane">
                 
                    <div id="carousel-buttons">
			    	    <a href="#" id="carousel-prev">&#8592; Previous </a>
			    	    <a href="#" id="carousel-next"> Next &#8594;</a>
			        </div>

					<div id="projects-carousel">

			    		<?php

							global $paged, $wp_query, $wp;

							$args = wp_parse_args($wp->matched_query);

							$temp = $wp_query;

							$wp_query= null;

							$wp_query = new WP_Query();

							$wp_query->query('post_type=post&posts_per_page=-1&author='.$user_id);

							$current = -1;

						?>

						<?php while ($wp_query->have_posts()) : $wp_query->the_post();

							$featured_post = "0";

							$post_price_plan_activation_date = get_post_meta($post->ID, 'post_price_plan_activation_date', true);
							$post_price_plan_expiration_date = get_post_meta($post->ID, 'post_price_plan_expiration_date', true);
							$post_price_plan_expiration_date_noarmal = get_post_meta($current_post, 'post_price_plan_expiration_date_normal', true);
							$todayDate = strtotime(date('m/d/Y h:i:s'));
							$expireDate = $post_price_plan_expiration_date;

							if(!empty($post_price_plan_activation_date)) {

								if(($todayDate < $expireDate) or $post_price_plan_expiration_date == 0) {
									$featured_post = "1";
								}

						} ?>

						<?php if($featured_post == "1") { 

							$current++;

						?>

						<div class="ad-box span3">

							<a class="ad-image" href="<?php the_permalink(); ?>">
			    				<?php require_once(TEMPLATEPATH . '/inc/BFI_Thumb.php'); ?>

								<?php 

									$thumb_id = get_post_thumbnail_id();
									$thumb_url = wp_get_attachment_image_src($thumb_id,'thumbnail-size', true);

									$params = array( 'width' => 440, 'height' => 290, 'crop' => true );
									echo "<img class='add-box-main-image' src='" . bfi_thumb( "$thumb_url[0]", $params ) . "'/>";

									
									$attachments = get_children(array('post_parent' => $post->ID,
												'post_status' => 'inherit',
												'post_type' => 'attachment',
												'post_mime_type' => 'image',
												'order' => 'ASC',
												'orderby' => 'menu_order ID'));

									$currentImg = 0;

									foreach($attachments as $att_id => $attachment) {
										$full_img_url = wp_get_attachment_url($attachment->ID);

										$currentImg++;

										if($currentImg == 2) {

											echo "<img class='add-box-second-image' src='" . bfi_thumb( "$full_img_url", $params ) . "'/>";

										} 

									}

									if($currentImg < 2) {

										echo "<img class='add-box-second-image' src='" . bfi_thumb( "$thumb_url[0]", $params ) . "'/>";										

									}

								
									?>

			    			</a>

			    			<div class="ad-box-content">

			    				<span class="ad-category">
			    					
			    					<?php
 
						        		$category = get_the_category();

						        		if ($category[0]->category_parent == 0) {

											$tag = $category[0]->cat_ID;

											$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
											if (isset($tag_extra_fields[$tag])) {
											    $category_icon_code = $tag_extra_fields[$tag]['category_icon_code'];
											    $category_icon_color = $tag_extra_fields[$tag]['category_icon_color'];
											}

										} else {

											$tag = $category[0]->category_parent;

											$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
											if (isset($tag_extra_fields[$tag])) {
											    $category_icon_code = $tag_extra_fields[$tag]['category_icon_code'];
											    $category_icon_color = $tag_extra_fields[$tag]['category_icon_color'];
											}

										}

										if(!empty($category_icon_code)) {

									?>

					        		<div class="category-icon-box" style="background-color: <?php echo $category_icon_color; ?>;"><?php $category_icon = stripslashes($category_icon_code); echo $category_icon; ?></div>

					        		<?php } 

					        		$category_icon_code = "";

					        		?>

			    				</span>

			    				<a href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 40) ? substr($theTitle,0,37).'...' : $theTitle; echo $theTitle; ?></a>

			    				<?php $post_price = get_post_meta($post->ID, 'post_price', true); ?>
								<div class="add-price"><span><?php echo $post_price; ?></span></div> 

			    			</div>

						</div>

			    		<?php } ?>

			    		<?php endwhile; ?>	
												
						<?php wp_reset_query(); ?>

			    	</div>

			    	<?php wp_enqueue_script( 'jquery-carousel', get_template_directory_uri().'/js/jquery.carouFredSel-6.2.1-packed.js', array('jquery'),'',true); ?>
										
					<script>

						jQuery(document).ready(function () {

							jQuery('#projects-carousel').carouFredSel({
								auto: false,
								prev: '#carousel-prev',
								next: '#carousel-next',
								pagination: "#carousel-pagination",
								mousewheel: true,
								swipe: {
									onMouse: true,
									onTouch: true
								} 
							});

						});
											
					</script>
					<!-- end scripts -->

			    </div>

			    <div class="pane">		

			    	<?php

						global $paged, $wp_query, $wp, $current, $current2;

						$args = wp_parse_args($wp->matched_query);

						$temp = $wp_query;

						$wp_query= null;

						$wp_query = new WP_Query();

						$wp_query->query('post_type=post&posts_per_page=-1&author='.$user_id);

						$featuredCurrent = 0;

					?>

					<?php while ($wp_query->have_posts()) : $wp_query->the_post();

							$featured_post = "0";

							$post_price_plan_activation_date = get_post_meta($post->ID, 'post_price_plan_activation_date', true);
							$post_price_plan_expiration_date = get_post_meta($post->ID, 'post_price_plan_expiration_date', true);
							$post_price_plan_expiration_date_noarmal = get_post_meta($current_post, 'post_price_plan_expiration_date_normal', true);
							$todayDate = strtotime(date('m/d/Y h:i:s'));
							$expireDate = $post_price_plan_expiration_date;

							if(!empty($post_price_plan_activation_date)) {

								if(($todayDate < $expireDate) or $post_price_plan_expiration_date == 0) {
									$featured_post = "1";
								}

						} ?>

						<?php if($featured_post == "1") { 

							$current++;

						?>

						<div class="list-featured-ads">

							<div class="list-feat-ad-image">

								<a class="ad-image" href="<?php the_permalink(); ?>">

								<?php require_once(TEMPLATEPATH . '/inc/BFI_Thumb.php'); ?>

								<?php 

									$thumb_id = get_post_thumbnail_id();
									$thumb_url = wp_get_attachment_image_src($thumb_id,'thumbnail-size', true);

									$params = array( 'width' => 440, 'height' => 290, 'crop' => true );
									echo "<img class='add-box-main-image' src='" . bfi_thumb( "$thumb_url[0]", $params ) . "'/>";

									
									$attachments = get_children(array('post_parent' => $post->ID,
												'post_status' => 'inherit',
												'post_type' => 'attachment',
												'post_mime_type' => 'image',
												'order' => 'ASC',
												'orderby' => 'menu_order ID'));

									$currentImg = 0;

									foreach($attachments as $att_id => $attachment) {
										$full_img_url = wp_get_attachment_url($attachment->ID);

										$currentImg++;

										if($currentImg == 2) {

											echo "<img class='add-box-second-image' src='" . bfi_thumb( "$full_img_url", $params ) . "'/>";

										} 

									}

									if($currentImg < 2) {

										echo "<img class='add-box-second-image' src='" . bfi_thumb( "$thumb_url[0]", $params ) . "'/>";										

									}

								?>

								</a>

							</div>

							<div class="list-feat-ad-content">

								<div class="list-feat-ad-title">

									<a href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 50) ? substr($theTitle,0,47).'...' : $theTitle; echo $theTitle; ?></a>

									<?php $post_price = get_post_meta($post->ID, 'post_price', true); ?>
									<div class="add-price"><span><?php echo $post_price; ?></span></div> 

								</div>

								<div class="list-feat-ad-excerpt">
									<p>
									<?php
										$content = get_the_content();
										echo wp_trim_words( $content , '20' ); 
									?>
									</p>
								</div>

								<div class="read-more">	<a href="<?php the_permalink(); ?>"><?php _e( 'Details', 'agrg' ); ?></a></div>			

							</div>

						</div>


			    	<?php } ?>

			    	<?php endwhile; ?>
												
					<?php wp_reset_query(); ?>

			    </div>

			</div>
        
        </div>

    </section>

    <?php } ?>

    <section id="ads-profile">
        
        <div class="container">

        	<div class="full" style="margin-top: 20px;">

        		<?php 

			    	global $redux_demo; 
					$edit = $redux_demo['edit'];

				?>

        		<h3><?php echo $user_identity; ?> <span class="edit-profile"><a class="" href="<?php echo $edit; ?>"><i class="fa fa-cog"></i><?php printf( __( 'Settings', 'agrg' )); ?></a></span></h3>

        		<div class="span9 first">

	        		<div class="span3 first">

			    		<div class="author-avatar">
			    			<?php require_once(TEMPLATEPATH . '/inc/BFI_Thumb.php'); ?>
			    			<?php 

								$author_avatar_url = get_user_meta($user_ID, "flatads_author_avatar_url", true); 

								if(!empty($author_avatar_url)) {

									$params = array( 'width' => 150, 'height' => 150, 'crop' => true );

									echo "<img class='author-avatar' src='" . bfi_thumb( "$author_avatar_url", $params ) . "' alt='' />";

								} else { 

							?>

								<?php $avatar_url = wpcook_get_avatar_url ( get_the_author_meta('user_email', $user_ID), $size = '150' ); ?>
								<img class="author-avatar" src="<?php echo $avatar_url; ?>" alt="" />

							<?php } ?>
			    		</div>

			    	</div> 

		        	<div class="span6">

		        		<div class="full">

							<h4><?php _e( 'Contact Details', 'agrg' ); ?></h4>

							<span class="author-details"><i class="fa fa-phone"></i><?php the_author_meta('phone', $user_id); ?></span>

							<span class="author-details"><i class="fa fa-envelope"></i><a href="mailto:<?php echo get_the_author_meta('user_email', $$user_id); ?>"><?php echo get_the_author_meta('user_email', $user_id); ?></a></span>

							<span class="author-details"><i class="fa fa-globe"></i><a href="<?php the_author_meta('user_url', $user_id); ?>"><?php the_author_meta('user_url', $user_id); ?></a></span>

							<span class="author-details"><i class="fa fa-map-marker"></i><?php the_author_meta('address', $user_id); ?></span>

						</div>

					</div>

					<h4><?php _e( 'Description', 'agrg' ); ?></h4>

					<div class="author-description"><?php $user_id = $current_user->ID; $author_desc = get_the_author_meta('description', $user_id); echo $author_desc; ?></div>

				</div>

				<div class="span3">

					<h4><?php _e( 'Profile Information', 'agrg' ); ?></h4>

					<span class="ad-detail-info"><?php _e( 'Regular Ads', 'agrg' ); ?>
						<span class="ad-detail"><?php echo $user_post_count = count_user_posts( $user_id ); ?></span>
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

							$wp_query->query('post_type=post&posts_per_page=-1&author='.$user_id);

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

						$result = $wpdb->get_results( "SELECT SUM(ads) AS sum FROM 'wpcads_paypal' WHERE user_id = " . $current_user->ID);

						$allads = $result[0]->sum;

						$unlimited_ads = get_user_meta( $current_user->ID, 'unlimited', $single);

					?>

					<span class="ad-detail-info"><?php _e( 'Featured Ads left', 'agrg' ); ?>
						<span class="ad-detail"><?php if($unlimited_ads == "yes") { ?> ∞ <?php } else { echo $allads; } ?></span>
					</span>

					<div class="pricing-plans">
				 		<?php 

				    	global $redux_demo; 
						$featured_plans = $redux_demo['featured_plans'];

						?>
				 		<a href="<?php echo $featured_plans; ?>" class="btn"><?php _e( 'See Featured Ads Plans', 'agrg' ); ?></a>
				 	</div>

				 	<?php } ?>

				</div>

	    	</div> 

	    	<?php 

				global $redux_demo; 

				$featured_ads_option = $redux_demo['featured-options-on'];

			?>

			<?php if($featured_ads_option == 1) { ?>

	    	<div class="full">

				<h3><?php _e( 'My Featured Ad Plans', 'agrg' ); ?></h3>

				<div class="full" style="margin-left: 0px; padding-top: 20px;">

					<?php 

						global $current_user;
      					get_currentuserinfo();

      					$userID = $current_user->ID;

						$result = $wpdb->get_results( "SELECT * FROM wpcads_paypal WHERE user_id = $userID ORDER BY main_id DESC" );

						if ( $result ) { ?>

						    <div class="full-boxed-pricing">

						        <div class="price-table-header">

									<div class="price-table-header-name"><span><?php _e( 'Name', 'agrg' ); ?></span></div>
									<div class="price-table-header-ads"><span><?php _e( 'Ads', 'agrg' ); ?></span></div>
									<div class="price-table-header-used"><span><?php _e( 'Used', 'agrg' ); ?></span></div>
									<div class="price-table-header-days"><span><?php _e( 'Active', 'agrg' ); ?></span></div>
									<div class="price-table-header-price"><span><?php _e( 'Price', 'agrg' ); ?></span></div>
									<div class="price-table-header-status"><span><?php _e( 'Status', 'agrg' ); ?></span></div>
									<div class="price-table-header-date"><span><?php _e( 'Date', 'agrg' ); ?></span></div>

								</div>

							<?php 

							    foreach ( $result as $info ) { 
							        if($info->status != "in progress") {
							?>

								<div class="price-table-row" <?php if($info->status == "pending") {  ?>style="background: #fce3e3;"<?php } ?>>

									<div class="price-table-row-name"><span><?php echo $info->name; ?></span></div>
									<div class="price-table-row-ads"><span><?php if(empty($info->ads)) { ?> ∞ <?php } else { echo $info->ads; } ?></span></div>
									<div class="price-table-row-used"><span><?php echo $info->used; ?></span></div>
									<div class="price-table-row-days"><span><?php if(empty($info->days)) { ?>∞<?php } else { echo $info->days; } ?> <?php _e( 'Days', 'agrg' ); ?></span></div>
									<div class="price-table-row-price"><span><?php echo $info->price; ?> <?php echo $info->currency; ?></span></div>
									<div class="price-table-row-status"><span <?php if($info->status == "success") {  ?>style="color: #40a000;"<?php } elseif($info->status == "pending") {  ?>style="color: #a02600;"<?php } ?>><?php echo $info->status; ?></span></div>
									<div class="price-table-row-date"><span><?php echo $info->date; ?></span></div>

								</div>

								<?php }
							} ?>

						</div>

					<?php } ?>        	

				</div>

			</div> 

			<?php } ?> 

	    	<div class="hr-full"></div>    	

        	<h3><?php echo $user_identity; ?> <?php _e( 'Premium Regular Ads', 'agrg' ); ?></h3>

			<div class="pane latest-ads-holder">

				<div class="latest-ads-grid-holder">

				<?php

					global $paged, $wp_query, $wp;

					$args = wp_parse_args($wp->matched_query);

					if ( !empty ( $args['paged'] ) && 0 == $paged ) {

						$wp_query->set('paged', $args['paged']);

						$paged = $args['paged'];

					}

					$past_status = array('publish', 'pending', 'draft');

					$cat_id = get_cat_ID(single_cat_title('', false));

					$temp = $wp_query;

					$wp_query= null;

					$wp_query = new WP_Query();

					$wp_query->query('post_type=post&post_status='.$past_status.'&posts_per_page=12&paged='.$paged.'&cat='.$cat_id.'&author='.$user_ID);

					$current = -1;
					$current2 = 0;

					?>

					<?php while ($wp_query->have_posts()) : $wp_query->the_post(); $current++; $current2++; ?>

						<div class="ad-box span3 latest-posts-grid <?php if($current%4 == 0) { echo 'first'; } ?>">

							<a class="ad-image" href="<?php if ( get_post_status ( $ID ) == 'pending' ) { ?>#<?php } else { the_permalink(); } ?>">
				    			<?php require_once(TEMPLATEPATH . '/inc/BFI_Thumb.php'); ?>

								<?php 

									$thumb_id = get_post_thumbnail_id();
									$thumb_url = wp_get_attachment_image_src($thumb_id,'thumbnail-size', true);

									$params = array( 'width' => 440, 'height' => 290, 'crop' => true );
									echo "<img class='add-box-main-image' src='" . bfi_thumb( "$thumb_url[0]", $params ) . "'/>";

										
									$attachments = get_children(array('post_parent' => $post->ID,
										'post_status' => 'inherit',
										'post_type' => 'attachment',
										'post_mime_type' => 'image',
										'order' => 'ASC',
										'orderby' => 'menu_order ID'));

									$currentImg = 0;

									foreach($attachments as $att_id => $attachment) {
										$full_img_url = wp_get_attachment_url($attachment->ID);

										$currentImg++;

										if($currentImg == 2) {

											echo "<img class='add-box-second-image' src='" . bfi_thumb( "$full_img_url", $params ) . "'/>";

										} 

									}

									if($currentImg < 2) {

										echo "<img class='add-box-second-image' src='" . bfi_thumb( "$thumb_url[0]", $params ) . "'/>";										

									}

									
								?>

				    		</a>

				    		<div class="ad-box-content">

				    			<span class="ad-category">
				    					
				    				<?php

							        	$category = get_the_category();

							        	if ($category[0]->category_parent == 0) {

											$tag = $category[0]->cat_ID;

											$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
											if (isset($tag_extra_fields[$tag])) {
												$category_icon_code = $tag_extra_fields[$tag]['category_icon_code'];
												$category_icon_color = $tag_extra_fields[$tag]['category_icon_color'];
											}

										} else {

											$tag = $category[0]->category_parent;

											$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
											if (isset($tag_extra_fields[$tag])) {
												$category_icon_code = $tag_extra_fields[$tag]['category_icon_code'];
												$category_icon_color = $tag_extra_fields[$tag]['category_icon_color'];
											}

										}

										if(!empty($category_icon_code)) {

									?>

						        	<div class="category-icon-box" style="background-color: <?php echo $category_icon_color; ?>;"><?php $category_icon = stripslashes($category_icon_code); echo $category_icon; ?></div>

						        	<?php } 

						        	$category_icon_code = "";

						        	?>

				    			</span>

				    			<a href="<?php if ( get_post_status ( $ID ) == 'pending' ) { ?>#<?php } else { the_permalink(); } ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 40) ? substr($theTitle,0,37).'...' : $theTitle; echo $theTitle; ?> <?php if ( get_post_status ( $ID ) == 'pending' ) { ?><?php _e( '(Pending)', 'agrg' ); ?><?php } ?></a>

				    			<?php $post_price = get_post_meta($post->ID, 'post_price', true); ?>
								<div class="add-price"><span><?php echo $post_price; ?></span></div> 

								<?php 

									global $redux_demo; 
									$edit_post_page_id = $redux_demo['edit_post'];
									$postID = $post->ID;

									global $wp_rewrite;
									if ($wp_rewrite->permalink_structure == '') {
									//we are using ?page_id
										$edit_post = $edit_post_page_id."&post=".$postID;
									} else {
									//we are using permalinks
										$edit_post = $edit_post_page_id."?post=".$postID;
									}

								?>

								<a class="author-edit-post" href="<?php echo $edit_post; ?>"><i class="fa fa-pencil"></i><?php _e( 'Edit', 'agrg' ); ?></a>

								<form onSubmit="return confirm('Do you really want to delete this?');" name="theForm<?php the_ID(); ?>" class="delete-listing" action="" method="post">

									<input type="hidden" name="deletepostid" value="<?php the_ID(); ?>" />

									<a class='author-delete-post' onclick='return confirm("Are you sure you want to delete this?")' href='javascript:document.theForm<?php the_ID(); ?>.submit();'><i class='fa fa-trash-o'></i><?php _e( 'Delete', 'agrg' ); ?></a>

			       			  	</form>

				    		</div>

						</div>

					<?php endwhile; ?>


				</div>
											
			<!-- Begin wpcrown_pagination-->	
			<?php get_template_part('pagination'); ?>
			<!-- End wpcrown_pagination-->	
																
			<?php wp_reset_query(); ?>

			</div>

        </div>

    </section>

    <script>
		// perform JavaScript after the document is scriptable.
		jQuery(function() {
			jQuery("ul.tabs").tabs("> .pane", {effect: 'fade', fadeIn: 200});
		});
	</script>

<?php get_footer(); ?>