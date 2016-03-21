<?php


if(!function_exists('blt_get_ad_spot')){
function blt_get_ad_spot($ad_spot_name){

	$ad_spot = blt_get_option($ad_spot_name, 'none');

	if($ad_spot != 'none'){

		switch($ad_spot){
			case 'html':
				echo '<div class="'.$ad_spot_name.' '.$ad_spot_name.'-html">';
					echo do_shortcode(blt_get_option($ad_spot_name.'_html'));
					// echo apply_filters('the_content', do_shortcode(blt_get_option($ad_spot_name.'_html')));
				echo '</div>';
				break;
			case 'image':
				echo '<div class="'.$ad_spot_name.' '.$ad_spot_name.'-image">';
					echo '<a href="'.blt_get_option($ad_spot_name.'_image_url').'" target="_blank"><img src="'.blt_get_option($ad_spot_name.'_image').'" alt=""></a>';
				echo '</div>';
				break;
			case 'adsense':
				echo '<div class="'.$ad_spot_name.' '.$ad_spot_name.'-gad">';
					echo BluCodes::adsense(array());
				echo '</div>';
				break;
		}
	}

}
}


if(!function_exists('get_share_buttons')){
function get_share_buttons(){

    $share_url = rawurlencode(esc_url(get_permalink()));
    $share_title = rawurlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8'));

	?>
	<a class="btn social-sharing share-facebook" onclick="blt_social_share(event, 'http://www.facebook.com/sharer.php?u=<?php echo $share_url; ?>&amp;t=<?php echo esc_attr($share_title); ?>')">
		<i class="fa fa-facebook"></i> <span class="hidden-xs hidden-sm"><?php _e('Share on Facebook', 'bluthemes') ?></span><span class="visible-xs-inline visible-sm-inline"><?php _e('Share', 'bluthemes') ?></span>
	</a>
	<a class="btn social-sharing share-twitter" onclick="blt_social_share(event, 'http://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&amp;text=<?php echo esc_attr($share_title); ?>')">
		<i class="fa fa-twitter"></i> <span class="hidden-xs hidden-sm"><?php _e('Share on Twitter', 'bluthemes') ?></span><span class="visible-xs-inline visible-sm-inline"><?php _e('Tweet', 'bluthemes') ?></span>
	</a>
	<?php
}
}

if(!function_exists('blt_get_post_meta')){
function blt_get_post_meta($skip_these = array(), $show_these = array()){


	if(is_single()){
		$meta_settings = blt_get_option('single_meta_data');
	}else{
		$meta_settings = blt_get_option('content_meta_data');
	}


	$meta_settings = empty($meta_settings) ? array() : $meta_settings;

	// remove these keys
	if(!empty($skip_these)){

		foreach($meta_settings as $key => $meta_setting){

			if(in_array($meta_setting, $skip_these)){

				unset($meta_settings[$key]);

			}
		}

	}
	// show these keys
	if(!empty($show_these)){

		foreach($show_these as $key => $show_this){

			if(!in_array($show_this, $meta_settings)){

				$meta_settings[] = $show_this;

			}
		}
	}

	// Date
	if(in_array('date', $meta_settings, true)){

		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			get_the_date(),
			esc_attr( get_the_modified_date( 'c' ) ),
			get_the_modified_date()
		);

		printf( '<span class="posted-on"><i class="fa fa-calendar-o"></i> <span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
			_x( 'Posted on', 'Used before publish date.', 'bluthemes' ),
			esc_url( get_permalink() ),
			$time_string
		);
	}

	// Author
	if(in_array('author', $meta_settings, true)){
	
		printf( '<span class="byline"><i class="fa fa-pencil"></i><span class="author vcard"><span class="screen-reader-text">%1$s </span><a class="url fn n" href="%2$s">%3$s</a></span></span>',
			_x( 'Author', 'Used before post author name.', 'bluthemes' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			get_the_author()
		);
	}

	// Category
	if(in_array('category', $meta_settings, true)){

		$category = blt_single_category();
		
		if($category){
			echo '<span class="cat-links"><i class="fa fa-folder-o"></i><a href="'.esc_url(get_category_link($category->term_id)).'" title="'.esc_attr(sprintf(__( 'View all posts in %s', 'bluthemes'), $category->name)).'">'.esc_attr($category->name).'</a></span>';
		}
	}

	// Tags
	if(in_array('tags', $meta_settings, true)){
	
		$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'bluthemes' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links"><i class="fa fa-tags"></i><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Tags', 'Used before tag names.', 'bluthemes' ),
				$tags_list
			);
		}
	}

	// Comments
	if(in_array('comments', $meta_settings, true)){

		if(!post_password_required() and comments_open() and get_comments_number()){
			echo '<span class="comments-link">';
			echo '<i class="fa fa-comment-o"></i> ';
			comments_popup_link( '', __( '1 Comment', 'bluthemes' ), __( '% Comments', 'bluthemes' ) );
			echo '</span>';
		}
	}

	if(in_array('views', $meta_settings, true)){
		$views = get_post_meta( get_the_ID(), 'blt_post_views', true );
		if(!empty($views) and $views > 0){

			echo '<span class="views">';
			echo '<i class="fa fa-eye"></i> '. $views;
			echo '</span>';

		}
	}
}
}



