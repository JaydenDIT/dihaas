<?php
/**
 * Description of Senitizer: This class is mainly for senitizing user input that may contain vulnerable
 * characters
 * @author: Nganthoiba
 * @helper: Konsam Malemngalba Singh
 */
namespace App\Library;

class Senitizer{

    public static function senitize($data=[]){
        $senitizedData = self::filterSpecialChars($data);
        $senitizedData = self::clean($senitizedData);
        return $senitizedData;
    }

    /* Method for recursively senitizing risky elements and 
     * thereby converting predefined html characters to HTML entities to 
     * avoid Cross Site Scripting (xss)*/
    public static function clean($data = []){        
        $clean_input = [];
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = self::clean($v);
            }
        } else {
            if(is_null($data)){
                $clean_input = "";
            }
            else{   
                $string = filter_var("".$data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $clean_input = trim(htmlspecialchars(strip_tags(stripslashes($string)),ENT_QUOTES, 'UTF-8')); 
            }
        }
        return $clean_input;
    }
    
    public static function filterSpecialChars(array $data=array() ){
        if(sizeof($data) == 0){
            return $data;
        }
        foreach($data as $key => $value)
        {
            if(is_array($value)){
                $data[$key] = self::filterSpecialChars($value);
            }
            else if(!is_numeric($key)){            
                $data[$key]= filter_var($data[$key], FILTER_SANITIZE_SPECIAL_CHARS);      
            }
            else{
                $data[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $data;
    }
    
}