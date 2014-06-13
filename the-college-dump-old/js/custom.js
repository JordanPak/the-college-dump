/*-----------------------------------------------------------------------------------*/
/*	Custom Script
/*-----------------------------------------------------------------------------------*/

jQuery.noConflict();
jQuery(document).ready(function(){

	jQuery(window).load(function(){

		jQuery('.page-container .page-content').fadeTo( 300 , 1);

		jQuery('#featured-ads').fadeTo( 300 , 1);

		jQuery('#big-map').fadeTo( 300 , 1);

		jQuery('#featured-ads-author').fadeTo( 300 , 1);

		jQuery('#featured-ads-category').fadeTo( 300 , 1);

	});

	jQuery("#projects-carousel .span3").each(function() {
		
		var $thisItem = jQuery(this);
		var $thisWidth = $thisItem.parents().parents().width();

		if($thisWidth <= 714) {
				
			$thisItem.css("width", $thisWidth);

		} else {

			$thisItem.css("width", "");

		}
			
	});

	jQuery(".form-select").chosen();

	jQuery(window).bind('resize', function () {

		jQuery("#projects-carousel .span3").each(function() {
		
			var $thisItem = jQuery(this);
			var $thisWidth = $thisItem.parents().parents().width();

			if($thisWidth <= 714) {
					
				$thisItem.css("width", $thisWidth);

			} else {

				$thisItem.css("width", "");

			}
				
		});

	});

	jQuery('.remImage').live('click', function() {

		jQuery(this).parent().parent().fadeOut();
		jQuery(this).parent().find('input').attr('name', 'att_remove[]' );

    });

    jQuery(document).ready(function() {
	    jQuery(".target-blank").attr({"target" : "_blank"})
	});

	jQuery(window).scroll(function() {
		if (jQuery(this).scrollTop() > 200) {
			jQuery('.backtop').fadeIn(200);
		} else {
			jQuery('.backtop').fadeOut(200);
		}
	});

	// scroll body to 0px on click
	jQuery(".backtop a").click(function () {
		jQuery("body,html").animate({
			scrollTop: 0
		}, 800);
		return false;
	});

	jQuery('#tag-index-page').isotope({
		itemSelector: '.tag-group',
		layoutMode: 'masonry'
	});

	//Toggle
	jQuery(".togglebox").hide();
	//slide up and down when click over heading 2
	
	jQuery("h4").click(function(){
		
		// slide toggle effect set to slow you can set it to fast too.
		jQuery(this).toggleClass("active").next(".togglebox").slideToggle("slow");
	
		return true;
	
	});

	jQuery(function() {
	    // setup ul.tabs to work as tabs for each div directly under div.panes
      	jQuery("ul.custom-tabs").tabs("div.custom-panes > div");
	});

	if( jQuery('#galleria').length ) {
	    // Initialize Galleria
		Galleria.run('#galleria');
	}


	jQuery('.show-long-number-button').click(function(){
    	
    	jQuery(".show-long-number-button").css("display", "none");
        jQuery(".short-phone-number").css("display", "none");
        jQuery(".long-phone-number").css("display", "block");
    	
	});

});