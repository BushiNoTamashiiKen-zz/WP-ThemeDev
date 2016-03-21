<?php
    global $upme_login_params;
    extract($upme_login_params);
?>

<div class="upme-wrap upme-login  <?php echo $sidebar_class; ?>">
    <div class="upme-inner upme-clearfix upme-login-wrapper">

        <!-- UPME Filters for before login head section -->
        <?php echo apply_filters( 'upme_login_before_head', ''); ?>
        <!-- End Filters -->

        <!-- UPME Filters for customizing head section -->
        <?php 
            $login_head_params = array();
            echo apply_filters( 'upme_login_head', $login_header , $login_head_params);
        ?>
        <!-- End Filters -->

    
        <!-- UPME Filters for after login head section -->
        <?php echo apply_filters( 'upme_login_after_head', ''); ?>
        <!-- End Filters -->


        <div class="upme-main">
            <?php echo $errors; ?>

            <?php if (!empty($act_status_message['msg'])) {    ?>
                <div class="upme-<?php echo $act_status_message['status']; ?>">';
                    <span class="upme-error upme-error-block">
                        <i class="upme-icon upme-icon-remove"></i>
                        <?php echo $act_status_message['msg']; ?>
                    </span>
                </div>
            <?php } ?>


            <?php echo $login_form_template; ?>

        </div>

        <!-- UPME Filters for after login fields section -->
        <?php echo apply_filters( 'upme_login_after_fields', ''); ?>
        <!-- End Filters -->

    </div>
</div>