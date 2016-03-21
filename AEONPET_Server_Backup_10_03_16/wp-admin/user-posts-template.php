<?php
#   
#   Template Name: Bluthemes - User Submitted Content
#   ========================================================================================
#   This page template loads the form which users can use to post content to your site
#   ========================================================================================
#  

get_header(); 
?>
<div id="site-content" class="clearfix">

	<div id="site-content-column" class="users-posts-section"><?php

		if(isset($_GET['complete'])){ ?>

			<div class="page-body">
				<h1 class="page-title"><i class="fa fa-check" style="color:#A5D535"></i> <?php _e('Post has been Submitted!', 'bluthemes'); ?></h1>
				<div class="page-text">
					<hr>
					<p class="lead"><?php _e('Thank you for submitting a post!<br>After our staff has gone over your submission we will publish it on our site.', 'bluthemes'); ?></p>
				</div>
			</div>
			<?php 
		}else{
		
			blt_post_thumbnail(); 

			if(have_posts()){ 
			
				while(have_posts()){ 

					the_post(); ?>
					<div class="page-body">
						<h1 class="page-title"><?php the_title(); ?></h1>
						<div class="page-text">
							<?php the_content(); ?>
						</div>
					</div><?php
				}
			} ?>
			<hr><?php

			if(!is_user_logged_in()){ ?>

				<div class="alert alert-danger"><?php _e('You need to log in to add a post', 'bluthemes') ?></div><?php 
			}	

			elseif(!blt_has_access(blt_get_option('user_posts_access', array('subscriber')))){ ?>

				<div class="alert alert-danger"><?php _e('You don\'t have sufficent access rights to submit a post', 'bluthemes') ?></div><?php 

			}else{ ?>
				<div class="users-posts-wrap"><?php

					blt_show_error_messages();

					?>
					<form id="blt_posts_form" action="<?php echo blt_get_option('user_posts_page_id') ?>" method="POST" enctype="multipart/form-data">

						<div class="form-group">
							<label for="blt_post_title"><?php _e('Title', 'bluthemes'); ?></label>
							<input class="form-control" name="blt_post_title" id="blt_post_title" type="text" value="<?php echo isset($_POST['blt_post_title']) ? $_POST['blt_post_title'] : '' ?> "/>
						</div>

						<div class="form-group">
							<label for="blt_post_info_url"><?php _e('Source URL (optional)', 'bluthemes'); ?></label>
							<input class="form-control" name="blt_post_info_url" id="blt_post_info_url" type="text" value="<?php echo isset($_POST['blt_post_info_url']) ? $_POST['blt_post_info_url'] : '' ?>"/>
						</div>

						<div class="form-group">
							<label for="blt_post_info_name"><?php _e('Source Name (optional)', 'bluthemes'); ?></label>
							<input class="form-control" name="blt_post_info_name" id="blt_post_info_name" type="text" value="<?php echo isset($_POST['blt_post_info_name']) ? $_POST['blt_post_info_name'] : '' ?>"/>
						</div>

						<div class="form-group">
							<label for="blt_post_tags"><?php _e('Tags', 'bluthemes'); ?></label>
							<input class="form-control" name="blt_post_tags" id="blt_post_tags" type="text" value="<?php echo isset($_POST['blt_post_tags']) ? $_POST['blt_post_tags'] : '' ?>" />
							<p class="help-block"><?php _e('Separate each tag by comma. (tag1, tag2, tag3)', 'bluthemes') ?></p>
						</div><?php


						/*$user_posts_categories = blt_get_option('user_posts_categories', false);
							
							$include = array();

							if(!empty($user_posts_categories)){

								foreach($user_posts_categories as $key => $value){
									if(!empty($value)){
										$include[] = $value;
									}
								}
							}*/


						?>
						<div class="form-group">

							<label for="blt_post_category"><?php _e('Category', 'bluthemes'); ?></label><?php
							/*query_posts('cat=35');
							if ( have_posts() ) : while ( have_posts() ) : the_post();
   							the_content();
							endwhile; endif;*/
							wp_dropdown_categories(array(
								'orderby'            => 'NAME', 
								'hide_empty'         => 0, 
								'include'            => '35,30,33,',
								'selected'           => (isset($_POST['blt_post_category']) ? $_POST['blt_post_category'] : ''),
								'hierarchical'       => 1, 
								'name'               => 'blt_post_category',
								'id'                 => 'blt_post_category',
								'class'              => 'form-control',
								'hide_if_empty'      => false
							));
							?>
						</div>

						<div class="form-group">
							<label for="blt_post_content"><?php _e('Description', 'bluthemes'); ?></label>
							<?php wp_editor( (isset($_POST['blt_post_content']) ? stripslashes($_POST['blt_post_content']) : '') , 'blt_post_content', array(
								'media_buttons' => false,
								'teeny' => true,
								'quicktags' => false,
								'textarea_rows' => get_option('default_post_edit_rows', 5),
							)) ?>
						</div>

						<div role="tabpanel">

						  	<ul class="nav nav-arrows" role="tablist">
						    	<li role="presentation" class="active"><a href="#image-post" aria-controls="image-post" role="tab" data-toggle="tab"><?php _e('Images', 'bluthemes'); ?></a></li>
						    	<li role="presentation"><a href="#video-post" aria-controls="video-post" role="tab" data-toggle="tab"><?php _e('Video', 'bluthemes'); ?></a></li>
						  	</ul>

  							<div class="tab-content">

								<div role="tabpanel" id="image-post" class="tab-pane active form-group new-post-list blt_post_files">
									<div class="list-group">
				  						<div class="list-group-item clearfix image-item">
											<div class="fileinput fileinput-new" data-provides="fileinput">

											<div class="row">
												<div class="col-md-5">
													<label><?php _e('Image', 'bluthemes'); ?></label>
													<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="display: block; width: 100%; min-height: 150px;"></div>
													<div class="form-group" style="display: block; float: left; width: 100%; margin-top: 15px; padding-top: 15px; border-top: 1px solid #EEEEEE;">
														<input type="radio" class="blt_make_featured_image" name="blt_make_featured_image" value="0">
														<label for="blt_make_featured_image"><?php _e('Make Featured Image', 'bluthemes'); ?></label>
													</div>
												</div>
												<div class="col-md-7">
													<div class="form-group">
														<label for="blt_post_files"><?php _e('Image Title (optional)', 'bluthemes'); ?></label>
														<input type="text" class="blt_post_files_title form-control" name="blt_post_files_title[]">
													</div>
													<div class="form-group">
														<label for="blt_post_files"><?php _e('Image Source Name (optional)', 'bluthemes'); ?></label>
														<input type="text" class="form-control" name="blt_post_files_source_name[]">
													</div>
													<div class="form-group">
														<label for="blt_post_files"><?php _e('Image Source URL (optional)', 'bluthemes'); ?></label>
														<input type="text" class="form-control" name="blt_post_files_source_url[]">
													</div>
													<div>
														<span class="btn btn-default btn-file">
															<span class="fileinput-new"><i class="fa fa-cloud-upload"></i> <?php _e('Select image', 'bluthemes') ?></span>
															<span class="fileinput-exists"><i class="fa fa-cloud-upload"></i> <?php _e('Change', 'bluthemes') ?></span>
															<input type="file" name="blt_post_files[]">
														</span>
														<a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> <?php _e('Remove', 'bluthemes') ?></a>
													</div>
												</div>
											</div>


											</div>
				  						</div>
										<a href="#new-image" class="list-group-item blt-add-new-image"><i class="fa fa-plus"></i> <?php _e('Add another image', 'bluthemes') ?></a>
				  					</div>	
								</div>
							
								<div role="tabpanel" id="video-post" class="tab-pane form-group new-post-list">
									<ul class="list-group">
				  						<li class="list-group-item clearfix image-item">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<label for="video_url"><?php _e('Video URL', 'bluthemes'); ?></label>
														<input type="text" class="video_url form-control" name="video_url">
														<hr>
														<p class="text-muted"><?php _e('Note: You must also provide at least one image from the image tab to use with the post. You can use a service like <a href="http://www.youtube-image.com" target="_blank">youtube-image.com</a> to find an image for youtube videos.'); ?></p>
													</div>
												</div>
											</div>
				  						</li>
				  					</ul>	
								</div>

							</div>
						
						</div>

						<button class="btn btn-theme btn-lg" data-loading-text="<?php _e('Loading...', 'bluthemes') ?>" type="submit"><?php _e('Submit Post', 'bluthemes'); ?></button>

						<?php echo wp_nonce_field('blt_post_upload_form', 'blt_post_upload_form_submitted'); ?>

					</form>

				</div><?php

			}

		} ?>

	</div><?php

	# 	
	# 	SIDEBAR
	# 	========================================================================================
	#   Load the sidebar if needed
	# 	========================================================================================
	# 		
	if(in_array(blt_get_option('sidebar_layout', 'right'), array('left', 'right'), true)){
		get_sidebar();
	} ?>
	
</div>

<?php get_footer(); ?>