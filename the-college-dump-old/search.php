<?php
/**
 * The template for displaying Category pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage FlatAds
 * @since FlatAds 1.0
 */

get_header(); ?>

	<?php 

		// Retrieve the URL variables (using PHP).
		$keyword = $_GET['s'];
		$post_location_search = $_GET['post_location'];
		$category_name = $_GET['category_name'];
		$geo_location = $_GET['geo-location'];
		$geo_radius_search = $_GET['geo-radius-search'];
		$geo_search_lat = $_GET['geo-search-lat'];
		$geo_search_lng = $_GET['geo-search-lng'];

	if($category_name != "All") {
		$catSearchID = get_cat_ID( $category_name );
	} else {
		$catSearchID = '-1';
	}

	?>

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

						global $paged, $wp_query, $wp;


						$args = array(
							'post_type' => 'post',
							's' => $keyword,
							'cat' => $catSearchID,
							'posts_per_page' => -1,
						);

						$wp_query= null;

						$wp_query = new WP_Query($args);

						$currentPost = 0;

						$emptyPost = 0;

						while ($wp_query->have_posts()) : $wp_query->the_post();

						if($post_location_search == 'All') { 

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

								if($geo_location == "on") {

									$distance = 6371 * acos(sin($post_latitude / 57.2958) * sin($geo_search_lat / 57.2958) +cos($post_latitude / 57.2958) * cos($geo_search_lat / 57.2958) *cos($geo_search_lng / 57.2958 - $post_longitude / 57.2958));

									if( $distance <= $geo_radius_search ) {

										$emptyPost++;
									?>

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

									<?php } ?>

								<?php } else { $emptyPost++; ?>

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

							<?php } } ?>

						<?php } else { 

							$post_location = get_post_meta($post->ID, 'post_location', true); 

			    			if($post_location == $post_location_search) {

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

									if($geo_location == on) {

										$distance = 6371 * acos(sin($post_latitude / 57.2958) * sin($geo_search_lat / 57.2958) +cos($post_latitude / 57.2958) * cos($geo_search_lat / 57.2958) *cos($geo_search_lng / 57.2958 - $post_longitude / 57.2958));

										if( $distance <= $geo_radius_search ) {

											$emptyPost++;

										?>

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

										<?php } ?>

									<?php } else { $emptyPost++; ?>

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

								<?php } } ?>

							<?php } ?>

						<?php } ?>

					<?php endwhile; ?>	

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

	<section id="seacrh-result-title">

		 <div class="container">

        	<h2><?php printf( __( 'Search Results for: ', 'agrg' )); ?> <?php echo $keyword ?></h2>

        </div>

	</section>

    <section id="featured-ads">
        
        <div class="container">
            
            <h3><?php _e( 'Premium Featured Ads', 'agrg' ); ?></h3>
            
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

						global $paged, $wp_query, $wp, $current, $current2;

						$args = wp_parse_args($wp->matched_query);

						$temp = $wp_query;

						$wp_query= null;

						$wp_query = new WP_Query();

						$wp_query->query('post_type=post&s='.$keyword.'&posts_per_page=-1&cat='.$catSearchID);

						$featuredCurrent = 0;

					?>

					<?php while ($wp_query->have_posts()) : $wp_query->the_post(); 

						$featured_post = get_post_meta($post->ID, 'featured_post', true); ?>

					<?php if($featured_post == "1") { $current++; $current2++; $featuredCurrent++; 

						if($geo_location == 'on') {

							if($post_location_search == 'All') {

								$emptyPost++;

								$post_latitude = get_post_meta($post->ID, 'post_latitude', true);
								$post_longitude = get_post_meta($post->ID, 'post_longitude', true);

								if(!empty($post_latitude)) { 

									$distance = 6371 * acos(sin($post_latitude / 57.2958) * sin($geo_search_lat / 57.2958) +cos($post_latitude / 57.2958) * cos($geo_search_lat / 57.2958) *cos($geo_search_lng / 57.2958 - $post_longitude / 57.2958));

									if( $distance <= $geo_radius_search ) {

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

								<?php } ?>

							<?php } else { 

								$post_location = get_post_meta($post->ID, 'post_location', true); 

				    			if($post_location == $post_location_search) {

				    				$emptyPost++;

				    				$post_latitude = get_post_meta($post->ID, 'post_latitude', true);
									$post_longitude = get_post_meta($post->ID, 'post_longitude', true);

									if(!empty($post_latitude)) { 

										$distance = 6371 * acos(sin($post_latitude / 57.2958) * sin($geo_search_lat / 57.2958) +cos($post_latitude / 57.2958) * cos($geo_search_lat / 57.2958) *cos($geo_search_lng / 57.2958 - $post_longitude / 57.2958));

										if( $distance <= $geo_radius_search ) {

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

									<?php } ?>

								<?php } ?>

							<?php } ?>

						<?php } else { ?>

							<?php if($post_location_search == 'All') {

								$emptyPost++;

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

							<?php } else { 

								$post_location = get_post_meta($post->ID, 'post_location', true); 

				    			if($post_location == $post_location_search) {

				    				$emptyPost++;

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

							<?php } ?>

						<?php } ?>

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

						global $paged, $wp_query, $wp;

						$args = wp_parse_args($wp->matched_query);

						$temp = $wp_query;

						$wp_query= null;

						$wp_query = new WP_Query();

						$wp_query->query('post_type=post&s='.$keyword.'&posts_per_page=-1&cat='.$catSearchID);

						$featuredCurrent = 0;

					?>

					<?php while ($wp_query->have_posts()) : $wp_query->the_post(); 

						$featured_post = get_post_meta($post->ID, 'featured_post', true); ?>

					<?php if($featured_post == "1") { $current++; $current2++; $featuredCurrent++; 

						if($geo_location == 'on') {

							if($post_location_search == 'All') {

								$emptyPost++;

								$post_latitude = get_post_meta($post->ID, 'post_latitude', true);
								$post_longitude = get_post_meta($post->ID, 'post_longitude', true);

								if(!empty($post_latitude)) { 

									$distance = 6371 * acos(sin($post_latitude / 57.2958) * sin($geo_search_lat / 57.2958) +cos($post_latitude / 57.2958) * cos($geo_search_lat / 57.2958) *cos($geo_search_lng / 57.2958 - $post_longitude / 57.2958));

									if( $distance <= $geo_radius_search ) {

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

								<?php } ?>

							<?php } else { 

								$post_location = get_post_meta($post->ID, 'post_location', true); 

				    			if($post_location == $post_location_search) {

				    				$emptyPost++;

				    				$post_latitude = get_post_meta($post->ID, 'post_latitude', true);
									$post_longitude = get_post_meta($post->ID, 'post_longitude', true);

									if(!empty($post_latitude)) { 

										$distance = 6371 * acos(sin($post_latitude / 57.2958) * sin($geo_search_lat / 57.2958) +cos($post_latitude / 57.2958) * cos($geo_search_lat / 57.2958) *cos($geo_search_lng / 57.2958 - $post_longitude / 57.2958));

										if( $distance <= $geo_radius_search ) {

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

									<?php } ?>

								<?php } ?>

							<?php } ?>

						<?php } else { ?>

							<?php if($post_location_search == 'All') {

								$emptyPost++;

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

							<?php } else { 

								$post_location = get_post_meta($post->ID, 'post_location', true); 

				    			if($post_location == $post_location_search) {

				    				$emptyPost++;

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

							<?php } ?>

						<?php } ?>

			    	<?php } ?>

			    	<?php endwhile; ?>
												
					<?php wp_reset_query(); ?>

			    </div>

			    <?php if($emptyPost == 0) { ?>
					<div class="full view-empty">
						<p><?php _e( 'No results found for the selected search criteria.', 'agrg' ); ?></p>
					</div>
				<?php } ?>

			</div>
        
        </div>

    </section>

    <section id="ads-homepage">
        
        <div class="container">

        	<h3><?php _e( 'Regular Ads', 'agrg' ); ?></h3>

			<div class="pane latest-ads-holder">

				<div class="latest-ads-grid-holder">

				<?php

					global $paged, $wp_query, $wp;

					$args = wp_parse_args($wp->matched_query);

					$temp = $wp_query;

					$wp_query= null;

					$wp_query = new WP_Query();

					$wp_query->query('post_type=post&s='.$keyword.'&posts_per_page=-1&cat='.$catSearchID);

					$current = -1;
					$current2 = 0;

					$emptyPost2 = 0;

					?>

					<?php while ($wp_query->have_posts()) : $wp_query->the_post(); 

					if($geo_location == 'on') {

						if($post_location_search == 'All') {

							$current++; $current2++; $emptyPost2++;

							$post_latitude = get_post_meta($post->ID, 'post_latitude', true);
							$post_longitude = get_post_meta($post->ID, 'post_longitude', true);

							if(!empty($post_latitude)) { 

								$distance = 6371 * acos(sin($post_latitude / 57.2958) * sin($geo_search_lat / 57.2958) +cos($post_latitude / 57.2958) * cos($geo_search_lat / 57.2958) *cos($geo_search_lng / 57.2958 - $post_longitude / 57.2958));

								if( $distance <= $geo_radius_search ) {

							?>

							<div class="ad-box span3 latest-posts-grid <?php if($current%4 == 0) { echo 'first'; } ?>">

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

					    			<a href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 50) ? substr($theTitle,0,47).'...' : $theTitle; echo $theTitle; ?></a>

					    			<?php $post_price = get_post_meta($post->ID, 'post_price', true); ?>
									<div class="add-price"><span><?php echo $post_price; ?></span></div> 

					    		</div>

							</div>

							<?php } ?>

							<?php } ?>

							<?php } else { 

								$post_location = get_post_meta($post->ID, 'post_location', true); 

				    			if($post_location == $post_location_search) {

				    				$current++; $current2++; $emptyPost2++;

				    				$post_latitude = get_post_meta($post->ID, 'post_latitude', true);
									$post_longitude = get_post_meta($post->ID, 'post_longitude', true);

									if(!empty($post_latitude)) { 

										$distance = 6371 * acos(sin($post_latitude / 57.2958) * sin($geo_search_lat / 57.2958) +cos($post_latitude / 57.2958) * cos($geo_search_lat / 57.2958) *cos($geo_search_lng / 57.2958 - $post_longitude / 57.2958));

										if( $distance <= $geo_radius_search ) {

									?>

								<div class="ad-box span3 latest-posts-grid <?php if($current%4 == 0) { echo 'first'; } ?>">

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

						    			<a href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 50) ? substr($theTitle,0,47).'...' : $theTitle; echo $theTitle; ?></a>

						    			<?php $post_price = get_post_meta($post->ID, 'post_price', true); ?>
										<div class="add-price"><span><?php echo $post_price; ?></span></div> 

						    		</div>

								</div>

								<?php } ?>

							<?php } ?>

							<?php } ?>

							<?php } ?>

						<?php } else { ?>

							<?php if($post_location_search == 'All') {

							$current++; $current2++; $emptyPost2++;

						?>

							<div class="ad-box span3 latest-posts-grid <?php if($current%4 == 0) { echo 'first'; } ?>">

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

					    			<a href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 50) ? substr($theTitle,0,47).'...' : $theTitle; echo $theTitle; ?></a>

					    			<?php $post_price = get_post_meta($post->ID, 'post_price', true); ?>
									<div class="add-price"><span><?php echo $post_price; ?></span></div> 

					    		</div>

							</div>

							<?php } else { 

								$post_location = get_post_meta($post->ID, 'post_location', true); 

				    			if($post_location == $post_location_search) {

				    				$current++; $current2++; $emptyPost2++;

								?>

								<div class="ad-box span3 latest-posts-grid <?php if($current%4 == 0) { echo 'first'; } ?>">

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

						    			<a href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 50) ? substr($theTitle,0,47).'...' : $theTitle; echo $theTitle; ?></a>

						    			<?php $post_price = get_post_meta($post->ID, 'post_price', true); ?>
										<div class="add-price"><span><?php echo $post_price; ?></span></div> 

						    		</div>

								</div>

								<?php } ?>

							<?php } ?>

						<?php } ?>

					<?php endwhile; ?>

				</div>
																
			<?php wp_reset_query(); ?>

			<?php if($emptyPost2 == 0) { ?>
				<div class="full view-empty">
					<p><?php _e( 'No results found for the selected search criteria.', 'agrg' ); ?></p>
				</div>
			<?php } ?>

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