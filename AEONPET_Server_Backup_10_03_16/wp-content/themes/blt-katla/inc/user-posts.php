<?php




if( isset($_POST['blt_post_upload_form_submitted'] ) and wp_verify_nonce($_POST['blt_post_upload_form_submitted'], 'blt_post_upload_form') ){

	$current_user = wp_get_current_user();

	// Get clean input variables
	$title 			= sanitize_text_field( $_POST['blt_post_title'] );
	$tags 			= sanitize_text_field( $_POST['blt_post_tags'] );
	$content 		= '';
	$content 		= $_POST['blt_post_content'];
	// $post_format 	= $_POST['blt_post_format'];
	$video_url 		= esc_url( $_POST['video_url'] );
	$info_url 		= esc_url( $_POST['blt_post_info_url'] );
	$info_name 		= esc_html( $_POST['blt_post_info_name'] );
	$category 		= intval( $_POST['blt_post_category'] );
	$blt_make_featured_image 	= empty($_POST['blt_make_featured_image']) ? 0 : $_POST['blt_make_featured_image'];


	# 
	# Validate
	# 
	
		// Make sure the user is logged in
		if(!is_user_logged_in()){
			blt_errors()->add('not_logged_in', __('You need to log in to add a post', 'bluthemes'));
		}

		// Title must be set
		if(empty($title)){
			blt_errors()->add('title_empty', __('Title can not be empty', 'bluthemes'));
		}

		// Content must be set
		if(empty($content)){
			blt_errors()->add('content_empty', __('Please insert some text content with your post', 'bluthemes'));
		}

		// Category must be selected
		if(empty($category)){
			blt_errors()->add('category_empty', __('Please select a category', 'bluthemes'));
		}

		// Make sure the video URL is valid
		if(!empty($video_url)){
			if(in_array(parse_url($video_url, PHP_URL_SCHEME),array('http','https'))){
			    if(filter_var($video_url, FILTER_VALIDATE_URL) === false){
					blt_errors()->add('url_invalid', __('The URL you provided for the video is not a valid URL', 'bluthemes'));
			    }
			}
		}

		// Make user has sufficient rights to post
		if(!blt_has_access( blt_get_option('user_posts_access', 'subscriber') )){
			blt_errors()->add('access_denied', __('You don\'t have sufficent access rights to submit a post', 'bluthemes'));
		}

		// Make sure the extra info URL is valid	
		if(!empty($info_url)){
			if(in_array(parse_url($info_url, PHP_URL_SCHEME),array('http','https'))){
			    if(filter_var($info_url, FILTER_VALIDATE_URL) === false){
					blt_errors()->add('url_invalid', __('The URL you provided is not a valid URL', 'bluthemes'));
			    }
			}else{
				blt_errors()->add('url_invalid', __('The URL you provided is not a valid URL', 'bluthemes'));
			}
		}

		// Tags must be set, you comment this out if you want that requirement
		// if(empty($tags)){
		// 	blt_errors()->add('tags_empty', __('Please insert tags', 'bluthemes'));
		// }





	# 
	# Image Uploads
	# 
	$images_required = blt_get_option('user_posts_images_required', false);


		$no_images = true;
		foreach($_FILES['blt_post_files']['name'] as $key => $value){
			if(!empty($value)){
				$no_images = false;
			}
		}




	// If there are errors already found, skip this part
	if(empty($error_codes)){
		

		if($no_images){
			
			// If images are required throw an error
			if($images_required){
				blt_errors()->add('no_images', __('Please upload images with your post', 'bluthemes'));
			}
		
		}else{
			

			$files = reArrayFiles( $_FILES['blt_post_files'] );

			$attachment_ids = array();

			$i = 0;
			foreach($files as $file){
				
				if(is_array($file)){

					$attachment_id = upload_user_file( $file, isset($_POST['blt_post_files_title'][$i]) ? sanitize_text_field($_POST['blt_post_files_title'][$i]) : false );

					if($attachment_id){

						// add post meta
						add_post_meta($attachment_id, 'blt_post_files_source_name', sanitize_text_field($_POST['blt_post_files_source_name'][$i]), false);
						add_post_meta($attachment_id, 'blt_post_files_source_url', sanitize_text_field($_POST['blt_post_files_source_url'][$i]), false);
						$attachment_ids[] = $attachment_id;
					}

					if( (int) $blt_make_featured_image === $i){
						$featured_image_id = $attachment_id;
					}

				}
				$i++;
			}
		}
	}

	$errors = blt_errors()->get_error_codes();

	// If there are no errors, continue and create the post
	if( empty($errors) ){

		// Insert post
		$post_id = wp_insert_post(array(
			'post_title'	=> wp_strip_all_tags($title),
			'post_content'	=> wp_filter_post_kses($content),
			'tags_input'	=> $tags,
			'post_category'	=> array($category),
			'post_status'	=> blt_get_option('user_posts_default_post_status', 'pending')
		));

		if(!empty($video_url)){
			set_post_format($post_id, 'video');
		}

		// Add Video URL to post meta
		update_field('video_url', $video_url, $post_id);

		// Add Info URL to post meta
		update_field('blt_info_url', $info_url, $post_id);
		update_field('blt_info_name', $info_name, $post_id);

		// Add post format if needed
		// if($post_format == 'open_list'){
		// 	update_field('blt_post_format', 'open_list', $post_id);
		// }

		if(!empty($attachment_ids)){
			
			// Create the ACF gallery with all the images
			update_post_meta( $post_id, 'blt_gallery', $attachment_ids );

			// Set selected image as the featured image
			set_post_thumbnail( $post_id, $featured_image_id );

		}

		$redirect_url = blt_get_option('user_posts_redirect_page');

			if(empty($redirect_url)){
				$redirect_url = blt_get_option('user_posts_page_id');
			}

			$redirect_url = add_query_arg(array('complete' => '1'), $redirect_url);

		// send an email to all admins that a new post has been created
		// get all admins
		if(blt_get_option('send_new_post_email', true)){
			
			$admins = get_users( array(
			 	'role' => 'administrator'
			));

			// loop through admins and send them emails
			foreach($admins as $admin){

				$message = __('New Post Submitted & Pending Review: ', 'bluthemes') . $title;
				wp_mail($admin->user_email, __('New Post by User'), $message);
				// wp_mail($admin->user_email, __('New Post by User'), sprintf(__('%s has posted a new article titled %s. You can view it %s here %s', 'bluthemes'), $current_user->user_login, wp_strip_all_tags($title), '<a href="'.get_permalink($post_id).'">', '</a>'));

			}
		}
		
		wp_redirect( $redirect_url );
		exit;


	}

}