#  
#  BLT POST THUMBNAIL
#  ========================================================================================
#   Determines how the thumbnail should be presented (Video, gallery depending on post format)
#  ========================================================================================
#   
   	if(!function_exists('blt_post_thumbnail')){
	function blt_post_thumbnail() {

		if(post_password_required()){
			return;
		} ?>

		<div class="post-thumbnail-area"><?php

			switch(get_post_format()){ 

				case 'video':

					if($video_url = get_field('video_url', get_the_ID())){ ?>

						<div class="post-video embed-responsive embed-responsive-16by9"><?php echo apply_filters( 'the_content', $video_url) ?></div><?php
						
					}elseif($video_embed_code = get_field('video_embed_code', get_the_ID())){ ?>

						<div class="post-video embed-responsive embed-responsive-16by9"><?php echo $video_embed_code ?></div><?php

					}elseif(has_post_thumbnail() and is_singular()){ ?>
						<div class="post-thumbnail"><?php echo get_the_post_thumbnail( get_the_ID(), 'hg') ?></div><?php
					}
				break;
					
				case 'audio': 

					if($audio_url = get_field('audio_url', get_the_ID())){ ?>

						<div class="post-audio"><?php echo apply_filters( 'the_content', $audio_url) ?></div><?php
						
					}elseif($audio_embed_code = get_field('audio_embed_code', get_the_ID())){ ?>

						<div class="post-audio"><?php echo $audio_embed_code ?></div><?php

					}elseif(has_post_thumbnail() and is_singular()){ ?>
						<div class="post-thumbnail"><?php echo get_the_post_thumbnail( get_the_ID(), 'hg') ?></div><?php
					} 
				break;

				case 'quote':
            		
            		$quote_author       = get_field('quote_author');
		            $quote_author_url   = get_field('quote_author_url');
		            $quote_text         = get_field('quote_text');
		            ?>
	                <div class="post-quote<?php echo (has_post_thumbnail() ? ' absolute' : '') ?>">
	                	<div>
			            	<p><?php echo esc_attr($quote_text) ?></p><?php

		                    if($quote_author){ ?>
		                        <a href="<?php echo esc_url($quote_author_url) ?>"><?php echo esc_html($quote_author) ?></a><?php
		                    } ?>

		            	</div>
		            </div><?php

					if(has_post_thumbnail() and is_singular()){ ?>
						<div class="post-thumbnail"><?php echo get_the_post_thumbnail( get_the_ID(), 'hg') ?></div><?php
					} 		            

				break;


				default:

					$images = get_field('blt_gallery');

					// Get the meta data if we need to
					if(!empty($images) and is_int($images[0])){

						foreach($images as $key => &$value) {
							$value = acf_get_attachment($value);
						}
					}


					if( $images ){ 

						$sidebar = get_field('blt_sidebar');

							if(empty($sidebar)){
								$sidebar = blt_get_option('default_post_sidebar_layout', 'none');
							}

						$image_size = $sidebar == 'none' ? 'hg' : 'lg';	 ?>

						<ul class="list-unstyled blt-gallery-list"><?php
							
							foreach($images as $image){ 
								
								if(isset($image['sizes'])){ ?>

									<li><?php

										if(!empty($image['caption'])){ ?>
											<h2><?php echo esc_attr($image['caption']); ?></h2><?php
										}

										elseif(!empty($image['title']) and $image['title'] !== str_replace(array('.jpg', '.gif', '.jpeg', '.png'), '', $image['filename'])){ ?>
											<h2><?php echo esc_attr($image['title']) ?></h2><?php
										}

										// Alt tag
										if(!empty($image['alt'])){
											$alt = $image['alt'];
										}
										elseif(!empty($image['caption'])){
											$alt = $image['caption'];
										}
										elseif(!empty($image['title'])){
											$alt = $image['title'];
										}
										else{
											$alt = $image['name'];
										} ?>
										<img class="img-responsive" src="<?php echo esc_url($image['sizes'][$image_size]) ?>" alt="<?php echo esc_attr($alt) ?>"><?php

										$source_name = get_post_meta($image['ID'], 'blt_post_files_source_name');
										$source_url = get_post_meta($image['ID'], 'blt_post_files_source_url');

										if(!empty($source_name[0])){ ?>
											
											<strong><?php _e('Source:', 'bluthemes'); ?></strong>&nbsp;<?php

											if(empty($source_url[0])){ ?>
												<span style="display: inline-block; margin-top: 5px;"><?php echo $source_name[0]; ?></span><?php
											}else{ ?>
												<a style="display: inline-block; margin-top: 5px;" href="<?php echo $source_url[0]; ?>" target="_blank"><?php echo $source_name[0]; ?></a><?php
											}

										} ?>
									
									</li><?php
								}

							} ?>

						</ul><?php
					
					}elseif(has_post_thumbnail() and is_singular() and !get_field('hide_featured_image')){ ?>
						<div class="post-thumbnail"><?php echo get_the_post_thumbnail( get_the_ID(), 'hg') ?></div><?php
					}

				break;
			} ?>

		</div><?php

	}
	}


