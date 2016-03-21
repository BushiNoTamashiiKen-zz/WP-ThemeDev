<?php
    global $upme_admin;
?>

<div class="upme-tab-content" id="upme-custom-fields-settings-content" style="display:none;">
    <h3><?php _e('Manage Custom Field Settings','upme');?>
        </h3>
        
        
    
    <div id="upme-custom-fields-settings" class="upme-custom-fields-screens" style="display:block">

        <form id="upme-custom-fields-settings-form">
            <table class="form-table" cellspacing="0" cellpadding="0">
                <tbody>
                    <?php
                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'help_text_html',
                                'help_text_html',
                                __('HTML for Help Text', 'upme'),
                                array('0'=> __('HTML Disabled','upme'),'1' => __('HTML Enabled','upme'),'2' => __('HTML Selected Tags Enabled','upme')),
                                __('Enabele/Disable HTML content on help text. By default HTML content is not enabled for help text.', 'upme'),
                                __('HTML Enabled - Enables any type of HTML content. HTML Selected Tags Enabled - only enabled p,a,strong,b,i,span tags. Other tags are blocked. Its recommended to disable or use selected tags to prevent styling conflicts.', 'upme'),
                                array('class'=> 'chosen-admin_setting')
                        );

                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'profile_collapsible_tabs',
                                'profile_collapsible_tabs',
                                __('Collapsible Tabs in Profiles', 'upme'),
                                array('0'=> __('Enable Collapsible Tabs','upme'),'1' => __('Disable Collapsible Tabs','upme')),
                                __('Enabele/Disable collapsible tabs by spearator fields. By default collapsible tabs is not enabled.', 'upme'),
                                __('Used to display/hide profile fields based on separator fields.', 'upme'),
                                array('class'=> 'chosen-admin_setting')
                        );

                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'profile_collapsible_tabs_display',
                                'profile_collapsible_tabs_display',
                                __('Display Fields in Collapsible Tabs', 'upme'),
                                array('0'=> __('Show by Default ','upme'),'1' => __('Hide by Default','upme')),
                                __('Display/hide fields in collapsible types on profile loading. By default, all fields in collapsible tabs are displayed', 'upme'),
                                __('Display/hide fields in collapsible types on profile loading. By default, all fields in collapsible tabs are displayed.', 'upme'),
                                array('class'=> 'chosen-admin_setting')
                        );

                        
                    ?>

                    <tr valign="top">
                        <th scope="row"><label>&nbsp;</label></th>
                        <td>
                            <?php 
                                echo UPME_Html::button('button', array('name'=>'save-upme-custom-fields-settings', 'id'=>'save-upme-custom-fields-settings', 'value'=> __('Save Changes','upme'), 'class'=>'button button-primary upme-save-module-options'));
                                echo '&nbsp;&nbsp;';
                                echo UPME_Html::button('button', array('name'=>'reset-upme-custom-fields-settings', 'id'=>'reset-upme-custom-fields-settings', 'value'=>__('Reset Options','upme'), 'class'=>'button button-secondary upme-reset-module-options'));
                            ?>
                            
                        </td>
                    </tr>

                </tbody>
            </table>
        
        </form>
        
    </div>     
</div>