function upload_user_file( $file = array(), $title = false ) {

	blt_add_thumbnail_sizes();

	require_once ABSPATH.'wp-admin/includes/admin.php';

	$file_return = wp_handle_upload($file, array('test_form' => false));

	if(isset($file_return['error']) || isset($file_return['upload_error_handler'])){

		blt_errors()->add('image_invalid', $file_return['error']);
		return false;

	}else{

		$filename = $file_return['file'];

		$attachment = array(
			'post_mime_type' => $file_return['type'],
			'post_content' => '',
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			'guid' => $file_return['url']
		);

		if($title){
			$attachment['post_title'] = $title;
		}

		$attachment_id = wp_insert_attachment( $attachment, $filename );
		// $attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );

		require_once(ABSPATH . 'wp-admin/includes/image.php');
		
		$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );

		wp_update_attachment_metadata( $attachment_id, $attachment_data );

		if( 0 < intval( $attachment_id ) ) {
			return $attachment_id;
		}
	}

	blt_errors()->add('image_invalid', $file__('Please select a category', 'bluthemes'));

	return false;
}


function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}



function blt_vote_post(){

	if(!wp_verify_nonce($_POST['blt_nonce'], 'blt_nonce')){
        die('Ah ah ah, you didn\'t say the magic word');
	}


	$post_id 	= $_POST['post_id'];
	$type 		= $_POST['type'];


	// Validate input
	if(!is_numeric($post_id)){
		echo 'Post id not numeric';
		die();
	}
	if(!in_array($type, array('upvote', 'downvote'), true)){
		echo 'Vote type invalid';
		die();
	}

	// Check if the user needs to be logged in to vote
	$needs_login = blt_get_option('post_voting_logged_in', true);
	$is_logged_in = is_user_logged_in();

		if(!$is_logged_in and $needs_login){
			echo 'You need to be logged in to do that';
			die();		
		}


	$user_votes = blt_get_user_votes($is_logged_in);
	$prev_vote = blt_has_user_voted_post( $user_votes, $post_id );


	$upvotes 	= get_post_meta( $post_id, 'blt_upvotes', true );
	$downvotes 	= get_post_meta( $post_id, 'blt_downvotes', true );


	// User has not voted on this post
	if($prev_vote === false){


		# 	
		# 	ADD VOTE TO POST
		# 	========================================================================================
		#   Add an up or down vote to a post via post meta
		# 	========================================================================================
		#
		switch($type){
 			case 'upvote':
				if($upvotes == ''){

					$upvotes = 1;

					add_post_meta( $post_id, 'blt_upvotes', $upvotes, true );
					
				}else{

					$upvotes++;

					update_post_meta( $post_id, 'blt_upvotes', $upvotes);
					
				}
				break;

 			case 'downvote':
				if($downvotes == ''){

					$downvotes = 1;

					add_post_meta( $post_id, 'blt_downvotes', $downvotes, true );

				}else{

					$downvotes++;

					update_post_meta( $post_id, 'blt_downvotes', $downvotes);

					}				
 			break;
		}


		blt_update_score($post_id, $upvotes, $downvotes);


		# 	
		# 	ADD VOTE TO USER
		# 	========================================================================================
		#   Add this vote to the user so we know what he has voted on
		# 	========================================================================================
		#		
		switch($type){
 			case 'upvote':

 				$user_votes['upvotes'][$post_id] = true;

				blt_set_user_votes($is_logged_in, $user_votes);

				echo 'ok';

				break;
 			case 'downvote':

 				$user_votes['downvotes'][$post_id] = true;

				blt_set_user_votes($is_logged_in, $user_votes);

				echo 'ok';

 			break;
		}



	// User has voted on this before
	}else{

		// If the user is changing his vote
		if($type != $prev_vote){


			# 	
			# 	ADD VOTE TO POST
			# 	========================================================================================
			#   Add an up or down vote to a post via post meta
			# 	========================================================================================
			#
			switch($type){

	 			case 'upvote':

					if($upvotes == ''){

						$upvotes = 1;

						add_post_meta( $post_id, 'blt_upvotes', $upvotes, true );
					}else{

						$upvotes++;
						
						update_post_meta( $post_id, 'blt_upvotes', $upvotes);
					}
					
					$downvotes = max($downvotes - 1, 0);

					update_post_meta( $post_id, 'blt_downvotes', $downvotes);

 				break;

	 			case 'downvote':

					if($downvotes == ''){

						$downvotes = 1;

						add_post_meta( $post_id, 'blt_downvotes', 1, true );
					}else{

						$downvotes++;
						
						update_post_meta( $post_id, 'blt_downvotes', $downvotes);
					}				
					
					$upvotes = max($upvotes - 1, 0);

					update_post_meta( $post_id, 'blt_upvotes', $upvotes);

	 			break;
			}


			blt_update_score($post_id, $upvotes, $downvotes);


			switch($type){
	 			case 'upvote':

	 				// Remove the downvote from the votes array
	 				unset($user_votes['downvotes'][$post_id]);

	 				// Add the upvote
	 				$user_votes['upvotes'][$post_id] = true;

					blt_set_user_votes($is_logged_in, $user_votes);

					echo 'ok';

 				break;
	 			case 'downvote':

	 				// Remove the upvote from the votes array
	 				unset($user_votes['upvotes'][$post_id]);

	 				// Add the downvote
	 				$user_votes['downvotes'][$post_id] = true;

					blt_set_user_votes($is_logged_in, $user_votes);

					echo 'ok';

	 			break;
			}


		}else{
			echo 'Already voted that.';
		}
	}



	wp_die();
}
add_action( 'wp_ajax_blt_vote_post', 'blt_vote_post' );
add_action( 'wp_ajax_nopriv_blt_vote_post', 'blt_vote_post' );



