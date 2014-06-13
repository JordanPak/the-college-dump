<?php
/**
 * Template Name: Contact
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage FlatAds
 * @since FlatAds 1.0
 */

global $redux_demo; 

$contact_email = $redux_demo['contact-email'];
$wpcrown_contact_email_error = $redux_demo['contact-email-error'];
$wpcrown_contact_name_error = $redux_demo['contact-name-error'];
$wpcrown_contact_message_error = $redux_demo['contact-message-error'];
$wpcrown_contact_thankyou = $redux_demo['contact-thankyou-message'];

$wpcrown_contact_latitude = $redux_demo['contact-latitude'];
$wpcrown_contact_longitude = $redux_demo['contact-longitude'];
$wpcrown_contact_zoomLevel = $redux_demo['contact-zoom'];


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
		} elseif(trim($_POST['contactName']) === '<?php _e( "Name*", "agrg" ); ?>') {
			$nameError = $wpcrown_contact_name_error;
			$hasError = true;
		}	else {
			$name = trim($_POST['contactName']);
		}

		//Check to make sure that the subject field is not empty
		if(trim($_POST['subject']) === '') {
			$subjectError = $wpcrown_contact_subject_error;
			$hasError = true;
		} elseif(trim($_POST['subject']) === '<?php _e( "Subject*", "agrg" ); ?>') {
			$subjectError = $wpcrown_contact_subject_error;
			$hasError = true;
		}	else {
			$subject = trim($_POST['subject']);
		}
		
		//Check to make sure sure that a valid email address is submitted
		if(trim($_POST['email']) === '')  {
			$emailError = $wpcrown_contact_email_error;
			$hasError = true;
		} else if (!preg_match("/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$/i", trim($_POST['email']))) {
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
			$humanTestError = "<?php _e( 'Not Human :(', 'agrg' ); ?>";
			$hasError = true;
		} else {

		}
			
		//If there is no error, send the email
		if(!isset($hasError)) {

			$emailTo = $contact_email;
			$subject = $subject;	
			$body = "<?php _e( 'Name', 'agrg' ); ?>: $name \n\nEmail: $email \n\nComments: $comments";
			$headers = '<?php _e( "From website", "agrg" ); ?> <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;
			
			wp_mail($emailTo, $subject, $body, $headers);

			$emailSent = true;

	}
}

get_header(); ?>

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
						"center":[<?php echo $wpcrown_contact_latitude; ?>,<?php echo $wpcrown_contact_longitude; ?>]
      					,"zoom": <?php echo $wpcrown_contact_zoomLevel; ?>
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
					latLng: [<?php echo $wpcrown_contact_latitude; ?>,<?php echo $wpcrown_contact_longitude; ?>]
				}
			});

			map = mapDiv.gmap3("get");

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

	</section>

	<section id="seacrh-result-title">

		 <div class="container">

        	<h2><?php the_title(); ?></h2>

        </div>

	</section>

    <section class="ads-main-page">

    	<div class="container">

	    	<div class="full" style="padding: 30px 0;">

				<div class="ad-detail-content">

	    			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							
					<?php the_content(); ?>
															
					<?php endwhile; endif; ?>


					<div class="contact_form">
								
						<?php if(isset($emailSent) && $emailSent == true) { ?>
													
							<h5><?php echo $wpcrown_contact_thankyou ?></h5></div>

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
												
							<div>
														
							<input type="text" onfocus="if(this.value=='<?php _e( "Name*", "agrg" ); ?>')this.value='';" onblur="if(this.value=='')this.value='<?php _e( "Name*", "agrg" ); ?>';" name="contactName" id="contactName" value="<?php _e( "Name*", "agrg" ); ?>" class="input-textarea" />
													 
							<input type="text" onfocus="if(this.value=='<?php _e( "Email*", "agrg" ); ?>')this.value='';" onblur="if(this.value=='')this.value='<?php _e( "Email*", "agrg" ); ?>';" name="email" id="email" value="<?php _e( "Email*", "agrg" ); ?>" class="input-textarea" />

							<input type="text" onfocus="if(this.value=='<?php _e( "Subject*", "agrg" ); ?>')this.value='';" onblur="if(this.value=='')this.value='<?php _e( "Subject*", "agrg" ); ?>';" name="subject" id="subject" value="<?php _e( "Subject*", "agrg" ); ?>" class="input-textarea" />
													 
							<textarea name="comments" id="commentsText" cols="8" rows="5" ></textarea>
														
							<br />

							<p style="margin-top: 20px;"><?php _e("Human test. Please input the result of 5+3=?", "agrg"); ?></p>

							<input type="text" onfocus="if(this.value=='')this.value='';" onblur="if(this.value=='')this.value='';" name="humanTest" id="humanTest" value="" class="input-textarea" />

							<br />
														
							<br />
														
							<input style="margin-bottom: 0;" name="submitted" type="submit" value="<?php _e( 'Send Message', 'agrg' ); ?>" class="input-submit"/>			
								
							</div>
													
						</form>
							
					</div>

					<?php } ?>

	    		</div>

	    		<div id="ad-comments">

	    			<?php comments_template( '' ); ?>  

	    		</div>

	    	</div>

	    </div>

    </section>

<?php get_footer(); ?>