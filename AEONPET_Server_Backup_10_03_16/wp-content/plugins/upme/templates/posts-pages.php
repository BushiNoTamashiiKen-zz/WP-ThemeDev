<?php
    global $upme_admin,$upme_options,$upme_roles;

    
    $user_roles_array = array_merge( array('all_roles' => __('All User Roles','upme')) , $upme_roles->upme_available_user_roles_view_profile() );
    
    $post_types_array = array(  'post'      => __('Posts','upme'),
                                'page'      => __('Pages','upme'),
                                'category'  => __('Categories','upme'),
                                'tags'      => __('Tags','upme'),
                            );

    $skipped_types = array('post','page','nav_menu_item','revision','attachment');
    $post_types = get_post_types( '', 'objects' ); 
    foreach ( $post_types as $k => $post_type ) {
        if(!in_array($k,$skipped_types)){
            $post_types_array[$k] = $post_type->labels->name;
        }
    }

    $settings = $upme_options->upme_settings;
    $favorite_default_featured_image = isset($settings['favorite_default_featured_image']) ? $settings['favorite_default_featured_image'] : '';
    $reader_default_featured_image = isset($settings['reader_default_featured_image']) ? $settings['reader_default_featured_image'] : '';
    $recommend_default_featured_image = isset($settings['recommend_default_featured_image']) ? $settings['recommend_default_featured_image'] : '';

    $recommend_mod_status = ( '0' == $settings['recommend_enabled_status'] ) ? '.upme-display-none' : '';
    $favorite_mod_status = ( '0' == $settings['favorite_enabled_status']) ? '.upme-display-none' : '';
    $reader_mod_status = ( '0' == $settings['reader_enabled_status']) ? '.upme-display-none' : '';
?>