function blt_update_score($post_id, $upvotes = 0, $downvotes = 0){

	if(empty($upvotes)){
		$upvotes = 0;
	}

	if(empty($downvotes)){
		$downvotes = 0;
	}

	$score = get_post_meta( $post_id, 'blt_score', true );

	$total_score = $upvotes - $downvotes;

		if($total_score < 0){
			$total_score = 0;
		}


	if($score == ''){
		add_post_meta( $post_id, 'blt_score', $total_score, true );
	}else{
		update_post_meta( $post_id, 'blt_score', $total_score);		
	}

}



function blt_get_user_votes($is_logged_in){


	if($is_logged_in){

		$votes = get_user_meta( get_current_user_id(), 'blt_user_votes', true );
	}else{

		$votes = get_option( md5('blt_user_votes_' . getRemoteIPAddress() ) );
	}


	if(empty($votes)){
		return array(
			'upvotes' => array(),
			'downvotes' => array(),
		);
	}


	return $votes;
}

function blt_set_user_votes($is_logged_in, $user_votes){

	if($is_logged_in){

		update_user_meta( get_current_user_id(), 'blt_user_votes', $user_votes );

	}else{

		update_option( md5('blt_user_votes_' . getRemoteIPAddress()), $user_votes  );
	}


}

function blt_has_user_voted_post( $votes_array, $post_id ){

	if(!$votes_array){
		return false;
	}


	if(isset($votes_array['upvotes'][$post_id])){
		return 'upvote';
	}

	elseif(isset($votes_array['downvotes'][$post_id])){	
		return 'downvote';
	}

	return false;
}