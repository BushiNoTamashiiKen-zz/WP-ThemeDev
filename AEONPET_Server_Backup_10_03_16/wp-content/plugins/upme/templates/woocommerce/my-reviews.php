<?php

global $upme_template_loader,$upme_woo_review_params;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


$args = array(
    'user_id'           => get_current_user_id(),
    'post_status'       => 'publish',
	'post_type'         => 'product',
	'status'            => 'approve',
    
//    'post_type'   => wc_get_order_types( 'view-orders' ),
//	'post_status' => array_keys( wc_get_order_statuses() )
);

// The Query
$comments_query = new WP_Comment_Query;
$customer_reviews = $comments_query->query( $args );

if ( $customer_reviews ){ ?>

<h2><?php echo apply_filters( 'woocommerce_my_account_my_reviews_title', __( 'My Product Reviews', 'woocommerce' ) ); ?></h2>

<ol class="upme-woo-reviews">
    <?php
        foreach ( $customer_reviews as $customer_review ) {
            $upme_woo_review_params = $customer_review;
            $upme_template_loader->get_template_part('my-review');
        }
    ?>
</ol>

<?php } else { ?>

<h2><?php echo apply_filters( 'woocommerce_my_account_my_reviews_title', __( 'My Product Reviews', 'woocommerce' ) ); ?></h2>
<p><?php echo __('No reviews found.','upme'); ?></p>

<?php }  ?>