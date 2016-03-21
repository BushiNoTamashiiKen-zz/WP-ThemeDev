<?php
//=== Some PHP form validation magic ===//
$postTitleError = '';

// This here checks that wp_nonce_field(); has done it's thing before submitting 
if( isset( $_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

	if( trim( $_POST['postTitle'] ) === '' ) {
		$postTitleError = 'Please enter a title.' ;
		$hasError = true;
	} else {
		$postTitle = trim($_POST['postTitle']);
}
//=== End ===//

//=== Actually submitting the post using Wordpress function wp_insert_post(); ===//
//=== Submit post information to Wordpress held in the array $post_Information ===//
$post_information = array(
	'post_title' => esc_attr(strip_tags($_POST['postTitle'])),
	'post_content' => esc_attr(strip_tags($_POST['postContent'])),
	'post_type' => 'post',
	'post_status' => 'pending'
);

// calling Wordpress method wp_insert_post() with our array as an argument;
wp_insert_post( $post_information );

// Make sure user doesn't submit more than once in a session by assigning wp_insert_post(); to $post_id
$post_id = wp_insert_post( $post_Information );

//If $post_id is returned once, redirect the user using Wordpress wp_redirect() and passing the destination to it;
if( $post_id ){
	wp_redirect( home_url() );
	exit;
}
?>

<?php
//=== My Front-end post Submission Form ===*/

//Put some function call like get_header(); here
get_header();
?>

<!-- Primary form begins here -->
<form action= '' id="PrimaryPostForm" method="POST">

	<!-- Create form fields -->
	<fieldset>
		<label for="postTitle"><?php _e('Post Title:', 'framework') ?></label>

		<input type="text" name="postTitle" id="postTitle" value="<?php if( isset($_POST['postTitle'] ) ) echo $_POST['postTitle']; ?>" class="required" />
	</fieldset>

	<!-- Output error message if necessary -->
	<?php if($postTitleError != '') { ?>
		<span class="error"><?php echo $postTitleError; ?></span>
		<div class="clearfix"></div>
	<?php } ?>
	<!-- End -->

	<fieldset>
		<label for="postContent"><?php _e('Post Content:', 'framework') ?></label>

		<textarea name="postContent" id="postContent" rows="8" cols="30" class="required"><?php if(isset($_POST['postContent'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['postContent']); } else { echo $_POST['postContent']; } } ?></textarea>
	</fieldset>

	<fieldset>
		<!-- Security check using Wordpress function wp_nonce_field(); to see if post info didn't come from somewhere else -->
		<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
		<input type="hidden" name="submitted" id="submitted" value="true" />

		<button type="submit"><?php _e('Add Post:', 'framework') ?></button>
	</fieldset>
	<!-- End -->

</form>
<!-- Primary form ends here -->





