<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/*
*
* ---------------how to use-----------------
* ------------------------------------------
* Developed by <sourav.diubd@gmail.com>
*
* $autoload['helper'] =  array('lang');

* display a language
* echo display('helloworld'); 

* display language list
* $lang = languageList(); 
* ------------------------------------------
*
*/


if (!function_exists('display')) {

    function display($text = null)
    {
        $ci =& get_instance();
        $ci->load->database();
        $userLang = $ci->input->cookie('Lng', true);
        $table  = 'language';
        $phrase = 'phrase';
        $setting_table = 'setting';
        $default_lang  = 'english';

        //set language  
        $data = $ci->db->get($setting_table)->row();
        if (!empty($data->language)) {
            if(!empty($userLang )){
                $language = $userLang ; 
            }else{
                $language = $data->language;
            }
             
        } else {
            $language = $default_lang; 
        } 
 
        if (!empty($text)) {

            if ($ci->db->table_exists($table)) { 

                if ($ci->db->field_exists($phrase, $table)) { 

                    if ($ci->db->field_exists($language, $table)) {

                        $row = $ci->db->select($language)
                              ->from($table)
                              ->where($phrase, $text)
                              ->get()
                              ->row(); 

                        if (!empty($row->$language)) {
                            return $row->$language;
                        } else {
                            return false;
                        }

                    } else {
                        return false;
                    }

                } else {
                    return false;
                }

            } else {
                return false;
            }            
        } else {
            return false;
        }  

    }
 
}

if (!function_exists('print_value')) {
    function print_value($input_array = [], $varibale = '', $blank_response = FALSE)
    {
        $response = ($blank_response) ? '' : '-';
        $input_array = (object)($input_array);
        if(isset($input_array->$varibale))
        {
            $input = isset($input_array->$varibale) ? $input_array->$varibale : $input_array[$varibale];
            if($input != "")
            {
                $response = $input;
            }
        }
        return $response;
    }
}
if (!function_exists('print_date')) {
    function print_date($input_array = [], $varibale = '', $blank_response = FALSE)
    {
        $response = ($blank_response) ? '' : '-';
        $input_array = (object)($input_array);
        if(isset($input_array->$varibale))
        {
            $input = isset($input_array->$varibale) ? $input_array->$varibale : $input_array[$varibale];
            if($input != "" && $input !='0000-00-00')
            {
                $response = date("jS F Y", strtotime($input));
            }
        }
        return $response;
    }
}


// $autoload['helper'] =  array('language_helper');

/*display a language*/
// echo display('helloworld'); 

/*display language list*/
// $lang = languageList(); 
