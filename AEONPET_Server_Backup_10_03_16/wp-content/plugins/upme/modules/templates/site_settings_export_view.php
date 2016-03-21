<?php
global $upme_admin;

// $options = get_option('upme_options');
// echo "<pre>";print_r($options);exit;

?>

<form id="upme-download-export-settings-form">
    <table class="form-table" cellspacing="0" cellpadding="0">
        <tbody>
        <tr valign="top">
            <th scope="row"><label for="Export Type"><?php _e('Export Type', 'upme'); ?></label></th>
            <td>
                <?php
                $export_types = array(
                    'all_settings' => __('All Settings', 'upme'),
                    //'selected_settings' => __('Selected Settings', 'upme')
                );
 
                echo UPME_Html::drop_down(array('name' => 'site_export_settings_type', 'id' => 'site_export_settings_type', 'class' => 'chosen-admin_setting'), $export_types, '');

                ?><i class="upme-icon-question-sign upme-tooltip2 option-help"
                     original-title="<?php _e('Select Export Type', 'upme') ?>"></i>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="Settings Section"><?php _e('Settings Section', 'upme'); ?></label></th>
            <td>
                <?php
                $settings_sections = array();
                echo UPME_Html::drop_down(array('name' => 'site_export_settings_sections[]', 'id' => 'site_export_settings_sections', 'class' => 'chosen-admin_setting', 'multiple' => ''), $settings_sections, '');

                ?><i class="upme-icon-question-sign upme-tooltip2 option-help"
                     original-title="<?php _e('Select settings sections to be exported.', 'upme') ?>"></i>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label>&nbsp;</label></th>
            <td>
                <?php
                echo UPME_Html::button('button', array('name' => 'upme-download-export-settings', 'id' => 'upme-download-export-settings', 'value' => __('Download','upme'), 'class' => 'button button-primary'));
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</form>