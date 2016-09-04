<?php
/**
 * Plugin Name: Browny Point Shopper 1.0
 * Description: A plugin that allocates points based on the number of posts approved and published by admin. The user can then use these points in a store to purchase items.
 * @param publish_post hook
 * Version: 1.0
 * Author: Thabo Mbuyisa
 * Last modified: 20-07-16
 * License: GPL2+
 */

// Allocates points to users based on the number of posts submitted 
function allocate_browny_points($ID, $post) {

	$author = $post->post_author; // Post author ID

	$browny_points = get_user_meta($author, 'browny_points', true); // Get current points (if there are any)
	$premium_message = get_user_meta($author, 'super_level', true);

	if($browny_points == ''){ // Check if there're no points in the DB 

		$browny_points = 0; // Initialise points
		delete_user_meta($author, 'browny_points', $browny_points); // Clean up
		add_user_meta($author, 'browny_points', $browny_points); // If none add some 

	}else {
		
		$browny_points += 500; // Increment the points by 500 each time
		update_user_meta($author, 'browny_points', $browny_points);

		if($browny_points == 3000){

			if($premium_message == ''){
				delete_user_meta($author, 'super_level', $premium_message);
				add_user_meta($author, 'super_level', $premium_message);
			}

			$premium_message = 'SuperUser unlocked!'; // Set points status after surpassing (n) points
			update_user_meta($author, 'super_level', $premium_message);

			// Setup mailer variables for user
			$name = get_the_author_meta( 'display_name', $author );
		    $email = get_the_author_meta( 'user_email', $author );
		    $title = $post->post_title;
		    $permalink = get_permalink( $ID );
		    $edit = get_edit_post_link( $ID, '' );
		    $to[] = sprintf( '%s <%s>', $name, $email );
		    $subject = sprintf( 'SuperUser level unlocked for: %s', $name );
		    $message = sprintf ('Congratulations, %s! You have reached “%s” points!' . "\n\n", $name, $browny_points );
		    $message .= sprintf( 'with this post: %s', $permalink );
		    $headers[] = '';

		    /*$multiple_recipients = array(
    			'recipient1@example.com',
    			'recipient2@foo.example.com'
			);*/

    		wp_mail( $to, $subject, $message, $headers ); // Send mail to user
		}
	}
}

add_action('publish_post', 'allocate_browny_points', 10, 2);


// Handles the user_meta and post_meta transaction and acts as an online purchase process
function browny_points_transaction() {

	$checkout = home_url('/checkout/');
	$cancel_checkout = home_url('/browny-shop/');

	$current_user = wp_get_current_user(); // Get the current user
	$current_user_id = get_current_user_id(); // Get the current logged in user's ID to pass to the WP_User instance
	$current_user_role = wp_get_current_user()->roles; // Get current user role to pass as a string

	$browny_points = get_user_meta($current_user_id, 'browny_points', true); // Get current points (if there are any)
		
	$item_id = get_the_ID(); // Retrieve current post id
	$item_title = get_the_title($item_id); // Retrieve the current post title
	$item_permalink = get_permalink($item_id); // Retrieve the current post permalink
	$item_price = get_post_meta($item_id, 'store_item_price', true); // Retrieve current post price meta

	// Set up variables
	$admin_email = get_option( 'admin_email' );
	$to = $current_user->user_email;
	$subject = 'Item Purchase';
	$message = "The user : " . $current_user->display_name . " has purchased " . $item_title . " using Browny Points \n";
	$message .= sprintf( 'Item link : %s', $item_permalink );

	if(isset($_POST['checkout_submitted'])){

		if($browny_points >= $item_price ){

			$residual = $browny_points - $item_price;
			update_user_meta($current_user_id, 'browny_points', $residual);
		}else{

			echo '<p>Sorry your points balance is' . ' ' . $browny_points .'</p>';
			echo '<p>Please recharge your points</p>';
		}
	
		// Set the mailer variable
		//$sent = wp_mail($to, $subject, $message);

		//if($sent){

			// redirect after headers have already been sent
			?>
  			<script type="text/javascript">
				window.location= <?php echo "'" . $checkout . "'"; ?>;
   			</script>
			<?php
		//}
	}elseif(isset($_POST['checkout_cancelled'])) {

		// redirect after header definitions
		?>
  		<script type="text/javascript">
			window.location= <?php echo "'" . $cancel_checkout . "'"; ?>;
   		</script>
		<?php
	}
}


