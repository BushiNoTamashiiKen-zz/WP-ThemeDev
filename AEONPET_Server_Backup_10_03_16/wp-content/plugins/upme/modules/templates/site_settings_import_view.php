<?php
    global $upme_admin;

?>

    <form id="upme-upload-import-settings-form" name="upme-upload-import-settings-form" method="post" enctype="multipart/form-data"  action="upload.php">

    <table class="form-table" cellspacing="0" cellpadding="0">
        <tbody>

        <tr valign="top">
            <th scope="row"><label for="Import Type"><?php _e('Import Type', 'upme'); ?></label></th>
            <td>
                <?php
                $import_types = array(
                    'all_settings'  => __('All Settings', 'upme'),
                    //'selected_settings'   => __('Selected Settings', 'upme')
                );

                echo UPME_Html::drop_down(array('name'=>'site_import_settings_type','id'=>'site_import_settings_type', 'class' =>'chosen-admin_setting'), $import_types, '');

                ?><i class="upme-icon-question-sign upme-tooltip2 option-help" original-title="<?php _e('Select import type.', 'upme') ?>"></i>
            </td>
        </tr>

        <tr valign="top">
    <th scope="row"><label for="Settings Section"><?php _e('Settings Section', 'upme'); ?></label></th>
    <td>
        <?php
        $settings_sections = array();
        echo UPME_Html::drop_down(array('name'=>'site_import_settings_sections[]','id'=>'site_import_settings_sections','class'=> 'chosen-admin_setting','multiple'=>''), $settings_sections, '');

        ?><i class="upme-icon-question-sign upme-tooltip2 option-help" original-title="<?php _e('Select settings sections to be restricted.', 'upme') ?>"></i>
    </td>
</tr>

<tr valign="top">
    <th scope="row"><label for="File"><?php _e('File', 'upme'); ?></label></th>
    <td>
        <input type="file" name="settings_file" id="settings_file" multiple />

    </td>
</tr>

<tr valign="top">
    <th scope="row"><label>&nbsp;</label></th>
    <td>
        <?php
        echo UPME_Html::button('submit', array('name' => 'upme-upload-import-settings', 'id' => 'upme-upload-import-settings', 'value' => __('Upload','upme'), 'class' => 'button button-primary'));
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
