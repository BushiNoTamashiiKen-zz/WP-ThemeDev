<?php
global $upme_template_loader;  

$current_user = wp_get_current_user();

wc_print_notices(); ?>

<div id="upme-woo-account" class="woocommerce">
    
    <div id="upme-woo-account-navigation" >
        <div class="upme-woo-account-navigation-item" data-nav-ietm-id="upme-woo-account-info" >
            <?php echo __('Account Info','upme'); ?>
        </div>
        <div class="upme-woo-account-navigation-item" data-nav-ietm-id="upme-woo-my-reviews" >
            <?php echo __('My Reviews','upme'); ?>
        </div>
        <div class="upme-woo-account-navigation-item" data-nav-ietm-id="upme-woo-my-downloads" >
            <?php echo __('My Downloads','upme'); ?>
        </div>
        <div class="upme-woo-account-navigation-item" data-nav-ietm-id="upme-woo-my-orders" >
            <?php echo __('My Orders','upme'); ?>
        </div>
        
        <div class="upme-woo-clear"></div>
    </div>
    
    <?php do_action( 'woocommerce_before_my_account' ); ?>
    
    <div id="upme-woo-account-info" class="myaccount_user upme-woo-account-navigation-content">
        <?php
        printf(
            __( 'Hello <strong>%1$s</strong> (not %1$s? <a href="%2$s">Sign out</a>).', 'woocommerce' ) . ' ',
            $current_user->display_name,
            wp_logout_url( get_permalink( wc_get_page_id( 'myaccount' ) ) )
        );

        printf( __( 'From your account dashboard you can view your recent orders, manage your shipping and billing addresses and edit your password and account details.', 'upme' ));
        ?>
        
        
        <?php $upme_template_loader->get_template_part('my-address'); ?>
    </div>

    <div id="upme-woo-my-downloads" class="upme-woo-account-navigation-content" style="display:none" >
        <?php $upme_template_loader->get_template_part('my-downloads');  ?>
    </div>

    <div id="upme-woo-my-reviews" class="upme-woo-account-navigation-content" style="display:none" >
        <?php $upme_template_loader->get_template_part('my-reviews');  ?>
    </div>

    <div id="upme-woo-my-orders" class="upme-woo-account-navigation-content" style="display:none"  >
        <?php $upme_template_loader->get_template_part('my-orders');  ?>
    </div>

    <?php do_action( 'woocommerce_after_my_account' ); ?>
    
</div>
