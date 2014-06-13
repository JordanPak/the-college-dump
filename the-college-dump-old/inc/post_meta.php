<?php


	// Post price box
	add_action( 'add_meta_boxes', 'post_price' );
	function post_price() {
	    add_meta_box( 
	        'post_price',
	        __( 'Price', 'myplugin_textdomain' ),
	        'post_price_content',
	        'post',
	        'side',
	        'high'
	    );
	}

	function post_price_content( $post ) {

		$post_price = get_post_meta( $post->ID, 'post_price', true );

		echo '<label for="post_price"></label>';
		echo '<input type="text" id="post_price" name="post_price" placeholder="Enter price here" value="';
		echo $post_price; 
		echo '">';
		
	}

	add_action( 'save_post', 'post_price_save' );
	function post_price_save( $post_id ) {		

		global $post_price;

		if(isset($_POST["post_price"]))
		$post_price = $_POST['post_price'];
		update_post_meta( $post_id, 'post_price', $post_price );

	}

	// Post location box
	add_action( 'add_meta_boxes', 'post_location' );
	function post_location() {
	    add_meta_box( 
	        'post_location',
	        __( 'Location', 'myplugin_textdomain' ),
	        'post_location_content',
	        'post',
	        'side',
	        'high'
	    );
	}

	function post_location_content( $post ) {

		$post_location = get_post_meta( $post->ID, 'post_location', true );

		echo '<label for="post_location"></label>';
		echo '<input type="text" id="post_location" name="post_location" placeholder="Enter location here" value="';
		echo $post_location; 
		echo '">';
		
	}

	add_action( 'save_post', 'post_location_save' );
	function post_location_save( $post_id ) {		

		global $post_location;

		if(isset($_POST["post_location"]))
		$post_location = $_POST['post_location'];
		update_post_meta( $post_id, 'post_location', $post_location );

	}

	// Post latitude
	add_action( 'add_meta_boxes', 'post_latitude' );
	function post_latitude() {
	    add_meta_box( 
	        'post_latitude',
	        __( 'Latitude', 'myplugin_textdomain' ),
	        'post_latitude_content',
	        'post',
	        'side',
	        'high'
	    );
	}

	function post_latitude_content( $post ) {

		$post_latitude = get_post_meta( $post->ID, 'post_latitude', true );

		echo '<label for="post_latitude"></label>';
		echo '<input type="text" id="post_latitude" name="post_latitude" placeholder="Enter location here" value="';
		echo $post_latitude; 
		echo '">';
		
	}

	add_action( 'save_post', 'post_latitude_save' );
	function post_latitude_save( $post_id ) {		

		global $post_latitude;

		if(isset($_POST["post_latitude"]))
		$post_latitude = $_POST['post_latitude'];
		update_post_meta( $post_id, 'post_latitude', $post_latitude );

	}

	// Post longitude
	add_action( 'add_meta_boxes', 'post_longitude' );
	function post_longitude() {
	    add_meta_box( 
	        'post_longitude',
	        __( 'Longitude', 'myplugin_textdomain' ),
	        'post_longitude_content',
	        'post',
	        'side',
	        'high'
	    );
	}

	function post_longitude_content( $post ) {

		$post_longitude = get_post_meta( $post->ID, 'post_longitude', true );

		echo '<label for="post_longitude"></label>';
		echo '<input type="text" id="post_longitude" name="post_longitude" placeholder="Enter location here" value="';
		echo $post_longitude; 
		echo '">';
		
	}

	add_action( 'save_post', 'post_longitude_save' );
	function post_longitude_save( $post_id ) {		

		global $post_longitude;

		if(isset($_POST["post_longitude"]))
		$post_longitude = $_POST['post_longitude'];
		update_post_meta( $post_id, 'post_longitude', $post_longitude );

	}

?>
