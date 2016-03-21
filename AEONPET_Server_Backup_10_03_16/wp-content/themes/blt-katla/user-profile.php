<?php
#   
#   Template Name: Bluthemes - Profile
#   ========================================================================================
#   This page template loads the currently logged in users profile
#   ========================================================================================
#  

get_header(); 
?>
<div id="site-content" class="clearfix">

	<div id="site-content-column" data-append-to="" class="users-posts-section"><?php

		if(!is_user_logged_in()){ ?>

			<div class="alert alert-warning"><?php _e('You need to log in to access this page', 'bluthemes') ?></div> <?php 

		}else{

			global $current_user;
			get_currentuserinfo();

			$user_votes = get_user_meta($current_user->ID, 'blt_user_votes', true);

			blt_post_thumbnail();  ?>

			<div class="page-body">
				
				<div class="page-user">

					<?php echo get_avatar( get_current_user_id(), '100' ); ?>

					<div class="page-user-info">
						
						<h3><?php echo $current_user->display_name; ?></h3>

						<div class="content-meta">
							
							<span><a href="<?php echo add_query_arg(array('status' => 'all'), get_permalink()); ?>"><?php _e('Posts: ', 'bluthemes'); echo count_user_posts( $current_user->ID ); ?></a></span><?php
							
							if(isset($user_votes['upvotes']) and count( $user_votes['upvotes'] ) > 0){ ?>

								<span><a href="<?php echo add_query_arg(array('sect' => 'upvotes'), get_permalink()); ?>"><?php _e('Upvoted Posts: ', 'bluthemes'); echo count( $user_votes['upvotes'] ); ?></a></span><?php

							}
							if(isset($user_votes['downvotes']) and count( $user_votes['downvotes'] ) > 0){ ?>

								<span><a href="<?php echo add_query_arg(array('sect' => 'downvotes'), get_permalink()); ?>"><?php _e('Downvoted Posts: ', 'bluthemes'); echo count( $user_votes['downvotes'] ); ?></a></span><?php

							} ?>

						</div>
					
					</div>

				</div>
				
				<div class="page-text">

					<?php the_content(); ?>

				</div>

			</div><?php

			#  
			#  SHOW DOWNVOTED POSTS
			#  ========================================================================================
			#  Determine if the user is trying to view the down/upvoted posts or the "all posts"
			#  ========================================================================================
			#   

				if(isset($_GET['sect']) and strpos($_GET['sect'], 'votes')){

					$paged = ( get_query_var('paged' ) and is_numeric( get_query_var('paged') ) ) ? get_query_var('paged') : 0;

					$post__in = array();
					foreach ($user_votes[$_GET['sect']] as $post_number => $score) {
						
						$post__in[] = (int)$post_number;
					
					}
					

					$query = new WP_Query(array(

						'author' => $current_user->ID,
						'post_status' => 'publish',
						'post__in' => $post__in,
						'paged' => esc_html( $paged )

					));

						
						// Layout
						$post_layout = 'list-sm';  ?>

						<div class="posts-list"><?php

							if(!$query->posts){ ?>
								
								<h3><?php _e('You haven\'t submitted any posts', 'bluthemes'); ?></h3><?php

							}

							// else{

								foreach ($query->posts as $post) {

									setup_postdata($GLOBALS['post'] = &$post);
									get_template_part( 'inc/template-parts/content', $post_layout );

								}
								
								wp_reset_postdata(); 
							
							// } 

							?>

						</div><?php

				}else{

				#  
				#  Get user's posts
				#  ========================================================================================
				#   

					$requested_status = ( isset($_GET['status']) and in_array($_GET['status'], array('all', 'publish', 'pending', 'trash'), true ) ) ? $_GET['status'] : 'all';
					$paged = ( get_query_var('paged' ) and is_numeric( get_query_var('paged') ) ) ? get_query_var('paged') : 0;

					$query = new WP_Query(array(

						'author' => $current_user->ID,
						'post_status' => array( esc_html( $requested_status ) ),
						'orderby' => 'ID',
						'paged' => esc_html( $paged )

					));
						?>

						<div role="tabpanel">

						  	<!-- Nav tabs -->
						  	<ul class="nav nav-tabs hidden-xs" role="tablist">

						    	<li role="presentation" <?php echo $requested_status == 'all' ? 'class="active"' : ''; ?>><a href="<?php echo add_query_arg(array('status' => 'all'), get_permalink()); ?>" role="tab"><?php _e('All Posts', 'bluthemes'); ?></a></li>
						    	<li role="presentation" <?php echo $requested_status == 'publish' ? 'class="active"' : ''; ?>><a href="<?php echo add_query_arg(array('status' => 'publish'), get_permalink()); ?>" role="tab"><?php _e('Published Posts', 'bluthemes'); ?></a></li>
						    	<li role="presentation" <?php echo $requested_status == 'pending' ? 'class="active"' : ''; ?>><a href="<?php echo add_query_arg(array('status' => 'pending'), get_permalink()); ?>" role="tab"><?php _e('Pending Posts', 'bluthemes'); ?></a></li>
						    	<li role="presentation" <?php echo $requested_status == 'trash' ? 'class="active"' : ''; ?>><a href="<?php echo add_query_arg(array('status' => 'trash'), get_permalink()); ?>" role="tab"><?php _e('Removed Posts', 'bluthemes'); ?></a></li>

						  	</ul>
							
							<div class="user-dropdown dropdown navbar-btn visible-xs">
								<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-expanded="true">
							    	<?php _e('Select Posts', 'bluthemes'); ?>
							    	<span class="caret"></span>
							  	</button>
							  	<ul class="dropdown-menu nav" role="tablist" aria-labelledby="dropdownMenu2">
						    		<li role="presentation"><a href="#all-posts" aria-controls="all-posts" data-toggle="tab"><?php _e('All Posts', 'bluthemes'); ?></a></li>
							    	<li role="presentation"><a href="#published-posts" aria-controls="published-posts" data-toggle="tab"><?php _e('Published Posts', 'bluthemes'); ?></a></li>
							    	<li role="presentation"><a href="#pending-posts" aria-controls="pending-posts" data-toggle="tab"><?php _e('Pending Posts', 'bluthemes'); ?></a></li>
							    	<li role="presentation"><a href="#removed-posts" aria-controls="removed-posts" data-toggle="tab"><?php _e('Removed Posts', 'bluthemes'); ?></a></li>
							    </ul>
						    </div>


							<div class="tab-content">
							
								<div role="tabpanel" id="all-posts" class="tab-pane active users-profile-wrap"><?php

									// Layout
									$post_layout = 'list-sm';  ?>

									<div class="posts-list"><?php

										if(!$query->posts){ ?>

											<article class="post list-sm-item list-sm-item-no-image"> 
												<div class="list-sm-meta">
													<h4 class="list-sm-title"><?php _e('No posts found', 'bluthemes'); ?></h4>
												</div>
											</article>
											<?php

										}else{ 									

											foreach ($query->posts as $post) {

												setup_postdata($GLOBALS['post'] = &$post);
												if($requested_status == 'all' or get_post_status() == $requested_status){
													get_template_part( 'inc/template-parts/content', $post_layout );
												}

											}
											
											wp_reset_postdata(); 

										}
										?>
									</div>

								</div>


							</div>

						</div><?php


				}

				#  
				#  PAGINATION
				#  ========================================================================================
				#   

				if ($query->max_num_pages > 1) { // check if the max number of pages is greater than 1  ?>
				  <nav class="prev-next-posts">
				    <div class="prev-posts-link pull-left">
				      <strong><?php echo get_next_posts_link( 'Older Entries', $query->max_num_pages ); // display older posts link ?></strong>
				    </div>
				    <div class="next-posts-link pull-right">
				      <strong><?php echo get_previous_posts_link( 'Newer Entries' ); // display newer posts link ?></strong>
				    </div>
				  </nav>
				<?php } 

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