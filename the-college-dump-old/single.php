<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage FlatAds
 * @since FlatAds 1.0
 */

global $redux_demo; 
if(!empty($redux_demo['add-detail-page-version'])) {
	$add_detail_page_version = $redux_demo['add-detail-page-version'];

	if($add_detail_page_version == '2')
	{
		include (TEMPLATEPATH . "/template-single-version2.php");
	    exit;
	}
}

get_header(); ?>
	
	<?php while ( have_posts() ) : the_post(); ?>


<?php 

global $redux_demo; 

global $current_user; get_currentuserinfo(); $user_ID == $current_user->ID;

$contact_email = get_the_author_meta( 'user_email', $user_ID );
$wpcrown_contact_email_error = $redux_demo['contact-email-error'];
$wpcrown_contact_name_error = $redux_demo['contact-name-error'];
$wpcrown_contact_message_error = $redux_demo['contact-message-error'];
$wpcrown_contact_thankyou = $redux_demo['contact-thankyou-message'];

global $nameError;
global $emailError;
global $commentError;
global $subjectError;
global $humanTestError;

//If the form is submitted
if(isset($_POST['submitted'])) {
	
		//Check to make sure that the name field is not empty
		if(trim($_POST['contactName']) === '') {
			$nameError = $wpcrown_contact_name_error;
			$hasError = true;
		} elseif(trim($_POST['contactName']) === 'Name*') {
			$nameError = $wpcrown_contact_name_error;
			$hasError = true;
		}	else {
			$name = trim($_POST['contactName']);
		}

		//Check to make sure that the subject field is not empty
		if(trim($_POST['subject']) === '') {
			$subjectError = $wpcrown_contact_subject_error;
			$hasError = true;
		} elseif(trim($_POST['subject']) === 'Subject*') {
			$subjectError = $wpcrown_contact_subject_error;
			$hasError = true;
		}	else {
			$subject = trim($_POST['subject']);
		}
		
		//Check to make sure sure that a valid email address is submitted
		if(trim($_POST['email']) === '')  {
			$emailError = $wpcrown_contact_email_error;
			$hasError = true;
		} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
			$emailError = $wpcrown_contact_email_error;
			$hasError = true;
		} else {
			$email = trim($_POST['email']);
		}
			
		//Check to make sure comments were entered	
		if(trim($_POST['comments']) === '') {
			$commentError = $wpcrown_contact_message_error;
			$hasError = true;
		} else {
			if(function_exists('stripslashes')) {
				$comments = stripslashes(trim($_POST['comments']));
			} else {
				$comments = trim($_POST['comments']);
			}
		}

		//Check to make sure that the human test field is not empty
		if(trim($_POST['humanTest']) != '8') {
			$humanTestError = "Not Human :(";
			$hasError = true;
		} else {

		}
			
		//If there is no error, send the email
		if(!isset($hasError)) {

			$emailTo = $contact_email;
			$subject = $subject;	
			$body = "Name: $name \n\nEmail: $email \n\nMessage: $comments";
			$headers = 'From <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;
			
			wp_mail($emailTo, $subject, $body, $headers);

			$emailSent = true;

	}
}

