<?php

class UPME_Import_Export
{

    public function __construct()
    {

        if (is_admin()) {
            /* AJAX requests for import and export for custom fields */
            add_action('wp_ajax_upme_download_export_fields', array($this, 'upme_download_export_fields'));
            add_action('wp_ajax_upme_upload_import_fields', array($this, 'upme_upload_import_fields'));

            /* AJAX requests for import and export for settings */
            add_action('wp_ajax_upme_download_export_settings', array($this, 'upme_download_export_settings'));
            add_action('wp_ajax_upme_upload_import_settings', array($this, 'upme_upload_import_settings'));
            
            /* AJAX requests for import and export for users */
            add_action('wp_ajax_upme_download_export_users', array($this, 'upme_download_export_users'));

        }

    }

    private function csv_to_array($filename = '', $delimiter = ',' , $type = 'rows')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return FALSE;

        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {

                switch ($type) {
                    case 'rows':

                        if (!$header) {
                            $header = $row;
                        } else {
                            $data[$row[0]] = $this->array_combine($header, $row);
                        }

                        break;
                    
                    case 'columns':
                        
                        if (!$header) {
                            $header = $row;
                        } else {
                            $data[$row[0]] = $row[1];
                        }

                        break;
                }
                
            }
            fclose($handle);
        }
        return $data;
    }

    private function array_combine($arr1, $arr2)
    {
        $count = min(count($arr1), count($arr2));
        return array_combine(array_slice($arr1, 0, $count), array_slice($arr2, 0, $count));
    }

    public function upme_upload_import_fields()
    {
        global $upme_admin;
        if (upme_is_in_get('current_tab')) {

            $allowed = array('text/csv','application/csv','application/vnd.ms-excel');

            if (isset($_FILES['fields_file']) && $_FILES['fields_file']['error'] == 0) {

                if (!in_array(strtolower($_FILES['fields_file']['type']), $allowed)) {
                    echo '{"status":"error"}';
                    exit;
                } else {
                    //read CSV
                    $csvData = $this->csv_to_array($_FILES['fields_file']['tmp_name']);
                    $optionArray = get_option('upme_profile_fields');

                    $updatedArray = array();

                    if (upme_is_in_post('site_import_fields') && count(upme_post_value('site_import_fields') > 0)) {
                        //get option array

                        foreach (upme_post_value('site_import_fields') as $postedVal) {
                            $updatedArray[$postedVal] = $csvData[$postedVal];
                        }
                    } else {
                        $updatedArray = $csvData;
                    }
                    $mergedArray = ($updatedArray + $optionArray);
                    ksort($mergedArray);

                    update_option('upme_profile_fields', ($mergedArray));

                    echo '{"status":"success"}';
                    exit;
                }

                echo '{"status":"error"}';
            }else{
                echo '{"status":"error"}';
                exit;
            }
            exit();
        }

    }


    public function upme_download_export_fields()
    {
        global $upme_admin;

        if (upme_is_in_get('current_tab')) {

            $optionsRaw = get_option('upme_profile_fields');
            if (upme_is_in_get('site_export_field_type')) {
                if (upme_get_value('site_export_field_type') == 'selected_fields') {

                    $options = array();

                    if (upme_is_in_get('site_export_fields') && count(upme_get_value('site_export_fields')) > 0) {

                        foreach ($optionsRaw as $key => $value) {
                            if (in_array($key, upme_get_value('site_export_fields'))) {
                                $options[$key] = $value;
                            }
                        }

                    }
                } else {
                    $options = $optionsRaw;
                }

            }

            $filename = 'fields.csv';
            header('Content-Type: application/csv');
            header('Content-Disposition: attachement; filename="' . $filename . '";');

            $headerArray = array(
                'position',
                'icon',
                'field',
                'type',
                'meta',
                'meta_custom',
                'name',
                'can_hide',
                'can_edit',
                'allow_html',
                'private',
                'required',
                'show_in_register',
                'social',
                'tooltip',
                'deleted',
                'show_to_user_role',
                'edit_by_user_role',
                'help_text',
                'show_to_user_role_list',
                'edit_by_user_role_list',
                'predefined_loop',
                'choices',
            );

            $f = fopen('php://output', 'w');
            fputcsv($f, $headerArray, ','); //Header
            foreach ($options as $key => $value) {

                $exp_val['position'] =  isset($value['position']) ? $value['position'] : 'null';
                $exp_val['icon'] =  isset($value['icon']) ? $value['icon'] : 'null';
                $exp_val['field'] =  isset($value['field']) ? $value['field'] : 'null';
                $exp_val['type'] =  isset($value['type']) ? $value['type'] : 'null';
                $exp_val['meta'] =  isset($value['meta']) ? $value['meta'] : 'null';
                $exp_val['meta_custom'] =  isset($value['meta_custom']) ? $value['meta_custom'] : 'null';
                $exp_val['name'] =  isset($value['name']) ? $value['name'] : 'null';
                $exp_val['can_hide'] =  isset($value['can_hide']) ? $value['can_hide'] : 'null';
                $exp_val['can_edit'] =  isset($value['can_edit']) ? $value['can_edit'] : 'null';
                $exp_val['allow_html'] =  isset($value['allow_html']) ? $value['allow_html'] : 'null';
                $exp_val['private'] =  isset($value['private']) ? $value['private'] : 'null';
                $exp_val['required'] =  isset($value['required']) ? $value['required'] : 'null';
                $exp_val['show_in_register'] =  isset($value['show_in_register']) ? $value['show_in_register'] : 'null';
                $exp_val['social'] =  isset($value['social']) ? $value['social'] : 'null';
                $exp_val['tooltip'] =  isset($value['tooltip']) ? $value['tooltip'] : 'null';
                $exp_val['deleted'] =  isset($value['deleted']) ? $value['deleted'] : 'null';
                $exp_val['show_to_user_role'] =  isset($value['show_to_user_role']) ? $value['show_to_user_role'] : 'null';
                $exp_val['edit_by_user_role'] =  isset($value['edit_by_user_role']) ? $value['edit_by_user_role'] : 'null';
                $exp_val['help_text'] =  isset($value['help_text']) ? $value['help_text'] : 'null';
                $exp_val['show_to_user_role_list'] =  isset($value['show_to_user_role_list']) ? $value['show_to_user_role_list'] : 'null';
                $exp_val['edit_by_user_role_list'] =  isset($value['edit_by_user_role_list']) ? $value['edit_by_user_role_list'] : 'null';
                $exp_val['predefined_loop'] =  isset($value['predefined_loop']) ? $value['predefined_loop'] : 'null';
                $exp_val['choices'] =  isset($value['choices']) ? $value['choices'] : 'null';
/*
  $exp_val['show_to_user_role_list'] =  isset($value['show_to_user_role_list']) ? explode(',', $value['show_to_user_role_list']) : 'null';
        if(is_array($exp_val['show_to_user_role_list'])){
            $exp_val['show_to_user_role_list'] = implode('#@#', $exp_val['show_to_user_role_list']);
        }

                $exp_val['edit_by_user_role_list'] =  isset($value['edit_by_user_role_list']) ? explode(',', $value['edit_by_user_role_list']) : 'null';
        if(is_array($exp_val['show_to_user_role_list'])){
            $exp_val['edit_by_user_role_list'] = implode('#@#', $exp_val['edit_by_user_role_list']);
        }
*/
    
//echo "<pre>";print_r($value);
//echo "<pre>";print_r($exp_val);
                fputcsv($f, $exp_val, ',');
            }

            exit;
        }
    }



    public function upme_upload_import_settings()
    {
        global $upme_admin;
        if (upme_is_in_get('current_tab')) {

            $allowed = array('text/csv','application/csv');

            if (isset($_FILES['settings_file']) && $_FILES['settings_file']['error'] == 0) {

                if (!in_array(strtolower($_FILES['settings_file']['type']), $allowed)) {
                    echo '{"status":"error"}';
                    exit;
                } else {
                    //read CSV
                    $csvData = $this->csv_to_array($_FILES['settings_file']['tmp_name'],',','columns');
                    
                    $optionArray = get_option('upme_options');

                    $updatedArray = array();

                    if (upme_is_in_post('site_import_settings') && count(upme_post_value('site_import_settings') > 0)) {
                        //get option array

                        foreach (upme_post_value('site_import_settings') as $postedVal) {
                            
                            $updatedArray[$postedVal] = $csvData[$postedVal];
                        }
                    } else {

                        $csvData['choose_roles_for_registration'] = explode(',', $csvData['choose_roles_for_registration']);
                        $csvData['site_lockdown_allowed_pages'] = explode(',', $csvData['site_lockdown_allowed_pages']);
                        $csvData['site_lockdown_allowed_posts'] = explode(',', $csvData['site_lockdown_allowed_posts']);
                        $csvData['site_lockdown_allowed_urls'] = str_replace(',',"\n", $csvData['site_lockdown_allowed_urls']);

                        $updatedArray = $csvData;
                    }
                    $mergedArray = ($updatedArray + $optionArray);
                    
                    ksort($mergedArray);

                    update_option('upme_options', ($mergedArray));

                    echo '{"status":"success"}';
                    exit;
                }

                echo '{"status":"error"}';
            }else{
                echo '{"status":"error"}';
                exit;
            }
            exit();
        }

    }


    public function upme_download_export_settings()
    {
        global $upme_admin;

        if (upme_is_in_get('current_tab')) {

            $optionsRaw = get_option('upme_options');
            if (upme_is_in_get('site_export_settings_type')) {
                if (upme_get_value('site_export_settings_type') == 'selected_settings') {

                    // $options = array();

                    // if (upme_is_in_get('site_export_fields') && count(upme_get_value('site_export_fields')) > 0) {

                    //     foreach ($optionsRaw as $key => $value) {
                    //         if (in_array($key, upme_get_value('site_export_fields'))) {
                    //             $options[$key] = $value;
                    //         }
                    //     }

                    // }
                } else {
                    $options = $optionsRaw;
                }

            }

            $filename = 'settings.csv';
            header('Content-Type: application/csv');
            header('Content-Disposition: attachement; filename="' . $filename . '";');

            $headerArray = array('Setting Name','Setting Value');

            $f = fopen('php://output', 'w');
            fputcsv($f, $headerArray, ','); //Header

            foreach ($options as $key => $value) {
                
                if($key == 'site_lockdown_allowed_urls'){
                    $value = explode(PHP_EOL, $value);
                }
                
                if(is_array($value)){
                  $value = implode(',',$value);
                  fputcsv($f, array($key,$value), ',');  
                }else{
                  fputcsv($f, array($key,$value), ',');  
                }
                
            }

            exit;
        }
    }
    
    public function upme_download_export_users()
    {
        global $upme_admin;

        if (upme_is_in_get('current_tab')) {

            $profileFieldsRaw = get_option('upme_profile_fields');
            if (upme_is_in_get('site_export_users_type')) {
                if (upme_get_value('site_export_users_type') == 'all_users') {
                    
                    $users_query = new WP_User_Query( array( 
                        'orderby' => 'registered',
                        'order'   => 'desc',
                        
                    ) );
                
                } else {
                    $user = (array) upme_get_value('site_export_users_type');
                    $users_query = new WP_User_Query( array( 
                        'orderby' => 'registered',
                        'order'   => 'desc',
                        'include' => $user
                    ) );
                }

                $result_users = $users_query->get_results();
              
            }

            $filename = 'users.csv';
            header('Content-Type: application/csv');
            header('Content-Disposition: attachement; filename="' . $filename . '";');
            
            $headerArray = array();
            $metaArray = array();
            foreach($profileFieldsRaw as $k => $fieldRaw){
                if($fieldRaw['type'] != 'separator' && ($fieldRaw['meta'] != 'user_pass' && $fieldRaw['meta'] != 'user_pass_confirm' && $fieldRaw['meta'] != 'user_pic' ) ){
                    array_push($headerArray, $fieldRaw['name']);
                    array_push($metaArray, $fieldRaw['meta']);
                }
            }

            

            $f = fopen('php://output', 'w');
            fputcsv($f, $headerArray, ','); //Header
            
            foreach($result_users as $user){
                $valuesArr = array();
                foreach ($metaArray as $key => $meta) {
                
                    $value = get_user_meta($user->ID,$meta,true);
                    array_push($valuesArr,$value);


                }
                fputcsv($f, $valuesArr, ','); 
            }

//            

            exit;
        }
    }
}

$upme_import_export = new UPME_Import_Export();
