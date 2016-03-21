<?php
    global $upme_login_params;
    extract($upme_login_params);
?>

<form action="" method="post" id="upme-login-form-<?php echo $login_code_count; ?>">

    
    <!-- Display login fields inside the form -->
    <?php echo $login_fields; ?>


    <!-- Display captcha verification fields after the login fields -->
    <?php echo $captcha_fields; ?>

    <div class="upme-field upme-edit upme-edit-show">
        <label class="upme-field-type upme-field-type-<?php echo $sidebar_class; ?>">&nbsp;</label>
        <div class="upme-field-value">

            <!-- UPME Filters for adding extra fields or hidden data in forgot form -->
            <?php echo apply_filters('upme_forgot_form_extra_fields',''); ?>
            <!-- End Filter -->

            <input type="hidden" name="upme-hidden-login-form-name" value="<?php echo $login_form_name; ?>" />
            <input type="hidden" name="upme-hidden-login-form-name-hash" value="<?php echo $hash; ?>" />

            <div class="upme-rememberme <?php echo $remember_me_class; ?>">
                <i class="<?php echo $class; ?>"></i> <?php echo  __('Remember me', 'upme'); ?>
                <input type="hidden" name="rememberme" id="rememberme-<?php echo  $login_code_count; ?>" value="0" />
            </div>
            <input type="submit" name="upme-login" class="upme-button upme-login <?php echo $login_btn_class; ?>" value="<?php echo  __('Log In', 'upme'); ?>" /><br />

            <?php echo $login_form_link; ?>


        </div>
    </div>
    <div class="upme-clear"></div>

    <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />

    <!-- UPME Filters for social login buttons section -->
    <?php echo apply_filters( 'upme_social_logins', ''); ?>
    <!-- End Filters -->

</form>       

        
<!-- UPME Filters for adding extra fields or hidden data in login form -->
<?php echo  apply_filters('upme_login_form_extra_fields',''); ?>
<!-- End Filter -->

<!-- Generating Forgot Password Form-->
<?php echo $forgot_pass_html; ?>