// Fetches Checkout Page template from plugin template parts
function browny_store_checkout() {

	require_once('template-parts/checkout-page.php'); 
}


// Creates shortcode that connects the transaction function to the wp post in order to capture the post_id
function browny_store_shortcode() {

	ob_start();
	browny_points_transaction();

	return ob_get_clean();
}
add_shortcode( 'store_transact', 'browny_store_shortcode' );


// Creates shortcode to call checkout page in wp
function browny_checkout_shortcode() {

	ob_start();
	browny_store_checkout();

	return ob_get_clean();
}
add_shortcode( 'checkout_page', 'browny_checkout_shortcode' );


// Adds custom post type to wp
function browny_store_init() {
    $args = array(
      	'label' 			 => 'Browny Shop',
        'public' 			 => true,
        'show_ui' 			 => true,
        'capability_type' 	 => 'post',
        'hierarchical' 		 => false,
        'rewrite' 			 => array(
        	'slug' => 'browny-shop'
        	),
        'query_var' 		 => true,
        'menu_icon' 		 => 'dashicons-products',
        'publicly_queryable' => true,
        'capability_type'    => 'post',
        'supports' 			 => array(
            'title',
            'editor',
            'excerpt',
            'trackbacks',
            'custom-fields',
            'comments',
            'revisions',
            'thumbnail',
            'author',
            'page-attributes',)
        );
    register_post_type( 'mofumofu-store', $args ); // (Post type name and display template file name should be the same)
}
add_action( 'init', 'browny_store_init' );


// Adds custom meta box for item pricing in post edit screen
function prices_metaboxes_init() {

	// Set up meta box visual
	function prices_add_meta_boxes() {

		add_meta_box(

			'item-prices-metabox', // Unique ID
			esc_html__( 'Item Price', 'Price' ), // Metabox title
			'add_item_prices_meta_box', // Callback function
			'mofumofu-store', // Post type
			'side', // Context
			'default' // Priority
		);
	}

	// Output MetaBox html
	function add_item_prices_meta_box( $post ) { ?>

		<?php wp_nonce_field( basename(__FILE__), 'add_item_prices_nonce' ); // Input validation with wp_nonce ?>

		<p>
			<label for="item_price_field"><?php _e( "Add Item Price", 'price' ); ?></label>
			<br />
			<input class="widefat" type="text" name="item_price_field" id="item_price_field" value="" size="30" />
		</p>

	<?php }

	// Capture user data 
	function save_item_price_data( $post_id, $post ) {

		// Verify the nonce before proceeding
		if( !isset( $_POST['add_item_prices_nonce'] ) || !wp_verify_nonce( $_POST['add_item_prices_nonce'], basename(__FILE__) ) )
		return $post_id;

		// Get the post type object
		$post_type = get_post_type_object( $post->post_type );

		// Check if the current user has permission to edit the post
		if( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

		$new_price_value = $_POST['item_price_field'];
		$current_price_value = get_post_meta($post_id, 'store_item_price', true);

		// Capture user input
		if( array_key_exists('item_price_field', $_POST ) ) {

			if($new_price_value && '' == $current_price_value ) { // If the meta value didn't exist before add it

				add_post_meta($post_id, 'store_item_price', $new_price_value, true);

			}elseif($new_price_value && $new_price_value != $current_price_value) { // If input meta value does not match old one

				update_post_meta( $post_id, 'store_item_price', $new_price_value );

			}elseif('' == $new_price_value && $current_price_value) { // If there is no new meta value but an old one exists delete it

				delete_post_meta($post_id, 'store_item_price', $current_price_value);
			}	
		}
	}

	add_action('add_meta_boxes', 'prices_add_meta_boxes');
	add_action( 'save_post', 'save_item_price_data', 10, 2 ); // Save Post Meta on 'save_post' hook 

}
add_action('load-post.php', 'prices_metaboxes_init');
add_action('load-post-new.php', 'prices_metaboxes_init');