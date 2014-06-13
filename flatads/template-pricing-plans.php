<?php
/**
 * Template name: Pricing Plans
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

$successMsg = '';

get_header(); ?>

<?php 

	$page = get_page($post->ID);
	$current_page_id = $page->ID;

	$page_slider = get_post_meta($current_page_id, 'page_slider', true); 

?>

<?php if($page_slider == "LayerSlider") : ?>

	<section id="layerslider">

		<?php

			$page_layer_slider_shortcode = get_post_meta($current_page_id, 'layerslider_shortcode', true);

			if(!empty($page_layer_slider_shortcode))
			{
		?>

			<?php echo do_shortcode($page_layer_slider_shortcode); ?>

		<?php } else { ?>

			<?php echo do_shortcode('[layerslider id="1"]'); ?>

		<?php } ?>

	</section>

<?php elseif ($page_slider == "Big Map") : ?>

	<section id="big-map">

		<div id="flatads-main-map"></div>

		<script type="text/javascript">
		var mapDiv,
			map,
			infobox;
		jQuery(document).ready(function($) {

			mapDiv = $("#flatads-main-map");
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

						$wp_query->query('post_type=post&posts_per_page=-1');

						


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

						if(!empty($post_latitude)) {
						 
							if( ($wp_query->current_post + 1) < ($wp_query->post_count) ) { ?>

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

							<?php } else { ?>

								{
									latLng: [<?php echo $post_latitude; ?>,<?php echo $post_longitude; ?>],
									options: {
										icon: "<?php echo $iconPath; ?>",
										shadow: "<?php echo get_template_directory_uri() ?>/images/shadow.png",
									},
									data: '<div class="marker-holder"><div class="marker-content"></div></div>'
								}

					<?php } } endwhile; ?>	

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
		      	max: 1000,
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
														<option value="<?php echo $cat->cat_slug; ?>">- <?php echo $cat->cat_name; ?></option>
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

<?php endif; ?>

    <section id="seacrh-result-title">

		 <div class="container">

        	<div class="span9 first"> 
        		<h2><?php the_title(); ?></h2>
        	</div>

        	<div class="span3"><h4 style="margin-top: 7px;"><?php _e('Account Overview', 'agrg') ?></h4></div>

        </div>

	</section>

    <section class="ads-main-page">

    	<div class="container">

	    	<div class="span9 first view-pricing-plans" style="padding: 40px 0;">

				<div class="ad-detail-content" style="margin-bottom: 30px;">

	    			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							
					<?php the_content(); ?>
															
					<?php endwhile; endif; ?>

	    		</div>

				<?php if(!empty($successMsg)) { ?>

				<div class="full" style="margin-left: 0px;"><div class="box-notification-content"><?php echo $successMsg; ?></div></div>

				<?php } ?>

				<?php

					global $paged, $wp_query, $wp;

					$args = wp_parse_args($wp->matched_query);

					$temp = $wp_query;

					$wp_query= null;

					$wp_query = new WP_Query();

					$wp_query->query('post_type=price_plan&posts_per_page=-1');

					$current = -1;
					$current2 = 0;

					?>

					<?php while ($wp_query->have_posts()) : $wp_query->the_post(); $current++; $current2++; ?>


					<div class="span3 <?php if($current%3==0) { echo 'first'; } ?>">

						<div class="product-wrapper">

							<?php $post_price = get_post_meta($post->ID, 'plan_price', true); ?>

							<div class="product-title">
								<?php the_title(); ?>
								<span class="price">
			    					<?php 
			    						global $redux_demo; 
			    						$currency_code = $redux_demo['currency_code']; 

			    						if($currency_code == "USD") {
			    							echo "$";
			    						} elseif($currency_code == "AUD") {
			    							echo "$";
			    						} elseif($currency_code == "CAD") {
			    							echo "$";
			    						} elseif($currency_code == "CZK") {
			    							echo "Kč";
			    						} elseif($currency_code == "DKK") {
			    							echo "kr";
			    						} elseif($currency_code == "EUR") {
			    							echo "€";
			    						} elseif($currency_code == "HKD") {
			    							echo "$";
			    						} elseif($currency_code == "HUF") {
			    							echo "Ft";
			    						} elseif($currency_code == "JPY") {
			    							echo "¥";
			    						} elseif($currency_code == "NOK") {
			    							echo "kr";
			    						} elseif($currency_code == "NZD") {
			    							echo "$";
			    						} elseif($currency_code == "PLN") {
			    							echo "zł";
			    						} elseif($currency_code == "GBP") {
			    							echo "£";
			    						} elseif($currency_code == "SEK") {
			    							echo "kr";
			    						} elseif($currency_code == "SGD") {
			    							echo "$";
			    						} elseif($currency_code == "CHF") {
			    							echo "CHF";
			    						}
			    								

			    						echo $post_price; 
			    					?>
			    				</span>
							</div>

							<div class="product-details">

			    				<div class="description"><?php the_content(); ?></div>

				    			<form method="post" action="<?php echo get_template_directory_uri(); ?>/paypal/form-handler.php?func=addrow">
									<input type="hidden" name="AMT" value="<?php echo $post_price; ?>" />
									<input type="hidden" name="PAYMENTREQUEST_0_DESC" value="<?php the_title(); ?>" />
									<?php global $redux_demo; $currency_code = $redux_demo['currency_code']; ?>
									<input type="hidden" name="CURRENCYCODE" value="<?php echo $currency_code; ?>">

									<?php $planID = uniqid(); ?>
									<input type="hidden" name="PAYMENTREQUEST_0_CUSTOM" value="<?php echo $planID; ?>">

									<input type="hidden" name="user_ID" value="<?php echo $user_ID; ?>">

									<input type="hidden" name="plan_name" value="<?php the_title(); ?>">

									<?php $plan_ads = get_post_meta($post->ID, 'featured_ads', true); ?>
				    				<input type="hidden" name="plan_ads" value="<?php echo $plan_ads; ?>">

				    				<input type="hidden" name="plan_price" value="<?php echo $post_price; ?>">

				    				<?php $plan_time = get_post_meta($post->ID, 'plan_time', true); ?>
				    				<input type="hidden" name="plan_time" value="<?php echo $plan_time; ?>">

				    				<?php $date = date('d/m/Y H:i:s'); ?>
				    				<input type="hidden" name="date" value="<?php echo $date; ?>">

				    				<input type="hidden" name="url" value="<?php echo get_template_directory_uri(); ?>">

									<?php global $redux_demo; $paypal_success = $redux_demo['paypal_success']; $paypal_fail = $redux_demo['paypal_fail']; ?>
											  
									<?php if ( isset($paypal_success) ) { ?>
										<input type="hidden" name="RETURN_URL" value="<?php echo $paypal_success; ?>" />
									<?php } ?>
											  
									<?php if ( isset($paypal_fail) ) { ?>
										<input type="hidden" name="CANCEL_URL" value="<?php echo $paypal_fail; ?>" />
									<?php } ?>
											  
									<input type="hidden" name="func" value="start" />

									<button class="btn form-submit" id="submit-plan" name="op" value="Buy Now!" type="submit"><?php printf( __( 'Buy Now!', 'agrg' )); ?></button>

								</form>

			    				<div class="disclaimer"><?php _e( 'No worries, it is 100% Secure!', 'agrg' ); ?></div>
			    			</div>

						</div>

					</div>


					<?php endwhile; ?>
															
					<?php $wp_query = null; $wp_query = $temp;?>

	    		<div id="ad-comments">

	    			<?php comments_template( '' ); ?>  

	    		</div>

	    	</div>

	    	<div class="span3" style="padding: 30px 0;">

	    		<span class="ad-detail-info"><?php _e( 'Regular Ads', 'agrg' ); ?>
					<span class="ad-detail"><?php echo $user_post_count = count_user_posts( $user_ID ); ?></span>
				</span>

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
				 	<a href="<?php echo $featured_plans; ?>" class="btn" style="margin-bottom: 30px;"><?php _e( 'See Featured Ads Plans', 'agrg' ); ?></a>
				 </div>

		    	<div class="cat-widget" style="margin-top: 10px;">

		    		<div class="cat-widget-title"><h4><?php _e( 'My Featured Ads', 'agrg' ); ?></h4></div>

		    		<div class="cat-widget-content">

		    			<ul> 

						  	<?php

								global $paged, $wp_query, $wp;

								$args = wp_parse_args($wp->matched_query);

								if ( !empty ( $args['paged'] ) && 0 == $paged ) {

									$wp_query->set('paged', $args['paged']);

									$paged = $args['paged'];

								}


								$current = -1;
								$featuredCurrent = 0;

								$author = get_the_author();

								$user_ID = get_current_user_id();

								$popularpost = new WP_Query( array( 'posts_per_page' => '-1', 'posts_type' => 'post', 'paged' => $paged, 'order' => 'DESC', 'author' => $user_ID ) );

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

		    	<?php get_sidebar('pages'); ?>

	    	</div>

	    </div>

    </section>

<?php get_footer(); ?>