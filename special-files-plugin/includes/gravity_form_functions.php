<?php

/*-------------- Really Simple CAPTCHA Admin validation -----/*/
add_filter('gform_validation', 'admin_validation_callback_checker',100);
function admin_validation_callback_checker($validation_result){    

    $form = $validation_result["form"];
        foreach($form["fields"] as &$field){

            #DEBUG CAPTHCA TYPE
            // if($_SERVER['REMOTE_ADDR'] == "178.16.129.106") print_r($field);
            
            if($field["type"] == "customcaptcha" || $field["type"] == "captcha"){ 
                if($_SERVER['REMOTE_ADDR'] == "217.145.95.33") {
                    $field["failed_validation"] = false;
                    break;
                }                 
            }
        }

     //check for validation of all inputs
    $validation_result["is_valid"] = true;
    foreach($form["fields"] as &$field) {
        if ($field["failed_validation"] == true) {
            $validation_result["is_valid"] = false;
        }
    }     
    
    $validation_result["form"] = $form;    
    return $validation_result;    
}
/*-------------- Really Simple CAPTCHA Admin validation -----/*/


// Contact 7 - CAPTCHA Admin validation 
function admin_validation_callback_checker_cf7_validate( $result, $tag ) {	

	if($_SERVER['REMOTE_ADDR'] == '217.145.95.33') {
		
		$name = $tag['name'];
		$the_value = $_POST[$name];

		if($the_value == "sys147") {			
			$result['valid'] = true;	
		}

		// Already invalid?
		if ( !$result['valid'] ) {
			return $result;
		}		

	}
	
	return $result;
}
add_filter( 'wpcf7_validate_captchar', 'admin_validation_callback_checker_cf7_validate', 10, 2 );
// Contact 7 - CAPTCHA Admin validation 

?>