<div class="upme-tab-content" id="upme-posts-pages-settings-content" style="display:none;">
    <h3><?php _e('Manage Posts and Pages Features','upme');?>
        </h3>
        
        
    
    <div id="upme-posts-pages-settings" class="upme-posts-pages-screens" style="display:block">

        <form id="upme-posts-pages-settings-form">
            <table class="form-table" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr><th colspan="2"><div class="upme-module-settings-sub-title"><?php echo __('Favorite Posts/Pages','upme'); ?></div></th></tr>
                    <?php

                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'favorite_enabled_status',
                                'favorite_enabled_status',
                                __('Favorite Module Status', 'upme'),
                                array('0' => __('Disabled','upme'), '1' => __('Enabled' , 'upme') ),
                                __('You can enable/disable the favorite/bookmarks module on posts.', 'upme'),
                                __('If you are not using this feature, mark in as disabled to improve performence.', 'upme'),
                                array('class'=> 'chosen-admin_setting','init_value' => '')
                        );
                            
                        
                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'favorite_enabled_post_types[]',
                                'favorite_enabled_post_types',
                                __('Enable Favorites On', 'upme'),
                                $post_types_array,
                                __('These post/page types will have a Favorites button for all users or specified user roles.', 'upme'),
                                __('Users will see the Favorites button based on this setting and Enable Favorites for setting.', 'upme'),
                                array('class'=> 'chosen-admin_setting '. $favorite_mod_status,'multiple'=>'','init_value' => '','style' => $favorite_mod_status)
                        );
                        
                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'favorite_enabled_user_roles[]',
                                'favorite_enabled_user_roles',
                                __('Enable Favorites for', 'upme'),
                                $user_roles_array,
                                __('These user roles will have a Favorites button on posts/pages.', 'upme'),
                                __('Users will see the Favorites button based on this setting and Enable Favorites On setting.', 'upme'),
                                array('class'=> 'chosen-admin_setting '.$favorite_mod_status,'multiple'=>'','init_value' => '', 'style' => $favorite_mod_status)
                        );

                    ?>
                    
                    <tr style="<?php echo $favorite_mod_status; ?>">
                        <th scope="row">
                            <label><?php _e('Default Featured Image for Favorites'); ?></label>
                        </th>
                        <td id="favorite-default-featured-image">
                            <?php if($favorite_default_featured_image != ''){ ?>
                                <img class='upme-admin-setting-img-prev' style='width:75px;height:75px' src='<?php echo $favorite_default_featured_image; ?>' />
                            <?php }  ?>
                            
                            <input type="hidden" name="favorite_default_featured_image" class="upme-admin-setting-upload-hidden" id="favorite_default_featured_image" value="<?php echo $favorite_default_featured_image; ?>" />    
                            <input type="button" name="favorite_default_featured_image_upload" id="favorite_default_featured_image_upload" class="button button-primary upme-admin-setting-upload-btn" value="<?php _e('Upload Image','upme'); ?>" />
                        </td>
                    </tr>
                    
                    
                    <tr><th colspan="2"><div class="upme-module-settings-sub-title"><?php echo __('Reading List','upme'); ?></div></th></tr>
                    
                    <?php

                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'reader_enabled_status',
                                'reader_enabled_status',
                                __('Reader Module Status', 'upme'),
                                array('0' => __('Disabled','upme'), '1' => __('Enabled' , 'upme') ),
                                __('You can enable/disable the post reader module on posts.', 'upme'),
                                __('If you are not using this feature, mark in as disabled to improve performence.', 'upme'),
                                array('class'=> 'chosen-admin_setting','init_value' => '')
                        );
                            
                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'reader_enabled_post_types[]',
                                'reader_enabled_post_types',
                                __('Enable Reader On', 'upme'),
                                $post_types_array,
                                __('These post/page types will have a Mark as Read/Unread button for all users or specified user roles.', 'upme'),
                                __('Users will see the Mark as Read/Unread button based on this setting and Enable Reader For setting.', 'upme'),
                                array('class'=> 'chosen-admin_setting','multiple'=>'','init_value' => '','style' => $reader_mod_status)
                        );
                        

                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'reader_enabled_user_roles[]',
                                'reader_enabled_user_roles',
                                __('Enable Reader for', 'upme'),
                                $user_roles_array,
                                __('These user roles will have a Mark as Read/Unread button on posts/pages.', 'upme'),
                                __('Users will see the Mark as Read/Unread button based on this setting and Enable Reader On setting.', 'upme'),
                                array('class'=> 'chosen-admin_setting','multiple'=>'','init_value' => '','style' => $reader_mod_status)
                        );
                    ?>
                    
                    <tr style="<?php echo $favorite_mod_status; ?>">
                        <th scope="row">
                            <label><?php _e('Default Featured Image for Reader'); ?></label>
                        </th>
                        <td id="reader-default-featured-image">
                            <?php if($reader_default_featured_image != ''){ ?>
                                <img class='upme-admin-setting-img-prev' style='width:75px;height:75px' src='<?php echo $reader_default_featured_image; ?>' />
                            <?php }  ?>
                            
                            <input type="hidden" name="reader_default_featured_image" class="upme-admin-setting-upload-hidden" id="reader_default_featured_image" value="<?php echo $reader_default_featured_image; ?>" />    
                            <input type="button" name="reader_default_featured_image_upload" id="reader_default_featured_image_upload" class="button button-primary upme-admin-setting-upload-btn" value="<?php _e('Upload Image','upme'); ?>" />
                        </td>
                    </tr>
                    
                    
                    <tr><th colspan="2"><div class="upme-module-settings-sub-title"><?php echo __('Recommended Posts/Pages','upme'); ?></div></th></tr>
                    <?php

                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'recommend_enabled_status',
                                'recommend_enabled_status',
                                __('Recommendation Module Status', 'upme'),
                                array('0' => __('Disabled','upme'), '1' => __('Enabled' , 'upme') ),
                                __('You can enable/disable the recommendation module on posts.', 'upme'),
                                __('If you are not using this feature, mark in as disabled to improve performence.', 'upme'),
                                array('class'=> 'chosen-admin_setting','init_value' => '')
                        );
                            
                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'recommend_enabled_post_types[]',
                                'recommend_enabled_post_types',
                                __('Enable Recommend On', 'upme'),
                                $post_types_array,
                                __('These post/page types will have a Recommend button for all users or specified user roles.', 'upme'),
                                __('Users will see the Recommend button based on this setting and Enable Recommend For setting.', 'upme'),
                                array('class'=> 'chosen-admin_setting','multiple'=>'','init_value' => '','style' => $recommend_mod_status)
                        );
                        

                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'recommend_enabled_user_roles[]',
                                'recommend_enabled_user_roles',
                                __('Enable Recommend for', 'upme'),
                                $user_roles_array,
                                __('These user roles will have a Recommend button on posts/pages.', 'upme'),
                                __('Users will see the Recommend button based on this setting and Enable Recommend For setting.', 'upme'),
                                array('class'=> 'chosen-admin_setting','multiple'=>'','init_value' => '','style' => $recommend_mod_status)
                        );
                    ?>
                    
                    <tr style="<?php echo $favorite_mod_status; ?>">
                        <th scope="row">
                            <label><?php _e('Default Featured Image for Recommended Posts'); ?></label>
                        </th>
                        <td id="recommend-default-featured-image">
                            <?php if($recommend_default_featured_image != ''){ ?>
                                <img class='upme-admin-setting-img-prev' style='width:75px;height:75px' src='<?php echo $recommend_default_featured_image; ?>' />
                            <?php }  ?>
                            
                            <input type="hidden" name="recommend_default_featured_image" class="upme-admin-setting-upload-hidden" id="recommend_default_featured_image" value="<?php echo $recommend_default_featured_image; ?>" />    
                            <input type="button" name="recommend_default_featured_image_upload" id="recommend_default_featured_image_upload" class="button button-primary upme-admin-setting-upload-btn" value="<?php _e('Upload Image','upme'); ?>" />
                        </td>
                    </tr>
                    
                    <tr><th colspan="2"><div class="upme-module-settings-sub-title"><?php echo __('Common Settings','upme'); ?></div></th></tr>
                    
                    <?php

                        $upme_admin->add_plugin_setting(
                                        'textarea',
                                        'content_before_post_buttons',
                                        __('Content Before Post Buttons', 'upme'),
                                        null,
                                        __('Show a text/HTML message before the post features buttons panel.', 'upme'),
                                        __('This message will be empty by default.', 'upme')
                                );

                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'post_button_panel_status',
                                'post_button_panel_status',
                                __('Enable Post/Page Feature Buttons', 'upme'),
                                array( '0' => __('After Content','upme'), '1' => __('Before Content','upme') , '2' => __('Before and After Content','upme') ),
                                __('Post/Pages features buttons panel will be displayed in the specified location.', 'upme'),
                                __('By default, buttons panel will be displayed after the content.', 'upme'),
                                array('class'=> 'chosen-admin_setting')
                        );
                            
                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'featured_image_enabled_types[]',
                                'featured_image_enabled_types',
                                __('Featured Image is Enabled For', 'upme'),
                                array('favorite' => __('Favorites','upme'),'reader' => __('Reader','upme'),'recommend' => __('Recommend','upme')),
                                __('These types will have a featured image displayed in the post list.', 'upme'),
                                __('Default image will be displayed when featured image is not available or not supported.', 'upme'),
                                array('class'=> 'chosen-admin_setting','multiple'=>'','init_value' => '')
                        );
                        


                    ?>
                    
                    

                    <tr valign="top">
                        <th scope="row"><label>&nbsp;</label></th>
                        <td>
                            <?php 
                                echo UPME_Html::button('button', array('name'=>'save-upme-posts-pages-settings', 'id'=>'save-upme-posts-pages-settings', 'value'=> __('Save Changes','upme'), 'class'=>'button button-primary upme-save-module-options'));
                                echo '&nbsp;&nbsp;';
                                echo UPME_Html::button('button', array('name'=>'reset-upme-posts-pages-settings', 'id'=>'reset-upme-posts-pages-settings', 'value'=>__('Reset Options','upme'), 'class'=>'button button-secondary upme-reset-module-options'));
                            ?>
                            
                        </td>
                    </tr>

                </tbody>
            </table>
        
        </form>
        
    </div>     
</div>