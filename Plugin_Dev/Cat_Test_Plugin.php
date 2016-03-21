<?php 
/*
Plugin Name: WPSE Crowded Cats
Plugin URI: http://wordpress.stackexchange.com/questions/43419/how-do-i-create-a-way-for-users-to-assign-categories-to-a-post-from-the-frontend
Description: Allow visitors to change categories of posts. Ready to use with custom taxonomies and post types. 
Version: 0.1
Author: WPSE
Author URI: http://wordpress.stackexchange.com/users/2110/maugly
License: GPL2
*/


add_action('plugins_loaded','wpse_init_crowd_cats_class');
function wpse_init_crowd_cats_class(){
    new WPSECrowdCatsClass();
}


class WPSECrowdCatsClass { 

    function __construct() {

        // APPEND THE FORM AUTOMATICALLY TO EVERY POST
        add_filter( 'the_content', array( $this,'append_form' ) );

        // TEMPLATE ACTION TAG TO BE USED IN THEME
        // Usage: do_action('wpse_crowd_cats_form');
        // Usage: do_action('wpse_crowd_cats_form', $post_id, $taxonomy );
        add_action( 'wpse_crowd_cats_form', array( $this,'wpse_crowd_cats_form' ), 10, 2 );

        // FORM PROCESSING
        add_action( 'template_redirect', array( $this,'process_request' ) );

    }

    function process_request(){

        // check submission
        if ( ! isset($_POST['crowd-cat-radio']) || ! is_array($_POST['crowd-cat-radio']) )
            return;

        //TODO: check nonce

        // sanitize and check the input
        $suggested_terms = array_map( 'absint', $_POST['crowd-cat-radio'] );
        $post_id = absint( $_POST['crowd-cats-pid'] );
        $tax = $_POST['crowd-cats-tax'];
        if ( ! taxonomy_exists($tax) )
            return;

        // Allow only existing terms. Not sure if this is needed.
        $args = array( 'hide_empty' => false );
        $args = apply_filters( 'mcc_allowed_terms_args', $args, $post_id, $tax );
        $args['fields'] = 'ids';
        $allowed_terms = get_terms( $tax, $args );
        foreach ( $suggested_terms as $key => $term_id )
            if ( ! in_array( $term_id, $allowed_terms ) )
                unset( $suggested_terms[$key] );

        // Add terms to taxonomy
        $affected_terms = wp_set_object_terms( $post_id, $suggested_terms, $tax, false );
        update_term_cache($affected_terms);
        return $affected_terms;

    }


    function get_form( $post_id=null, $tax='category' ) {

        if ( is_null($post_id) || ! taxonomy_exists($tax) )
            return false;

        $args = array( 'hide_empty' => false );
        $args = apply_filters( 'mcc_get_terms_args', $args, $post_id, $tax );
        $all_terms = get_terms( $tax, $args );

        if ( ! $all_terms )
            return false;

        $post_terms = wp_get_object_terms( $post_id, $tax, array( 'fields' => 'ids' ) );

        $permalink = get_permalink( $post_id );

        $out = "<form id='crowd-cats' action='$permalink' method='POST' >
            <ul >";

        foreach ( $all_terms as $t ) :

            $checked = in_array( $t->term_id, $post_terms) ? 'checked' : '';
            $out .= "<li>
                        <input type='checkbox' id='crowd-cat-$t->term_id' name='crowd-cat-radio[]' value='$t->term_id' $checked /> 
                        <label for='crowd-cat-$t->term_id' >".esc_attr($t->name)."</label>
                     </li>";

        endforeach;

        $out .= "</ul>
                <input type='submit' value='Submit' name='crowd-cats-submit'/>
                <input type='hidden' value='".esc_attr($tax)."' name='crowd-cats-tax'/>
                <input type='hidden' value='$post_id' name='crowd-cats-pid'/>";

        //TODO: set nonce

        $out .= "</form>";

        return $out;

    }



    function append_form($content){

        global $post;

        if ( 'post' != $post->post_type )
            return $content;

        $form = $this->get_form( $post->ID );

        if ( ! $form )
            return $content;

        return "$content \n $form";
    }


    function wpse_crowd_cats_form( $post_id=null, $taxonomy='category' ) {

        if ( is_null($post_id) ) {
            global $post;
            $post_id = $post->ID;
        }

        echo $this->get_form( $post_id, $taxonomy );
    }


} // end of class               
?>