<?php
global $upme_admin;

// $options = get_option('upme_options');
// echo "<pre>";print_r($options);exit;

?>

<form id="upme-download-export-users-form">
    <table class="form-table" cellspacing="0" cellpadding="0">
        <tbody>
        <tr valign="top">
            <th scope="row"><label for="<?php _e('Export Users','upme'); ?>"><?php _e('Export Users', 'upme'); ?></label></th>
            <td>
                <?php
                $export_types = array(
                    'all_users' => __('All Users', 'upme'),
                    //'selected_settings' => __('Selected Settings', 'upme')
                );

                $users_query = new WP_User_Query( array( 
                    'orderby' => 'registered',
                    'order'   => 'desc'
                ) );
                $result_users = $users_query->get_results();
                foreach($result_users as $user){
                    $name = trim(get_user_meta($user->ID,'first_name',true). ' ' . get_user_meta($user->ID,'last_name',true));
                    $name = ($name == '') ? $user->display_name : $name;
                    $id = $user->ID;
                    $export_types[$id] = $name;
                }
 
                echo UPME_Html::drop_down(array('name' => 'site_export_users_type', 'id' => 'site_export_users_type', 'class' => 'chosen-admin_setting'), $export_types, '');

                ?><i class="upme-icon-question-sign upme-tooltip2 option-help"
                     original-title="<?php _e('Select Export Type', 'upme') ?>"></i>
            </td>
        </tr>

        

        <tr valign="top">
            <th scope="row"><label>&nbsp;</label></th>
            <td>
                <?php
                echo UPME_Html::button('button', array('name' => 'upme-download-export-users', 'id' => 'upme-download-export-users', 'value' => __('Download','upme'), 'class' => 'button button-primary'));
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</form>