?>

	<section id="ad-page-title">
        
        <div class="container">

        	<div class="span9 first"> 
        		<h2><?php the_title(); ?>
        			<?php global $current_user; get_currentuserinfo(); 
          			if ($post->post_author == $current_user->ID) { ?>

          			<?php 
						global $redux_demo; 
						$edit_post_page_id = $redux_demo['edit_post'];
						$postID = $post->ID;

						global $wp_rewrite;
						if ($wp_rewrite->permalink_structure == '')
						//we are using ?page_id
							$edit_post = $edit_post_page_id."&post=".$postID;
						else
						//we are using permalinks
							$edit_post = $edit_post_page_id."?post=".$postID;

						?>
						<a href="<?php echo $edit_post; ?>">(Edit)</a>

          			<?php } ?></h2> 

        	</div>

        	<div class="span3"> <span class="ad-page-price"><h2><?php $post_price = get_post_meta($post->ID, 'post_price', true); echo $post_price; ?></h2></span> </div>

        </div>

    </section>

    <section id="ad-page-header">
        
        <div class="container">

        	<div class="span12">

        		<script type='text/javascript'>
	  				jQuery(function() {
						jQuery('.flexslider').flexslider();
					});
				</script>

				<div class="flexslider">
					
					<ul class="slides">

						<?php require_once(TEMPLATEPATH . '/inc/BFI_Thumb.php'); ?>

						<?php 

						$params = array( 'width' => 950, 'height' => 560, 'crop' => true );

						$attachments = get_children(array('post_parent' => $post->ID,
							'post_status' => 'inherit',
							'post_type' => 'attachment',
							'post_mime_type' => 'image',
							'order' => 'ASC',
							'orderby' => 'menu_order ID'));

						foreach($attachments as $att_id => $attachment) {
							$full_img_url = wp_get_attachment_url($attachment->ID);

							echo "<li><img class='flexslider-image' src='" . bfi_thumb( "$full_img_url", $params ) . "'/></li>";		

						} 

						if(empty($full_img_url)) {

							echo "<style type=\"text/css\"> ul.flex-direction-nav { display: none; } </style>";
							
						}

						?>

					</ul>
							
				</div>

        	</div>

        </div>

    </section>

    <section class="ads-main-page">

    	<div class="container">

	    	<div class="span9 first">

	    		<?php 

	    			$post_video = get_post_meta($post->ID, 'post_video', true);

	    			if(!empty($post_video)) {

	    		?>

	    		<div id="ad-video-text"><span><i class="fa fa-youtube-play"></i><?php _e( 'Video', 'agrg' ); ?></span></div>

	    		<div id="ad-video"><?php echo $post_video; ?></div>

	    		<?php } ?>


	    		<?php

					$post_latitude = get_post_meta($post->ID, 'post_latitude', true);
					$post_longitude = get_post_meta($post->ID, 'post_longitude', true);
					$post_address = get_post_meta($post->ID, 'post_address', true);

					if(!empty($post_latitude)) {

				?>

			    <div id="single-page-map">

			    	<div id="ad-address"><span><i class="fa fa-map-marker"></i><?php echo $post_address; ?></span></div>

					<div id="single-page-main-map"></div>

					<script type="text/javascript">
					var mapDiv,
						map,
						infobox;
					jQuery(document).ready(function($) {

						mapDiv = $("#single-page-main-map");
						mapDiv.height(400).gmap3({
							map: {
								options: {
									"center": [<?php echo $post_latitude; ?>,<?php echo $post_longitude; ?>]
									,"zoom": 16
									,"draggable": true
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
							 		 	});

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

					});
					</script>

				</div>

				<?php } ?>

				<table class="ad-detail-half-box">
					<tr>
						<td>
							<span class="ad-details-title"><?php the_author_posts_link(); ?></span>

				    		<?php $curauth = get_user_by( 'id', get_queried_object()->post_author ); // get the info about the current author ?>
																		
							<?php
								$wpcrown_author_address = $curauth->address;
																																
								if(!empty($wpcrown_author_address)) {
							?>
								<span class="ad-detail-info">
					    			<i class="fa fa-map-marker"></i> <span class="ad-details"><?php echo $wpcrown_author_address; ?></span>
								</span>
							<?php
								} 		
							?>


				    		<?php $curauth = get_user_by( 'id', get_queried_object()->post_author ); // get the info about the current author ?>
																		
							<?php
																			
								$wpcrown_author_phone = $curauth->phone;
																																
								if(!empty($wpcrown_author_phone)) {
							?>
								<span class="ad-detail-info"> 
				    				<i class="fa fa-phone-square"></i> <span class="ad-details"><?php echo $wpcrown_author_phone; ?></span>
								</span>
							<?php
								} 		
							?>

							<?php $curauth = get_user_by( 'id', get_queried_object()->post_author ); // get the info about the current author ?>
																		
							<?php
								$wpcrown_author_web = $curauth->user_url;
																																
								if(!empty($wpcrown_author_web)) {
							?>
								<span class="ad-detail-info">
					    			<i class="fa fa-globe"></i> <span class="ad-details"><a href="<?php echo $wpcrown_author_web; ?>"><?php echo $wpcrown_author_web; ?></a></span>
								</span>
							<?php } ?>

						</td>
					</tr>
					<tr>
						<td>
							<span class="ad-detail-info"><?php _e( 'Category', 'agrg' ); ?> <span class="ad-detail">
				    			<?php 
									$category = get_the_category();
									if ($category) {
										echo '<a href="' . get_category_link( $category[0]->term_id ) . '" title="' . sprintf( __( "View all posts in %s", "agrg" ), $category[0]->name ) . '" ' . '>' . $category[0]->name.'</a> ';
									}
								?></span>
							</span>

							<span class="ad-detail-info"><?php _e( 'Added', 'agrg' ); ?> <span class="ad-detail">
				    			<?php the_time('M j, Y') ?></span>
							</span>

							<?php 
								$post_location = get_post_meta($post->ID, 'post_location', true); 
								if(!empty($post_location)) {
							?>

							<span class="ad-detail-info"><?php _e( 'Location', 'agrg' ); ?> <span class="ad-detail">
				    			<?php echo $post_location; ?></span>
							</span>

							<?php } ?>

							<span class="ad-detail-info"><?php _e( 'Views', 'agrg' ); ?> <span class="ad-detail">
				    			<?php echo wpb_get_post_views(get_the_ID()); ?></span>
							</span>


							<?php if(function_exists('the_ratings')) { ?>

								<span class="ad-detail-info"><?php _e( 'Rating', 'agrg' ); ?> 
									<span class="ad-detail"><?php the_ratings(); ?></span>
								</span>

							<?php } ?>
						</td>
					</tr>
				</table>

	    		<ul class="links">

					<li class="service-links-pinterest-button">
						<a href="//www.pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=&amp;description=<?php the_title(); ?>" data-pin-do="buttonPin" data-pin-config="beside"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
						<script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script>
					</li>

					<li class="service-links-facebook-share">
						<div id="fb-root"></div>
						<script>(function(d, s, id) {
							var js, fjs = d.getElementsByTagName(s)[0];
							if (d.getElementById(id)) return;
							js = d.createElement(s); js.id = id;
							js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=247363645312964";
							fjs.parentNode.insertBefore(js, fjs);
							}(document, 'script', 'facebook-jssdk'));</script>
						<div class="fb-share-button" data-href="<?php the_permalink(); ?>" data-type="button_count"></div>
					</li>

					<li class="service-links-google-plus-one last">
						<!-- Place this tag where you want the share button to render. -->
						<div class="g-plus" data-action="share" data-annotation="bubble"></div>

						<!-- Place this tag after the last share tag. -->
						<script type="text/javascript">
							(function() {
								var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
								po.src = 'https://apis.google.com/js/platform.js';
								var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
							})();
						</script>
					</li>

					<li class="service-links-twitter-widget first">
						<iframe id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true" src="http://platform.twitter.com/widgets/tweet_button.1384205748.html#_=1384949257081&amp;count=horizontal&amp;counturl=<?php the_permalink(); ?>&amp;id=twitter-widget-0&amp;lang=en&amp;original_referer=<?php the_permalink(); ?>&amp;size=m&amp;text=<?php the_title(); ?>&amp;url=<?php the_permalink(); ?>&amp;via=drupads" class="twitter-share-button service-links-twitter-widget twitter-tweet-button twitter-count-horizontal" title="Twitter Tweet Button" data-twttr-rendered="true" style="width: 107px; height: 20px;"></iframe>
					</li>
				</ul>

				<div class="ad-detail-content">

	    			<?php echo the_content(); ?>

	    			<?php wp_link_pages(); ?>

	    		</div>

				<div class="ads-tags">

					<i class="fa fa-tag"></i><span><?php the_tags('','',''); ?></span>

				</div>

				<div class="full">

					<h2><?php _e( 'Contact Owner', 'agrg' ); ?></h2>

					<div id="contact-ad-owner">

						<div class="contact-ad-owner-arrow"></div>

						<?php if(isset($emailSent) && $emailSent == true) { ?>

							<div class="full">
								<h5><?php echo $wpcrown_contact_thankyou ?></h5> 
							</div>

						<?php } else { ?>

						<?php if($nameError != '') { ?>
							<div class="full">
								<h5><?php echo $nameError;?></h5> 
							</div>										
						<?php } ?>
														
						<?php if($emailError != '') { ?>
							<div class="full">
								<h5><?php echo $emailError;?></h5>
							</div>
						<?php } ?>

						<?php if($subjectError != '') { ?>
							<div class="full">
								<h5><?php echo $subjectError;?></h5>  
							</div>
						<?php } ?>
														
						<?php if($commentError != '') { ?>
							<div class="full">
								<h5><?php echo $commentError;?></h5>
							</div>
						<?php } ?>

						<?php if($humanTestError != '') { ?>
							<div class="full">
								<h5><?php echo $humanTestError;?></h5>
							</div>
						<?php } ?>

						<form name="contactForm" action="<?php the_permalink(); ?>" id="contact-form" method="post" class="contactform" >
															
							<input type="text" onfocus="if(this.value=='Name*')this.value='';" onblur="if(this.value=='')this.value='Name*';" name="contactName" id="contactName" value="Name*" class="input-textarea" />
														 
							<input type="text" onfocus="if(this.value=='Email*')this.value='';" onblur="if(this.value=='')this.value='Email*';" name="email" id="email" value="Email*" class="input-textarea" />

							<input type="text" onfocus="if(this.value=='Subject*')this.value='';" onblur="if(this.value=='')this.value='Subject*';" name="subject" id="subject" value="Subject*" class="input-textarea" />
														 
							<textarea name="comments" id="commentsText" cols="8" rows="5" ></textarea>
															
							<br />

							<p style="margin-top: 20px;"><?php _e("Human test. Please input the result of 5+3=?", "agrg"); ?></p>

							<input type="text" onfocus="if(this.value=='')this.value='';" onblur="if(this.value=='')this.value='';" name="humanTest" id="humanTest" value="" class="input-textarea" />

							<br />
															
							<br />
															
							<input style="margin-bottom: 0;" name="submitted" type="submit" value="Send Message" class="input-submit"/>	
														
						</form>

						<?php } ?>

					</div>

				</div>

	    		<div class="related-ads">

	    			<h2><?php _e( 'Related Ads', 'agrg' ); ?></h2>

	    			<div class="full">

	    				<?php  
							$orig_post = $post;  
							global $post;  
							$tags = wp_get_post_tags($post->ID);  
								      
							if ($tags) {  
								$tag_ids = array();  
								foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;  
								$args=array(  
								    'tag__in' => $tag_ids,  
								    'post__not_in' => array($post->ID),  
								    'posts_per_page'=>4, // Number of related posts to display.  
								    'ignore_sticky_posts'=>1  
								);  

								$current = -1;
								      
								$my_query = new wp_query( $args );  
								  
								while( $my_query->have_posts() ) { 

								    $my_query->the_post();  
								    global $postID;

								    $current++;

								?>  
		    						
		    						<div class="span2 <?php if($current%4 == 0) { echo 'first'; } ?>">
		    							
		    							<span class="field-content">

		    								<div class="ad-image-related">
		    										<a href="<?php the_permalink(); ?>">
		    											<?php 

		    										$thumb_id = get_post_thumbnail_id();
													$thumb_url = wp_get_attachment_image_src($thumb_id,'thumbnail-size', true);

													$params = array( 'width' => 440, 'height' => 290, 'crop' => true );
													echo "<img class='add-box-main-image' src='" . bfi_thumb( "$thumb_url[0]", $params ) . "'/>";

													?></a>
		    								</div>
		    									
		    								<div class="ad-description">
		    									<span class="title">
		    										<a href="<?php the_permalink(); ?>">
		    											<span class="title"><?php the_title(); ?></span>
		    										</a>
		    									</span>
		    									<span class="price"><?php $postID = get_the_ID(); echo get_post_meta($postID, 'post_price', true); ?></span>
		    								</div>
		    								
		    							</span> 

		    						</div>

		    			<?php 	}  
							}  
							$post = $orig_post;  
							wp_reset_query();  
						?>

	    			</div>

	    		</div>

	    		<div id="ad-comments">

	    			<?php comments_template( '' ); ?>  

	    		</div>

	    	</div>

	    	<div class="span3">

	    		<div class="cat-widget" style="margin-top: 10px;">

		    		<div class="cat-widget-title"><h4><?php _e( 'Most Popular', 'agrg' ); ?></h4></div>

		    		<div class="cat-widget-content">

		    			<ul> 

						  	<?php

								global $paged, $wp_query, $wp;

								$args = wp_parse_args($wp->matched_query);

								if ( !empty ( $args['paged'] ) && 0 == $paged ) {

									$wp_query->set('paged', $args['paged']);

									$paged = $args['paged'];

								}

								$categories = get_the_category();
								$cat_id = $categories[0]->cat_ID;


								$current = -1;
								$current2 = 0;


								$popularpost = new WP_Query( array( 'posts_per_page' => '5', 'cat' => $cat_id, 'posts_type' => 'post', 'paged' => $paged, 'meta_key' => 'wpb_post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC'  ) );										

								while ( $popularpost->have_posts() ) : $popularpost->the_post(); $current++; $current2++;

								?>

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

							<?php endwhile; ?>	       
						  									
						</ul>

		    		</div>

		    	</div>

		    	<?php 

					global $redux_demo; 

					$featured_ads_option = $redux_demo['featured-options-on'];

				?>

				<?php if($featured_ads_option == 1) { ?>

		    	<div class="cat-widget">

		    		<div class="cat-widget-title"><h4><?php _e( 'Featured Ads', 'agrg' ); ?></h4></div>

		    		<div class="cat-widget-content">

		    			<ul> 

						  	<?php

								global $paged, $wp_query, $wp;

								$args = wp_parse_args($wp->matched_query);

								if ( !empty ( $args['paged'] ) && 0 == $paged ) {

									$wp_query->set('paged', $args['paged']);

									$paged = $args['paged'];

								}

								$categories = get_the_category();
								$cat_id = $categories[0]->cat_ID;


								$current = -1;
								$featuredCurrent = 0;


								$popularpost = new WP_Query( array( 'cat' => $cat_id, 'posts_type' => 'post', 'paged' => $paged, 'order' => 'DESC'  ) );
										

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