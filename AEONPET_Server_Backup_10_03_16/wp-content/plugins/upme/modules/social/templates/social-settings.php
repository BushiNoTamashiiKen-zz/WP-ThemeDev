<?php
    global $upme_admin;
?>

<div class="upme-tab-content" id="upme-social-login-settings-content" style="display:none;">
    <h3><?php _e('Manage Social Login','upme');?>
        </h3>
        
        
    
    <div id="upme-social-login-settings" class="upme-woo-screens" style="display:block">

        <form id="upme-social-login-settings-form">
            <table class="form-table" cellspacing="0" cellpadding="0">
                <tbody>
                    <?php

                        $upme_admin->add_plugin_setting(
                                'checkbox_list',
                                'social_login_allowed_networks',
                                __('Enabled Social Networks', 'upme'),
                                array(  'linkedin' => __('LinkedIn','upme'),
                                        'facebook' => __('Facebook','upme'),
                                        'twitter' => __('Twitter','upme'),
                                        'google' => __('Google Plus','upme')
                                     ),
                                __('Selected social network buttons will be available in login and registration forms to login using social networks.', 'upme'),
                                __('Please select only the necessary networks and fill in API details before using them.', 'upme'),
                                '',
                                array()
                        );

                        $upme_admin->add_plugin_setting(
                            'input',
                            'social_login_display_message',
                            __('Social Login Message', 'upme'), array(),
                            sprintf(__('Message displayed before the social login buttons.', 'upme')),
                            __('Message displayed before the social login buttons.', 'upme')
                        );

                        $upme_admin->add_plugin_setting(
                            'input',
                            'social_login_facebook_app_id',
                            __('Facebook App ID', 'upme'), array(),
                            sprintf(__('ID of your Facebook application.', 'upme')),
                            __('Get the ID of your Facebook application and use it here.', 'upme')
                        );

                        $upme_admin->add_plugin_setting(
                            'input',
                            'social_login_facebook_app_secret',
                            __('Facebook App Secret', 'upme'), array(),
                            sprintf(__('Secret key of your Facebook application.', 'upme')),
                            __('Get the app secret of your Facebook application and use it here.', 'upme')
                        );


                        $upme_admin->add_plugin_setting(
                            'input',
                            'social_login_google_client_id',
                            __('Google Client ID', 'upme'), array(),
                            sprintf(__('Client ID of your Google application.', 'upme')),
                            __('Get the Client ID of your Google application and use it here.', 'upme')
                        );

                        $upme_admin->add_plugin_setting(
                            'input',
                            'social_login_google_client_secret',
                            __('Google Client Secret', 'upme'), array(),
                            sprintf(__('Secret key of your Google application.', 'upme')),
                            __('Get the app secret of your Google application and use it here.', 'upme')
                        );


                        $upme_admin->add_plugin_setting(
                            'input',
                            'social_login_twitter_app_key',
                            __('Twitter App Key', 'upme'), array(),
                            sprintf(__('Application key of your Twitter application', 'upme')),
                            __('Get the key of your Twitter application and use it here.', 'upme')
                        );

                        $upme_admin->add_plugin_setting(
                            'input',
                            'social_login_twitter_app_secret',
                            __('Twitter App Secret', 'upme'), array(),
                            sprintf(__('Secret key of your Twitter application.', 'upme')),
                            __('Get the app secret of your Twitter application and use it here.', 'upme')
                        );


                        $upme_admin->add_plugin_setting(
                            'input',
                            'social_login_linkedin_app_key',
                            __('LinkedIn App Key', 'upme'), array(),
                            sprintf(__('Application key of your LinkedIn application.', 'upme')),
                            __('Get the key of your LinkedIn application and use it here.', 'upme')
                        );

                        $upme_admin->add_plugin_setting(
                            'input',
                            'social_login_linkedin_app_secret',
                            __('LinkedIn App Secret', 'upme'), array(),
                            sprintf(__('Secret key of your LinkedIn application.', 'upme')),
                            __('Get the app secret of your LinkedIn application and use it here.', 'upme')
                        );



                    ?>

                    <tr valign="top">
                        <th scope="row"><label>&nbsp;</label></th>
                        <td>
                            <?php 
                                echo UPME_Html::button('button', array('name'=>'save-upme-social-login-settings', 'id'=>'save-upme-social-login-settings', 'value'=> __('Save Changes','upme'), 'class'=>'button button-primary upme-save-module-options'));
                                echo '&nbsp;&nbsp;';
                                echo UPME_Html::button('button', array('name'=>'reset-upme-social-login-settings', 'id'=>'reset-upme-social-login-settings', 'value'=>__('Reset Options','upme'), 'class'=>'button button-secondary upme-reset-module-options'));
                            ?>
                            
                        </td>
                    </tr>

                </tbody>
            </table>
        
        </form>
        
    </div>     
</div>