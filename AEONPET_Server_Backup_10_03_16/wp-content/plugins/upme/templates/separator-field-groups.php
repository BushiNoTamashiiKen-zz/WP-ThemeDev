<?php
    global $upme_admin;
    $upme_profile_fields = get_option('upme_profile_fields');

    $separator_group_fields = get_option('upme_separator_group_fields');

    $separator_fields = array();
    $profile_fields = array();
    if(is_array($upme_profile_fields)){
        foreach($upme_profile_fields as $profile_field){
            if(isset($profile_field['type']) && $profile_field['type'] == 'separator' ){
                $meta = isset($profile_field['meta']) ? $profile_field['meta'] : '';
                if($meta != ''){
                    $separator_fields[$meta] = isset($profile_field['name']) ? $profile_field['name']  : '';
                }                
            }            
            else if(isset($profile_field['type']) && $profile_field['type'] == 'usermeta'){
                $meta = isset($profile_field['meta']) ? $profile_field['meta'] : '';
                if($meta != ''){
                    $profile_fields[$meta] = isset($profile_field['name']) ? $profile_field['name']  : '';
                }
            }
        }
    }

?>

<div class="upme-tab-content" id="upme-separator-field-groups-settings-content" style="display:none;">
    <h3><?php _e('Separator Field Groups Settings','upme');?>
        </h3>
        
        
    
    <div id="upme-separator-field-groups-settings" class="upme-custom-fields-screens" style="display:block">

        <form id="upme-separator-field-groups-settings-form">
            <table class="form-table" cellspacing="0" cellpadding="0">
                <tbody>
                    <?php
                        foreach($separator_fields as $sep_meta => $sep_name){
                    ?>
                        <tr valign="top" id="">
                            <th scope="row">
                                <label for=""><?php echo $sep_name; ?></label>
                            </th>
                            <td><select class="chosen-admin_setting" multiple id="separator_group_fields" name="separator_group_fields[<?php echo $sep_meta; ?>][]" style="display: none;">
                                <option value="0"></option>
                                <?php                    
                                      foreach($profile_fields as $prof_meta => $prof_name){ 
                                            $selected = '';
                                          
                                            if(isset($separator_group_fields[$sep_meta]) && is_array($separator_group_fields[$sep_meta]) ){
                                                
                                                if(in_array($prof_meta,$separator_group_fields[$sep_meta])){
                                                    //print_r($separator_group_fields[$sep_meta]);print_r($prof_meta);
                                                    $selected = ' selected="selected" ';
                                                }
                                                
                                            }
                                
                                ?>
                                        <option <?php echo $selected; ?> value="<?php echo $prof_meta; ?>"><?php echo $prof_name; ?></option>
                                <?php } ?>
                                </select>
                            </td>
                        </tr>
                    <?php
                        }

                    ?>
                        

                    <tr valign="top">
                        <th scope="row"><label>&nbsp;</label></th>
                        <td>
                            <?php 
                                echo UPME_Html::button('button', array('name'=>'save-upme-separator-groups-settings', 'id'=>'save-upme-separator-groups-settings', 'value'=> __('Save Changes','upme'), 'class'=>'button button-primary '));
                                echo '&nbsp;&nbsp;';
                                
                            ?>
                            
                        </td>
                    </tr>

                </tbody>
            </table>
        
        </form>
        
    </div>     
</div>