<?php 

function wpcrown_wpcss_loaded() {

	// Return the lowest priority number from all the functions that hook into wp_head
	global $wp_filter;
	$lowest_priority = max(array_keys($wp_filter['wp_head']));
 
	add_action('wp_head', 'wpcrown_wpcss_head', $lowest_priority + 1);
 
	$arr = $wp_filter['wp_head'];

}
add_action('wp_head', "wpcrown_wpcss_loaded");
 
// wp_head callback functions
function wpcrown_wpcss_head() {

	global $redux_demo; 
	$wpcrown_main_color = $redux_demo['color-main'];
	$wpcrown_main_color_hover = $redux_demo['color-main-hover'];
	$wpcrown_button_color_main = $redux_demo['button-color-main'];
	$wpcrown_button_color_main_hover = $redux_demo['button-color-main-hover'];
	$measure_system = $redux_demo['measure-system'];

	echo "<style type=\"text/css\">";

	// Main Color
	if(!empty($measure_system)) {

		if($measure_system == "1") {
			
			echo ".range-pin { background: transparent url(";
			echo get_template_directory_uri();
			echo "/images/range-mi.png) no-repeat top left; } ";

		}

	}

	// Main Color
	if(!empty($wpcrown_main_color)) {
			
		echo "a, #navbar .main_menu .menu li.current_page_item .sub-menu a:hover, #navbar .main_menu .menu li.current_page_item .children a:hover, #navbar .main_menu .menu li.current-menu-item  .children a:hover, .main_menu ul li ul.children li a:hover, .main_menu ul li ul.sub-menu li a:hover, .main_menu ul li ul.children li.current_page_item a, .main_menu ul li ul.children li.current-menu-item a, .main_menu .menu li.current_page_item .sub-menu a:hover, .main_menu .menu li.current-menu-item  .sub-menu a:hover, .main_menu .menu li.current_page_item .children a:hover, .main_menu .menu li.current-menu-item  .children a:hover, .geo-location-button .on .fa, .geo-location-button .fa:hover, ul.custom-tabs li a.current, h4.trigger:hover, h4.trigger.active:hover, h4.trigger.active,#navbar .main_menu .menu li .sub-menu li.current_page_item a, #navbar .main_menu .menu li .children li.current_page_item a, #navbar .main_menu .menu li .children li.current_page_item a:hover, #navbar .main_menu .menu li .children li .current-menu-item a:hover { color: ";
		echo $wpcrown_main_color;
		echo "; } ";

		echo ".ui-slider-horizontal .ui-slider-range-min, #advanced-search-widget-version2 .views-exposed-widget .btn-primary, .quicktabs-tabs .grid-feat-ad-style a:hover, .quicktabs-tabs .list-feat-ad-style a:hover, .quicktabs-tabs .grid-feat-ad-style a.current, .quicktabs-tabs .list-feat-ad-style a.current { background-color: ";
		echo $wpcrown_main_color;
		echo "; } ";

		echo ".woocommerce ul.products li.product a:hover img, .woocommerce-page ul.products li.product a:hover img, .woocommerce #content div.product div.images a:hover img, .woocommerce div.product div.images a:hover img, .woocommerce-page #content div.product div.images a:hover img, .woocommerce-page div.product div.images a:hover img { border: 5px solid ";
		echo $wpcrown_main_color;
		echo "; } ";

		echo "#contact-form #contactName:focus, #contact-form #author:focus, #contact-form #email:focus, #contact-form #url:focus, #contact-form #subject:focus, #contact-form #commentsText:focus, #contact-form #humanTest:focus { border: 1px solid ";
		echo $wpcrown_main_color;
		echo "; } ";

	}

	// Main Color Hover
	if(!empty($wpcrown_main_color_hover)) {
			
		echo "a:hover, a:active, .main_menu ul li:hover > a, .main_menu .menu li.current_page_item a, .main_menu .menu li.current-menu-item a { color: ";
		echo $wpcrown_main_color_hover;
		echo "; } ";

		echo "#advanced-search-widget-version2 .views-exposed-widget .btn-primary:hover { background-color: ";
		echo $wpcrown_main_color_hover;
		echo "; } ";

	}

	// Button Color
	if(!empty($wpcrown_button_color_main)) {
			
		echo "#new-post a.btn, input[type='submit'], .woocommerce span.onsale, .woocommerce-page span.onsale, .products li a.button, .woocommerce div.product form.cart .button, .woocommerce-page div.product form.cart .button, .woocommerce #content div.product form.cart .button, .woocommerce-page #content div.product form.cart .button, .woocommerce button.button, .woocommerce-page button.button, .woocommerce input.button, .woocommerce-page input.button, .woocommerce #respond input#submit, .woocommerce-page #respond input#submit, .woocommerce #content input.button, .woocommerce-page #content input.button, #top-cart .button, form.cart .button-alt, .woocommerce #content input.button, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce-page #content input.button, .woocommerce-page #respond input#submit, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .bbp-submit-wrapper button.button, .woocommerce .quantity .minus, .woocommerce-page .quantity .minus, .woocommerce #content .quantity .minus, .woocommerce-page #content .quantity .minus, .woocommerce .quantity .plus, .woocommerce-page .quantity .plus, .woocommerce #content .quantity .plus, .woocommerce-page #content .quantity .plus, form.cart .plus, form.cart .minus, .product-quantity .plus, .product-quantity .minus, .woocommerce .quantity input.qty, .woocommerce-page .quantity input.qty, .woocommerce #content .quantity input.qty, .woocommerce-page #content .quantity input.qty, form.cart input.qty, form.cart input.qty, .product-quantity input.qty, .pricing-plans a.btn, #edit-submit, .ads-tags a, #navbar .btn-navbar { background: ";
		echo $wpcrown_button_color_main;
		echo "!important; } ";

		echo ".woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price { color: ";
		echo $wpcrown_button_color_main;
		echo "; } ";

		echo ".ads-tags a:hover { background: #ecf0f1!important; color: ";
		echo $wpcrown_button_color_main;
		echo "!important; } ";

	}

	// Button Color Hover
	if(!empty($wpcrown_button_color_main_hover)) {
			
		echo "#new-post a.btn:hover, input[type='submit']:hover, .products li a.button:hover, .woocommerce div.product form.cart .button:hover, .woocommerce-page div.product form.cart .button:hover, .woocommerce #content div.product form.cart .button:hover, .woocommerce-page #content div.product form.cart .button:hover, .woocommerce button.button:hover, .woocommerce-page button.button:hover, .woocommerce input.button:hover, .woocommerce-page input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce-page #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page #content input.button:hover, #top-cart .button:hover, form.cart .button-alt:hover, .woocommerce #content input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce-page #content input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover, .bbp-submit-wrapper button.button:hover, .woocommerce .quantity .minus:hover, .woocommerce-page .quantity .minus:hover, .woocommerce #content .quantity .minus:hover, .woocommerce-page #content .quantity .minus:hover,.woocommerce .quantity .plus:hover, .woocommerce-page .quantity .plus:hover, .woocommerce #content .quantity .plus:hover, .woocommerce-page #content .quantity .plus:hover, form.cart .plus:hover, form.cart .minus:hover, .product-quantity .plus:hover, .product-quantity .minus:hover, .pricing-plans a.btn:hover, #edit-submit:hover, #navbar .btn-navbar:hover { background: ";
		echo $wpcrown_button_color_main_hover;
		echo "!important; } ";

	}

	echo "</style>";

}