#  
#  BLT ARCHIVE TITLE
#  ========================================================================================
#   Overwrites the default archive_title and adds some span tags around, for more customization
#  ========================================================================================
#   
	
	function blt_archive_title($before = '', $after = ''){

	    if ( is_category() ) {
	        $title = sprintf( __( 'Category: %s', 'bluthemes'), '<span class="categories">' . single_cat_title( '', false ) ) . '</span>';
	    } elseif ( is_tag() ) {
	        $title = sprintf( __( 'Tag: %s', 'bluthemes'), '<span class="tags">' . single_tag_title( '', false ) . '</span>' );
	    } elseif ( is_author() ) {
	        $title = sprintf( __( 'Author: %s', 'bluthemes'), '<span class="vcard">' . get_the_author() . '</span>' );
	    } elseif ( is_year() ) {
	        $title = sprintf( __( 'Year: %s', 'bluthemes'), '<span class="year">' . get_the_date( _x( 'Y', 'yearly archives date format', 'bluthemes' ) ) . '</span>' );
	    } elseif ( is_month() ) {
	        $title = sprintf( __( 'Month: %s', 'bluthemes'), '<span class="month">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'bluthemes' ) ) . '</span>' );
	    } elseif ( is_day() ) {
	        $title = sprintf( __( 'Day: %s', 'bluthemes'), '<span class="day">' . get_the_date( _x( 'F j, Y', 'daily archives date format', 'bluthemes' ) ) . '</span>' );
	    } elseif ( is_tax( 'post_format' ) ) {
	        if ( is_tax( 'post_format', 'post-format-aside' ) ) {
	            $title = _x( 'Asides', 'post format archive title', 'bluthemes' );
	        } elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
	            $title = _x( 'Galleries', 'post format archive title', 'bluthemes' );
	        } elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
	            $title = _x( 'Images', 'post format archive title', 'bluthemes' );
	        } elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
	            $title = _x( 'Videos', 'post format archive title', 'bluthemes' );
	        } elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
	            $title = _x( 'Quotes', 'post format archive title', 'bluthemes' );
	        } elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
	            $title = _x( 'Links', 'post format archive title', 'bluthemes' );
	        } elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
	            $title = _x( 'Statuses', 'post format archive title', 'bluthemes' );
	        } elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
	            $title = _x( 'Audio', 'post format archive title', 'bluthemes' );
	        } elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
	            $title = _x( 'Chats', 'post format archive title', 'bluthemes' );
	        }
	    } elseif ( is_post_type_archive() ) {
	        $title = sprintf( __( 'Archives: %s', 'bluthemes' ), post_type_archive_title( '', false ) );
	    } elseif ( is_tax() ) {
	        $tax = get_taxonomy( get_queried_object()->taxonomy );
	        /* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
	        $title = sprintf( __( '%1$s: %2$s', 'bluthemes' ), $tax->labels->singular_name, single_term_title( '', false ) );
	    } else {
	        $title = __( 'Archives', 'bluthemes' );
	    }
	 
	    /**
	     * Filter the archive title.
	     *
	     * @since 4.1.0
	     *
	     * @param string $title Archive title to be displayed.
	     */
	    $title = $before . $title . $after;

	    return apply_filters( 'get_the_archive_title', $title );
	}


# 	
#	SINGLE CATEGORY
#	========================================================================================
#	Get a single category array
#	========================================================================================
#
   	if(!function_exists('blt_single_category')){
	function blt_single_category($field = false, $post_id = false){

		$categories = get_the_category( $post_id );


		if(!empty($categories) and $categories[0]->name != 'Uncategorized'){
		
			if($field){
				return $categories[0]->$field;
			}

			return $categories[0];
		}
		
		return false;
	}
	}