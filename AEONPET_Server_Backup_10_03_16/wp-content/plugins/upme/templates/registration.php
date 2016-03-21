<?php
    global $upme_registration_params;
    extract($upme_registration_params);
?>


<?php if('1' == $users_can_register){ ?>


<div id="upme-registration" class="upme-wrap upme-registration <?php echo $sidebar_class; ?> ">
    <div class="upme-inner upme-registration-wrapper upme-clearfix">

        <?php if($display_errors_status){ ?>

            <!-- UPME Filters for before registration head section -->
            <?php echo apply_filters( 'upme_register_before_head', ''); ?>
            <!-- End Filters -->

            <!-- UPME Filters for customizing head section -->
            <?php 
                $registration_head_params = array();
                echo apply_filters( 'upme_registration_head', $display_head , $registration_head_params);
            ?>
            <!-- End Filters -->

        <?php } ?>

        <!-- UPME Filters for after registration head section -->
        <?php echo apply_filters( 'upme_register_after_head', ''); ?>
        <!-- End Filters -->

        <div class="upme-main">
            <div class="upme-errors" style="display:none;" id="pass_err_holder">
                <span class="upme-error upme-error-block" id="pass_err_block">
                    <i class="upme-icon upme-icon-remove"></i>
                    <?php echo __('Please enter a username.','upme'); ?>
                </span>
            </div>

            <?php echo  $display_reg_post_errors; ?>
            <?php echo  $register_form; ?>

        </div>

        <!-- UPME Filters for after registration fields section -->
        <?php echo apply_filters( 'upme_register_after_fields', ''); ?>
        <!-- End Filters -->

    </div>
</div>

<?php  } else { ?>

<div id="upme-registration" class="upme-wrap upme-registration <?php echo $sidebar_class; ?> ">
    <div class="upme-inner upme-clearfix">
        <div class="upme-head">
            <?php echo  $registration_blocked_message; ?>
        </div>
    </div>
</div>

<?php  } ?>