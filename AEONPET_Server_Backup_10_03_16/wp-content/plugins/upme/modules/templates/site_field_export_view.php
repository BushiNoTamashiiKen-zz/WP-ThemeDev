<?php
global $upme_admin;

?>

<form id="upme-download-export-fields-form">
    <table class="form-table" cellspacing="0" cellpadding="0">
        <tbody>
        <tr valign="top">
            <th scope="row"><label for="Export Type"><?php _e('Export Type', 'upme'); ?></label></th>
            <td>
                <?php
                $export_types = array(
                    'all_fields' => __('All Fields', 'upme'),
                    'selected_fields' => __('Selected Fields', 'upme')
                );

                echo UPME_Html::drop_down(array('name' => 'site_export_field_type', 'id' => 'site_export_field_type', 'class' => 'chosen-admin_setting'), $export_types, '');

                ?><i class="upme-icon-question-sign upme-tooltip2 option-help"
                     original-title="<?php _e('Select Export Type', 'upme') ?>"></i>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="Fields"><?php _e('Fields', 'upme'); ?></label></th>
            <td>
                <?php
                $profile_fields = $upme_admin->upme_get_custom_field();
                echo UPME_Html::drop_down(array('name' => 'site_export_fields[]', 'id' => 'site_export_fields', 'class' => 'chosen-admin_setting', 'multiple' => ''), $profile_fields, '');

                ?><i class="upme-icon-question-sign upme-tooltip2 option-help"
                     original-title="<?php _e('Select pages to be exported.', 'upme') ?>"></i>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label>&nbsp;</label></th>
            <td>
                <?php
                echo UPME_Html::button('button', array('name' => 'upme-download-export-fields', 'id' => 'upme-download-export-fields', 'value' => __('Download','upme'), 'class' => 'button button-primary'));
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</form>