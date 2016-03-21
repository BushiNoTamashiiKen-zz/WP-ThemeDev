<?php
    global $upme_admin;
?>

<div class="upme-tab-content" id="upme-woocommerce-settings-content" style="display:none;">
    <h3><?php _e('Manage Woocommerce','upme');?>
        </h3>
        
        
    
    <div id="upme-woocommerce-settings" class="upme-woo-screens" style="display:block">

        <form id="upme-woocommerce-settings-form">
            <table class="form-table" cellspacing="0" cellpadding="0">
                <tbody>
                    <?php

                        $upme_admin->add_plugin_module_setting(
                            'checkbox',
                            'woocommerce_profile_tab_status',
                            'woocommerce_profile_tab_status',
                            __('Display Woocoommerce Tab in Profile', 'upme'),
                            '1',
                            __('If checked, Woocommerce my account details will be available in UPME profile as a separate tab.', 'upme'),
                            __('Checking this option will enable Woocommerce tab for logged-in user profiles.', 'upme')
                        );

                    ?>

                    <tr valign="top">
                        <th scope="row"><label>&nbsp;</label></th>
                        <td>
                            <?php 
                                echo UPME_Html::button('button', array('name'=>'save-upme-woocommerce-settings', 'id'=>'save-upme-woocommerce-settings', 'value'=> __('Save Changes','upme'), 'class'=>'button button-primary upme-save-module-options'));
                                echo '&nbsp;&nbsp;';
                                echo UPME_Html::button('button', array('name'=>'reset-upme-woocommerce-settings', 'id'=>'reset-upme-woocommerce-settings', 'value'=>__('Reset Options','upme'), 'class'=>'button button-secondary upme-reset-module-options'));
                            ?>
                            
                        </td>
                    </tr>

                </tbody>
            </table>
        
        </form>
        
    </div>     
</div>