<?php
/*
Plugin Name: Bluthemes Posts Widget
Description: Fetch posts and display them in different layouts
Author: Bluthemes
Version: 1.0
Author URI: http://www.bluthemes.com/
*/
class blt_posts extends WP_Widget{

	public function __construct() {
		parent::__construct('blt_posts', 'Bluthemes - Posts', array('classname' => 'blt_posts', 'description' => 'Display posts'));
	}

	function form($instance){

		wp_enqueue_script('suggest');

		$instance = wp_parse_args((array)$instance, array(
			'title' => '',
			'posts_per_page' => 3,
			'cat_posts' => '',
			'tag_posts' => '',
			'layout' => 'list',
			'orderby' => 'date',
			'date' => 'all_time'
		)); 

		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title', 'bluthemes'); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>

		<!-- POSTS PER PAGE -->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('posts_per_page')); ?>">
				<?php _e('Posts Per Load:', 'bluthemes'); ?>
				<small><?php _e('How many posts to load', 'bluthemes'); ?></small>
			</label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('posts_per_page')); ?>" name="<?php echo esc_attr($this->get_field_name('posts_per_page')); ?>" type="text" value="<?php echo esc_attr($instance['posts_per_page']); ?>" />
		</p>

		<!-- CATEGORIES -->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('cat_posts')); ?>">
				<?php _e('Only Show Categories:', 'bluthemes'); ?>
				<small><?php _e('Hold CTRL for multiple', 'bluthemes'); ?></small>
			</label>
			<select style="min-height: 108px;" class="widefat" id="<?php echo esc_attr($this->get_field_id('cat_posts')); ?>" name="<?php echo esc_attr($this->get_field_name('cat_posts')); ?>[]" multiple><?php 
			
				 
				$categories = get_terms('category', 'orderby=count&hide_empty=0');

				foreach($categories as $category) {
					if(is_array($instance['cat_posts']) and in_array($category->term_id, $instance['cat_posts'])){

						echo '<option value="'.esc_attr($category->term_id).'" selected>'.esc_html($category->name).($category->count == 0 ? ' (empty)' : '').'</option>';
					}else{
					}
						echo '<option value="'.esc_attr($category->term_id).'">'.esc_html($category->name).($category->count == 0 ? ' (empty)' : '').'</option>';

				} ?>
			</select>
		</p>

		<!-- TAGS -->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('tag_posts')); ?>">
				<?php _e('Only Show Tags', 'bluthemes'); ?>
				<small><?php _e('Separated by comma', 'bluthemes'); ?></small>
			</label><?php

				$tags = array();
				foreach(explode(',', $instance['tag_posts']) as $tag){

					$tag_obj = get_term_by('id', $tag, 'post_tag');

					if(isset($tag_obj->name)){
						$tags[] = $tag_obj->name;
					}
				}

				$instance['tag_posts'] = implode(',', $tags);

			?>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('tag_posts')); ?>" name="<?php echo esc_attr($this->get_field_name('tag_posts')); ?>" type="text" value="<?php echo esc_attr($instance['tag_posts']); ?>" onfocus="setSuggestTags('<?php echo esc_attr($this->get_field_id('tag_posts')); ?>');" />
		</p>
				
		<!-- ORDER BY -->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('orderby')); ?>">
				<?php _e('Order By:', 'bluthemes'); ?>
				<small><?php _e('How to order the posts', 'bluthemes'); ?></small>
			</label>
			<select class="widefat" id="<?php echo esc_attr($this->get_field_id('orderby')); ?>" name="<?php echo esc_attr($this->get_field_name('orderby')); ?>">
				<option value="date" <?php selected( $instance['orderby'], 'date'); ?>>Date</option>
				<option value="popular"<?php selected( $instance['orderby'], 'popular'); ?>>Popular - Reddit like "Hot" algorithm</option>
				<option value="views"<?php selected( $instance['orderby'], 'views'); ?>>Views - Posts with the most views</option>
				<option value="upvotes"<?php selected( $instance['orderby'], 'upvotes'); ?>>Upvotes - Posts with the most upvotes</option>
				<option value="downvotes"<?php selected( $instance['orderby'], 'downvotes'); ?>>Downvotes - Posts with the most downvotes</option>
				<option value="rand"<?php selected( $instance['orderby'], 'rand'); ?>>Random</option>
			</select>
		</p>
				
		<!-- DATE -->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('date')); ?>">
				<?php _e('Date:', 'bluthemes'); ?>
				<small><?php _e('Get posts by creation date', 'bluthemes'); ?></small>
			</label>
			<select class="widefat" id="<?php echo esc_attr($this->get_field_id('date')); ?>" name="<?php echo esc_attr($this->get_field_name('date')); ?>">
				<option value="all_time" <?php selected( $instance['date'], 'all_time'); ?>>All time</option>
				<option value="today" <?php selected( $instance['date'], 'today'); ?>>Today</option>
				<option value="3_days" <?php selected( $instance['date'], '3_days'); ?>>Last 3 days</option>
				<option value="7_days" <?php selected( $instance['date'], '7_days'); ?>>Last 7 days</option>
				<option value="14_days" <?php selected( $instance['date'], '14_days'); ?>>Last 14 days</option>
				<option value="30_days" <?php selected( $instance['date'], '30_days'); ?>>Last 30 days</option>
				<option value="60_days" <?php selected( $instance['date'], '60_days'); ?>>Last 60 days</option>
				<option value="90_days" <?php selected( $instance['date'], '90_days'); ?>>Last 90 days</option>
			</select>
		</p>
				
		<!-- LAYOUT -->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('layout')); ?>">
				<?php _e('Layout:', 'bluthemes'); ?>
				<small><?php _e('The way the posts render on the page', 'bluthemes'); ?></small>
			</label>
			<select class="widefat" id="<?php echo esc_attr($this->get_field_id('layout')); ?>" name="<?php echo esc_attr($this->get_field_name('layout')); ?>">
				<option value="grid" <?php selected( $instance['layout'], 'grid'); ?>>Grid</option>
				<option value="list"<?php selected( $instance['layout'], 'list'); ?>>List</option>
			</select>
		</p>

		<script type="text/javascript" >
		    // Function to add auto suggest
		    if (typeof(setSuggestTags) !== "function") { 
			    function setSuggestTags(id) {
			        jQuery('#' + id).suggest(ajaxurl+"?action=ajax-tag-search&tax=post_tag", {multiple:true, multipleSep: ","});
			    }
		    }
	    </script><?php
	}

	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		$instance['cat_posts']		= $new_instance['cat_posts'];
		$instance['title']			= strip_tags($new_instance['title']);
		$instance['posts_per_page']	= strip_tags($new_instance['posts_per_page']);
		$instance['layout']			= strip_tags($new_instance['layout']);
		$instance['orderby']		= strip_tags($new_instance['orderby']);
		$instance['date']			= strip_tags($new_instance['date']);

			$tags = array();
   	  		foreach(explode(',', rtrim(strip_tags($new_instance['tag_posts']), ',')) as $tag){
				
				$tag_obj = get_term_by('name', $tag, 'post_tag');
   	  			
	   	  			if(isset($tag_obj->term_id)){
	   	  				$tags[] = $tag_obj->term_id;
	   	  			}
   	  		}
		
		$instance['tag_posts'] = implode(',', $tags);

		return $instance;
	}

	function widget($args, $instance){

		extract($args);

		#
		# Get posts
		#

			# Set Defaults
			$query_args = wp_parse_args($instance, array(
				'posts_per_page' => $instance['posts_per_page'],
				'orderby' => 'date',
				'offset' => 0,
				'post_status' => 'publish',
				'ignore_sticky_posts' => true
			));

		     # Make them safe
			if(!is_numeric($query_args['posts_per_page'])){
				$query_args['posts_per_page'] = 10;
			}
	        if(!empty($instance['cat_posts']) and is_array($instance['cat_posts'])){
	        	$query_args['category__in'] = array_map('intval', array_filter($instance['cat_posts'], 'is_numeric'));
	        } 
			# Prepare tags filter
	        if(!empty($instance['tag_posts'])){
	        	$query_args['tag__in'] = array_map('intval', array_filter($instance['tag_posts'], 'is_numeric'));
	        } 
			# Prepare date filter
	        if(!empty($instance['date']) and $instance['date'] != 'all_time'){

				$dates = array(
					'today' => 'today',
					'3_days' => 'midnight 3 days ago',
					'7_days' => 'midnight 7 days ago',
					'14_days' => 'midnight 14 days ago',
					'30_days' => 'midnight 30 days ago',
					'60_days' => 'midnight 60 days ago',
					'90_days' => 'midnight 90 days ago',
				);

	        	$query_args['date_query'] = array(
	        		'after' => $dates[$instance['date']]
	        	);


	        } 


	        if(in_array($query_args['orderby'], array('popular', 'downvotes', 'upvotes', 'views'))){

	        	global $wpdb;

				$q = 'SELECT p.ID, ';


					switch($query_args['orderby']){
						

						case 'downvotes':
						case 'upvotes':
						case 'views':
							$q .= 'CAST(l.meta_value as UNSIGNED) AS blt_total_score ';
							break;
						
						case 'popular':
						default:
							$q .= '(LOG10( IFNULL(l.meta_value, 1) + 1) * 287015) + UNIX_TIMESTAMP(p.post_date_gmt) AS blt_total_score ';
							break;
					}


				$q .= 'FROM '.$wpdb->posts.' p ';

			          # Add category filter if needed
			          if(!empty($query_args['category__in'])){
			          	$q .= 'LEFT JOIN '.$wpdb->term_relationships.' r ON r.object_id = p.ID LEFT JOIN '.$wpdb->term_taxonomy.' t ON t.term_taxonomy_id = r.term_taxonomy_id ';
			          }

			          # Add tag filter if needed
			          if(!empty($query_args['tag__in'])){
			          	$q .= 'LEFT JOIN '.$wpdb->term_relationships.' r1 ON r1.object_id = p.ID LEFT JOIN '.$wpdb->term_taxonomy.' t1 ON t1.term_taxonomy_id = r1.term_taxonomy_id ';
			          }

					switch($query_args['orderby']){
						
						case 'upvotes':
							$q .= 'LEFT JOIN '.$wpdb->postmeta.' l ON l.post_id = p.ID AND l.meta_key = "blt_upvotes" ';
							break;

						case 'downvotes':
							$q .= 'LEFT JOIN '.$wpdb->postmeta.' l ON l.post_id = p.ID AND l.meta_key = "blt_downvotes" ';
							break;

						case 'views':
							$q .= 'LEFT JOIN '.$wpdb->postmeta.' l ON l.post_id = p.ID AND l.meta_key = "blt_post_views" ';
							break;
						
						case 'popular':
						default:
							$q .= 'LEFT JOIN '.$wpdb->postmeta.' l ON l.post_id = p.ID AND l.meta_key = "blt_score" ';
							break;
					}


			    $q .= 'WHERE p.post_status = "publish" AND p.post_type = "post"';

			        # Add category filter if needed
			        if(!empty($query_args['category__in'])){
			        	$q .= ' AND t.term_id IN ('.implode(',', $query_args['category__in']).')'; 
			        }

			        # Add tag filter if needed
			        if(!empty($query_args['tag__in'])){
			        	$q .= ' AND t1.term_id IN('.implode(',', $query_args['tag__in']).')';
			        }

			        # Filter by date
			        if(!empty($query_args['date_query'])){
			        	$q .= ' AND p.post_date > "'.date('Y-m-d H:i:s', strtotime($query_args['date_query']['after'])) . '"';
			        }


				$q .= ' GROUP BY p.ID';
				$q .= ' ORDER BY blt_total_score DESC';

				$q .= ' LIMIT '.$query_args['posts_per_page'];

				          # Add tag filter if needed
				          if(!empty($query_args['offset'])){
				            $q .= ' OFFSET '. $query_args['offset'];
				          }

			    // $post_ids = get_transient( 'blt_posts_'.md5($q) );

				    // if($post_ids === false){

						$posts = $wpdb->get_results($q, OBJECT);

						$post_ids = array();
						
						if(!empty($posts)){
							foreach($posts as $key => $value){
								$post_ids[] = $value->ID;
							}
						}
				    	
				        // set_transient( 'blt_posts_'.md5($q), $post_ids, 60 * 30 ); // 30 mins
				    // }
			
				$query_args['post__in'] = $post_ids;
				$query_args['orderby'] = 'post__in';
				unset($query_args['category__in']);
				unset($query_args['tag__in']);

			}

			unset($query_args['layout']);
			unset($query_args['title']);
			unset($query_args['date']);
			unset($query_args['tag_posts']);
			unset($query_args['cat_posts']);
			
			$query = new WP_Query($query_args);
		#
		# Begin Widget
		#
		echo $before_widget;


			# Title
			$title = apply_filters('widget_title', $instance['title']);
			
				if(!empty($title)){
					echo $args['before_title'] . esc_attr($title) . $args['after_title'];
				}

				if(!empty($instance['subtitle'])){
					echo '<p class="widget-subtitle">'.esc_html($instance['subtitle']).'</p>';
				}

			echo '<div class="layout-'.esc_attr($instance['layout']).'">';

				if($query->have_posts()){
					while($query->have_posts()){

						$query->the_post();


		                # Get image array
		                $image = has_post_thumbnail();

						echo '<article class="layout-'.esc_attr($instance['layout']).'-item'.(empty($image) ? ' image-empty' : '').'">';
							echo '<a href="'.get_the_permalink().'">';

								if($instance['layout'] == 'list' or (!empty($image) and $instance['layout'] == 'grid')){

									echo '<div class="post-image'.(empty($image) ? ' post-image-empty' : '').'">';
										if(!empty($image)){
											the_post_thumbnail( ($instance['layout'] == 'grid' ? 'md' : 'thumbnail'), array('alt' => get_the_title()));
										}
									echo '</div>';
								}

								echo '<div class="post-meta">';
									echo '<h4>'.get_the_title().'</h4>';
									echo '<p class="post-date text-muted"><i class="mdi-device-access-time"></i>&nbsp;'.get_the_date().'</p>';
								echo '</div>';
							echo '</a>';
						echo '</article>';

					} 
				}
			echo '</div>';
			
			wp_reset_query();

		echo $after_widget;
		#		
		# End Widget		
		#		
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("blt_posts");') );