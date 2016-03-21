<?php
    global $upme_registration_params;
    extract($upme_registration_params);
?>

<div class="upme-head">
                 
    <div class="upme-left">
        <div class=" <?php echo $pic_class; ?> ">
            <?php echo $user_pic; ?>
        </div>

        <div class="upme-name">
            <div class="upme-field-name upme-field-name-wide">
                <?php echo $display_head_title; ?>
            </div>
        </div>
    </div>

    <div class="upme-right"></div>
    <div class="upme-clear"></div>                 
</div>