<?php

global $wpdb;

	$post_id = isset($_POST['post_id']) ? esc_html( $_POST['post_id'] ) : get_the_ID();
	


	$layout = get_field('top_posts_layout', $post_id);

		if(empty($layout)){
			$layout = 'list';
		}


	# Get the GET variable and multiply it to load the same posts as before
	$posts_loaded = (isset($_GET['page']) and is_numeric($_GET['page']) and $_GET['page'] < 100) ? $_GET['page'] : 1;

	# Get variables
	$page_settings = array(
	     'posts_per_page'    => get_field('top_posts_number_of_posts', $post_id) * $posts_loaded,
	     'category__in'      => get_field('top_posts_categories', $post_id),
	     'ordering'      	 => get_field('top_posts_ordering', $post_id),
	     'tag__in'           => get_field('top_posts_tags', $post_id),
	     'offset'          	 => isset($paged) ? abs($paged) * get_field('top_posts_number_of_posts', $post_id ) : 0,
	);

	     # Make them safe
	     if(is_array($page_settings['category__in'])){
	          $page_settings['category__in'] = array_map('intval', array_filter($page_settings['category__in'], 'is_numeric'));
	     }
	     if(is_array($page_settings['tag__in'])){
	          $page_settings['tag__in'] = array_map('intval', array_filter($page_settings['tag__in'], 'is_numeric'));
	     }
	     if(!is_numeric($page_settings['posts_per_page'])){
	          $page_settings['posts_per_page'] = 10;
	     }

	# Set Defaults
	$query_args = wp_parse_args($page_settings, array(
	     'posts_per_page' => 10 * $posts_loaded,
	     'category__in' => array(),
	     'tag__in' => array(),
	     'orderby' => 'date',
	     'offset' => 0,
	     'post_status' => 'publish',
	     'ignore_sticky_posts' => 1
	));


	// m.meta_value AS blt_post_views, 
	// LEFT JOIN '.$wpdb->postmeta.' m ON m.post_id = p.ID AND m.meta_key = "blt_post_views" 


	$q = 'SELECT p.ID, ';


		switch($page_settings['ordering']){
			

			case 'downvotes':
			case 'upvotes':
			case 'views':
				$q .= 'CAST(l.meta_value as UNSIGNED) AS blt_total_score ';
				break;

			case 'newest':
			case 'oldest':
				$q .= 'p.post_date AS blt_total_score ';
				break;
			
			case 'trending':
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


		switch($page_settings['ordering']){
			
			case 'upvotes':
				$q .= 'LEFT JOIN '.$wpdb->postmeta.' l ON l.post_id = p.ID AND l.meta_key = "blt_upvotes" ';
				break;

			case 'downvotes':
				$q .= 'LEFT JOIN '.$wpdb->postmeta.' l ON l.post_id = p.ID AND l.meta_key = "blt_downvotes" ';
				break;

			case 'views':
				$q .= 'LEFT JOIN '.$wpdb->postmeta.' l ON l.post_id = p.ID AND l.meta_key = "blt_post_views" ';
				break;

			case 'newest':
			case 'oldest':
				break;
			
			case 'trending':
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


	$q .= ' GROUP BY p.ID';


	// Ordering
	switch($page_settings['ordering']){
		
		case 'oldest':
			$q .= ' ORDER BY p.post_date ASC';
			break;
		
		default:
			$q .= ' ORDER BY blt_total_score DESC';
			break;
	}



	$q .= ' LIMIT '.$page_settings['posts_per_page'];

	          # Add tag filter if needed
	          if(!empty($query_args['offset'])){
	            $q .= ' OFFSET '. $query_args['offset'];
	          }

    $post_ids = get_transient( 'blt_posts_'.md5($q) );

	    if($post_ids === false){

			$posts = $wpdb->get_results($q, OBJECT);

			if(!empty($posts)){
				
				$post_ids = array();
			
				foreach($posts as $key => $value){
					$post_ids[] = $value->ID;
				}
	        	
	        	set_transient( 'blt_posts_'.md5($q), $post_ids, 60 * 30 ); // 30 mins
	    	
			}
	    }

	if(!empty($post_ids)){

		
		$query = new WP_Query(array(
			'post__in' => $post_ids,
		    'post_status' => 'publish',
			'ignore_sticky_posts' => true,
			'orderby' => 'post__in',
			'posts_per_page' => $page_settings['posts_per_page']
		));

		// The Loop
		if($query->have_posts()){
		
			while($query->have_posts()){

				$query->the_post();

				if($layout != 'list'){
					get_template_part( 'inc/template-parts/content', $layout );
				}else{
					get_template_part( 'inc/template-parts/content', 'list' );
				}

			}
		
		}else{
			$has_posts = false;
		}
	}else{
		$has_posts = false;
	}    


	// if(!empty($posts)){

	//     foreach($posts as $value){
	        
	//         # Prepare post data
	//         setup_postdata($GLOBALS['post'] = &$value);

	// 	}

	// }else{
	// }

	wp_reset_postdata();