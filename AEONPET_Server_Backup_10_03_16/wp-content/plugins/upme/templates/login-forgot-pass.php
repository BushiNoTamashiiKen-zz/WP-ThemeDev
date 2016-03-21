<?php
    global $upme_login_forgot_params;
    extract($upme_login_forgot_params);
?>
<div class="upme-forgot-pass" id="upme-forgot-pass-holder-<?php echo $login_code_count; ?>">
    <div class="upme-field upme-edit upme-edit-show">
        <label class="upme-field-type" for="user_name_email-<?php echo $login_code_count; ?>">
            <i class="upme-icon upme-icon-user"></i>
            <span><?php echo __('Username or Email', 'upme'); ?></span>
        </label>
        <div class="upme-field-value">
            <input type="text" class="upme-input" name="user_name_email" id="user_name_email-<?php echo $login_code_count; ?>" value=""></div>
    </div>

    <div class="upme-field upme-edit upme-edit-show">
        <label class="upme-field-type upme-blank-lable">&nbsp;</label>
        <div class="upme-field-value">
            <div class="upme-back-to-login">
            <a href="javascript:void(0);" title="<?php echo __('Back to Login', 'upme'); ?>" id="upme-back-to-login-<?php echo $login_code_count; ?>"><?php echo __('Back to Login', 'upme'); ?></a> <?php echo $register_link_forgot; ?>
            </div>

        <input type="button" name="upme-forgot-pass" id="upme-forgot-pass-btn-<?php echo $login_code_count; ?>" class="upme-button upme-login" value="<?php echo __('Forgot Password', 'upme'); ?>">
        </div>
    </div>
</div>