<?php
    global $upme_admin,$upme_options;

    $profile_fields = $upme_options->upme_profile_fields;
    $profile_meta_fields = array('0'=> __('Select Field','upme'));
    foreach($profile_fields as $profile_field){
        if(isset($profile_field['type']) && $profile_field['type'] == 'usermeta'){
            $profile_meta_fields[$profile_field['meta']] = $profile_field['name'];
        }
    }

    
?>

<div class="upme-tab-content" id="upme-seo-settings-content" style="display:none;">
    <h3><?php _e('Manage Profile SEO Settings','upme');?>
        </h3>
        
        
    
    <div id="upme-seo-settings" class="upme-seo-screens" style="display:block">

        <form id="upme-seo-settings-form">
            <table class="form-table" cellspacing="0" cellpadding="0">
                <tbody>
                    <?php
                        $upme_admin->add_plugin_setting(
                                            'input',
                                            'seo_profile_title_prefix',
                                            __('Prefix for Profile Page Title Bar', 'upme'), array(),
                                            __('Provide prefix to be included in title bar of the profile page and meta tags', 'upme'),
                                            __('This will be used to specify a SEO optimized page title.', 'upme')
                                    );
    
                        $upme_admin->add_plugin_setting(
                                            'input',
                                            'seo_profile_title_suffix',
                                            __('Suffix for Profile Page Title Bar', 'upme'), array(),
                                            __('Provide suffix to be included in title bar of the profile page and meta tags', 'upme'),
                                            __('This will be used to specify a SEO optimized page title.', 'upme')
                                    );



                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'seo_profile_title_field',
                                'seo_profile_title_field',
                                __('Field for Title Meta Tag', 'upme'),
                                $profile_meta_fields,
                                __('Value of this profile field will be used for the title meta tag. This will be placed bewtween the prefix and suffix specified above.', 'upme'),
                                __('Display name or first name is the ideal field for this setting.', 'upme'),
                                array('class'=> 'chosen-admin_setting')
                        );

                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'seo_profile_description_field',
                                'seo_profile_description_field',
                                __('Field for Description Meta Tag', 'upme'),
                                $profile_meta_fields,
                                __('Value of this profile field will be used for the description meta tag.', 'upme'),
                                __('Description/About field us the ideal field for this setting.', 'upme'),
                                array('class'=> 'chosen-admin_setting')
                        );

                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'seo_profile_image_field',
                                'seo_profile_image_field',
                                __('Field for Image Meta Tag', 'upme'),
                                $profile_meta_fields,
                                __('Value of this profile field will be used for the image meta tag.', 'upme'),
                                __('Profile picture is the ideal field for this setting.', 'upme'),
                                array('class'=> 'chosen-admin_setting')
                        );

                        
                    ?>

                    <tr valign="top">
                        <th scope="row"><label>&nbsp;</label></th>
                        <td>
                            <?php 
                                echo UPME_Html::button('button', array('name'=>'save-upme-seo-settings', 'id'=>'save-upme-seo-settings', 'value'=> __('Save Changes','upme'), 'class'=>'button button-primary upme-save-module-options'));
                                echo '&nbsp;&nbsp;';
                                echo UPME_Html::button('button', array('name'=>'reset-upme-seo-settings', 'id'=>'reset-upme-seo-settings', 'value'=>__('Reset Options','upme'), 'class'=>'button button-secondary upme-reset-module-options'));
                            ?>
                            
                        </td>
                    </tr>

                </tbody>
            </table>
        
        </form>
        
    </div>     
</div>