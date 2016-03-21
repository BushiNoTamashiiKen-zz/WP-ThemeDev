<?php


	// Layout
	if(is_category())
	{
		$post_layout = blt_get_option('category_post_layout', 'normal');
	}
	elseif(is_author())
	{
		$post_layout = blt_get_option('author_post_layout', 'normal');
	}
	elseif(is_archive())
	{
		$post_layout = blt_get_option('archive_post_layout', 'normal');
	}
	else
	{
		$post_layout = blt_get_option('home_post_layout', 'normal');
	}

	$i = 1;
	if(have_posts()){ 
	
		while(have_posts()){ 

			the_post();
			get_template_part( 'inc/template-parts/content', $post_layout );

			
			// Ad spot #4
			if($ad_spot_between_posts = blt_get_option('spot_between_posts', 'none') != 'none'){

				$ad_posts_frequency = blt_get_option('spot_between_posts_frequency', 3);

				// take into account ad frequency
				if(($i % (int) $ad_posts_frequency) == 0){

					switch($post_layout){

						case 'grid-3':
						case 'grid-2':
							echo '<div class="content-post col-xs-12 col-sm-12 col-md-4 col-lg-4">';
							break;

						case 'grid':
							echo '<div class="content-post '.(blt_has_sidebar() == 'none' ? 'col-sm-6 col-md-4 col-lg-4' : 'col-sm-6 col-md-4 col-lg-4').'">';
							break;
						
						default:
							echo '<div>';
							break;
					}


					blt_get_ad_spot( 'spot_between_posts' );

					echo '</div>';
				}
				
			}
			$i++;

		}

	}else{

		$has_posts = false;

	}