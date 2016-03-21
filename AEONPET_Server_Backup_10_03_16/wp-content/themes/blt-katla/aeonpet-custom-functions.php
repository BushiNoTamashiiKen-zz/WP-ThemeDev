<?php
/**
 * Name: AeonPet Custom Functions
 *
 * Description: A library of several custom functions for the AeonPet Application
 * (This would normally go in functions.php but we're separating the logic)
 * 
 * @param (Several including $postID and $Comments and the walker class from wp-core)
 */

/**
 * Function to observe the number of times a post has been viewed
 *
 * @param $postID
 * 
 * Updated post meta value after observing view count
 */
if(!function_exists(aeonpet_observe_post_views)){
	function aeonpet_observe_post_views() {
		$count_key = 'post_views_count'; //saving custom field post_views_count in a variable
		$count = get_post_meta($postID, $count_key, true);

		if($count== ''){ //Check if the custom field value is empty
			$count = 0; //Set the counter to 0;
			delete_post_meta($postID, $count_key);
			add_post($postID, $count_key, '0');
		}
		else{
			$count++; //Increase custom field value by 1
			update_post_meta($postID, $count_key, $count); //Update post meta value to increased value
		}

	}
}

/**
 * Function to fetch post views and display
 *
 * @param $postID
 *
 */
if(!function_exists(aeonpet_fetch_post_views)) {
	function aeonpet_fetch_post_views() {
		$count_key = 'post_views_count';
		$count = get_post_meta($postID, $count_key, true);

		if($count == ''){
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, '0');

			return '0 view';
		}
		return $count. 'views';
	}
}

/**
 * Function to fetch most popular posts based on comment count
 * @param $num
 *
 */
if(!function_exists(aeonpet_popular_by_comment)){
	function aeonpet_popular_by_comment($num) {
	    global $wpdb;
	    
	    $posts = $wpdb->get_results("SELECT comment_count, ID, post_title FROM $wpdb->posts ORDER BY comment_count DESC LIMIT 0 , $num");
	    
	    foreach ($posts as $post) {
	        setup_postdata($post);
	        $id = $post->ID;
	        $title = $post->post_title;
	        $count = $post->comment_count;
	        
	        if ($count != 0) {
	            $popular .= '<li>';
	            $popular .= '<a href="' . get_permalink($id) . '" title="' . $title . '">' . $title . '</a> ';
	            $popular .= '</li>';
	        }
	    }
	    return $popular;
	}
}

/*function get_me_an_upgrade() {
	get_template_part('inc/template-parts/upgrade-form.php');
}*/

 /**
 * With love from wp core a checkbox walker class
 * Output an unordered list of checkbox <input> elements labelled
 * with term names. Taxonomy independent version of wp_category_checklist().
 *
 * @since 3.0.0
 *
 * @param int $post_id
 * @param array $args
 */
 /*function wp_terms_checklist_frontend($post_id = 0, $args = array()) {
	$defaults = array(
	    'descendants_and_self' => 0,
	    'popular_cats' => false,
	    'walker' => null,
	    'taxonomy' => 'category',
	    'checked_ontop' => true
	    );
	    $args = apply_filters( 'wp_terms_checklist_args', $args, $post_id );
	
	    extract( wp_parse_args($args, $defaults), EXTR_SKIP );
	
	    if ( empty($walker) || !is_a($walker, 'Walker') )
	        $walker = new Walker_Category_Checklist_Frontend;
	
	        $descendants_and_self = (int) $descendants_and_self;
	
	        $args = array('taxonomy' => $taxonomy);
	
	        $tax = get_taxonomy($taxonomy);
        $args['disabled'] = !current_user_can($tax->cap->assign_terms);
	
	        if ( is_array( $selected_cats ) )
	                $args['selected_cats'] = $selected_cats;
	        elseif ( $post_id )
	                $args['selected_cats'] = wp_get_object_terms($post_id, $taxonomy, array_merge($args, array('fields' => 'ids')));
	        else
	                $args['selected_cats'] = array();
	
	        if ( is_array( $popular_cats ) )
	                $args['popular_cats'] = $popular_cats;
	        else
	                $args['popular_cats'] = get_terms( $taxonomy, array( 'fields' => 'ids', 'orderby' => 'count', 'order' => 'DESC', 'number' => 10, 'hierarchical' => false ) );
	
	        if ( $descendants_and_self ) {
	                $categories = (array) get_terms($taxonomy, array( 'child_of' => $descendants_and_self, 'hierarchical' => 0, 'hide_empty' => 0 ) );
	                $self = get_term( $descendants_and_self, $taxonomy );
	                array_unshift( $categories, $self );
	        } else {
	                $categories = (array) get_terms($taxonomy, array('get' => 'all'));
	        }
	
	        if ( $checked_ontop ) {
	                // Post process $categories rather than adding an exclude to the get_terms() query to keep the query the same across all posts (for any query cache)
	                $checked_categories = array();
	                $keys = array_keys( $categories );
	
	                foreach( $keys as $k ) {
	                        if ( in_array( $categories[$k]->term_id, $args['selected_cats'] ) ) {
	                                $checked_categories[] = $categories[$k];
	                                unset( $categories[$k] );
	                        }
	                }
	
	                // Put checked cats on top
	                echo call_user_func_array(array(&$walker, 'walk'), array($checked_categories, 0, $args));
	        }
        // Then the rest of them
	        echo call_user_func_array(array(&$walker, 'walk'), array($categories, 0, $args));
	}
/** WP Core ends here **/