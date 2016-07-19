<?php
/**
 * Plugin Name: Browny Point Shopper 1.0
 * Description: A plugin that allocates points based on the number of posts approved and published by admin
 *              The user can then use these points in a store to purchase items.
 * @param publish_post hook
 * Version: 1.0
 * Author: Thabo Mbuyisa
 * Last modified: 15-07-16
 * License: GPL2+
 */

// Allocates points to users based on the number of posts submitted 
function allocate_browny_points($ID, $post){

	$author = $post->post_author; // Post author ID

	$browny_points = get_user_meta($author, 'browny_points', true); // Get current points (if there are any)
	$premium_message = get_user_meta($author, 'super_level', true);

	if($browny_points == ''){ // Check if there're no points in the DB 

		$browny_points = 0; // Initialise points
		delete_user_meta($author, 'browny_points', $browny_points); // Clean up
		add_user_meta($author, 'browny_points', $browny_points); // If none add some 

	}else{
		
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
		    $subject = sprintf( 'SuperUser unlocked for: %s', $name );
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
function browny_points_transaction(){

	$checkout = home_url('/checkout/');
	$cancel_checkout = home_url();

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

		if($item_price == ''){

			$item_price = 0; // Set the item pricing
			add_post_meta($item_id, 'store_item_price', $item_price);

		}elseif($browny_points >= $item_price ){

			$residual = $browny_points - $item_price;
			update_user_meta($current_user_id, 'browny_points', $residual);
		}else{

			echo 'Sorry your points balance is' . $browny_points;
			echo 'Please recharge your mofumofu points';
		}

		/*$item_price += 500; // Increment the price by 500 each time
		update_post_meta($item_id, 'store_item_price', $item_price);*/
	
		// Set the mailer variable
		//$sent = wp_mail($to, $subject, $message);

		//if($sent){

			// redirect after header definitions
			?>
  			<script type="text/javascript">
				window.location= <?php echo "'" . $checkout . "'"; ?>;
   			</script>
			<?php
		//}
	}elseif(isset($_POST['checkout_cancelled'])){

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
function browny_store_shortcode(){
	ob_start();
	browny_points_transaction();

	return ob_get_clean();
}
add_shortcode( 'store_transact', 'browny_store_shortcode' );

// Creates shortcode to call checkout page in wp
function browny_checkout_shortcode(){
	ob_start();
	browny_store_checkout();

	return ob_get_clean();
}
add_shortcode( 'mofu_checkout_page', 'browny_checkout_shortcode' );

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
    register_post_type( 'mofumofu-store', $args );
}
add_action( 'init', 'browny_store_init' );