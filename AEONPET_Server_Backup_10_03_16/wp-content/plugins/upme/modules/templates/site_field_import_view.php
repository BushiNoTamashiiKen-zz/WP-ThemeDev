<?php
    global $upme_admin;

?>

    <form id="upme-upload-import-fields-form" name="upme-upload-import-fields-form" method="post" enctype="multipart/form-data"  action="upload.php">

    <table class="form-table" cellspacing="0" cellpadding="0">
        <tbody>

        <tr valign="top">
            <th scope="row"><label for="Import Type"><?php _e('Import Type', 'upme'); ?></label></th>
            <td>
                <?php
                $import_types = array(
                    'all_fields'  => __('All Fields', 'upme'),
                    'selected_fields'   => __('Selected Fields', 'upme')
                );

                echo UPME_Html::drop_down(array('name'=>'site_import_field_type','id'=>'site_import_field_type', 'class' =>'chosen-admin_setting'), $import_types, '');

                ?><i class="upme-icon-question-sign upme-tooltip2 option-help" original-title="<?php _e('Select import type.', 'upme') ?>"></i>
            </td>
        </tr>

        <tr valign="top">
    <th scope="row"><label for="Fields"><?php _e('Fields', 'upme'); ?></label></th>
    <td>
        <?php
        $profile_fields = $upme_admin->upme_get_custom_field();
        echo UPME_Html::drop_down(array('name'=>'site_import_fields[]','id'=>'site_import_fields','class'=> 'chosen-admin_setting','multiple'=>''), $profile_fields, '');

        ?><i class="upme-icon-question-sign upme-tooltip2 option-help" original-title="<?php _e('Select pages to be restricted.', 'upme') ?>"></i>
    </td>
</tr>

<tr valign="top">
    <th scope="row"><label for="File"><?php _e('File', 'upme'); ?></label></th>
    <td>
        <input type="file" name="fields_file" id="fields_file" multiple />

    </td>
</tr>

<tr valign="top">
    <th scope="row"><label>&nbsp;</label></th>
    <td>
        <?php
        echo UPME_Html::button('submit', array('name' => 'upme-upload-import-fields', 'id' => 'upme-upload-import-fields', 'value' => __('Upload','upme'), 'class' => 'button button-primary'));
        ?>
    </td>
</tr>

<tr>
    <div id="response"></div>
    <ul id="image-list">
</tr>
        </tbody>
    </table>
</form>


<div id="errfrmMsg"></div>
