<?php 
if (!function_exists('__setCookie')) {
    function __setCookie($c_name, $value, $expiredays, $domain) {

        setcookie($c_name, $value, time() + 60 * 60 * 24 * $expiredays, "/", $domain);
    }   
}
 
if (!function_exists('getTrafficSource')) {
    function getTrafficSource() {

        $strURL = preg_replace('/blog\./', 'www.', $_SERVER['HTTP_HOST']);
        if ($strURL == 'localhost') $strURL = '';
        $domain = $strURL;

        $access_levels = array( 'SMM' => 3, 'MAR' => 3, 'PPC' => 2, 'GORG' => 1 );
        $expire_times = array( 'SMM' => '14', 'MAR' => '14', 'PPC' => '30', 'GORG' => '30' );
        
        //preg_match('#(ppc|smm|mar)=([1-9]{1,4})#', $_SERVER['REQUEST_URI'], $matches);
        preg_match('#(ppc|smm|mar)=(\d+)#', $_SERVER['REQUEST_URI'], $matches);

        if ( is_array($matches) && !empty($matches) ){
            $entry_access_levels = preg_replace("/[^0-9,.]/", "", $matches[0]);
            $entry_cookie_abr = strtoupper($matches[1]);
            $entry_cookie = $entry_cookie_abr . $entry_access_levels;
        }

        if( isset($_COOKIE['calltracker']) && $_COOKIE['calltracker'] == 'DHIT') { unset($_COOKIE['calltracker']);  }   

        if (isset($_COOKIE['calltracker']) && $_COOKIE['calltracker'] != null) {
            $calltracker = preg_replace("/[^a-z,A-Z]/", "", $_COOKIE['calltracker']); # ppc, smm, org
        }

        if ( !empty($entry_access_levels) && ( !isset($calltracker) || (isset($calltracker) && $access_levels[$calltracker] <=  $access_levels[$entry_cookie_abr]) ) ) {
            __setCookie('calltracker', $entry_cookie, $expire_times[$entry_cookie_abr], $domain); 
            $cStr = $entry_cookie;
         
        } else if( strpos($_SERVER['REQUEST_URI'], 'pk_campaign') !== false && ( !isset($calltracker) || (isset($calltracker) && $access_levels[$calltracker] <=  $access_levels['PPC']) ) ) {
            __setCookie('calltracker', 'PPC', 30, $domain); 
            $cStr = 'PPC'; 
        } else {
            if ( isset($calltracker) ) {
                $cStr = $_COOKIE['calltracker']; 
            } else {
                __setCookie('calltracker', 'GORG' , 30, $domain); 
                $cStr = 'GORG';
            }
        } 

        return  strtoupper($cStr);
    }
}

if (!function_exists('getChatSource')) {
    function getChatSource() {

        $strURL = preg_replace('/blog\./', 'www.', $_SERVER['HTTP_HOST']);
        if ($strURL == 'localhost')
            $strURL = '';
        $domain = $strURL;
        if ($_COOKIE['chattracker'] != "" && $_COOKIE['chattracker'] != null) {
            $cStr = $_COOKIE['chattracker'];
        } else if ( strpos($_SERVER['REQUEST_URI'], '?pk_campaign') !== false) {
            __setCookie('chattracker', 'ppc', 30, $domain);
            $cStr = "ppc";
        } else {
            __setCookie('chattracker', 'DHIT', 30, $domain);
            $cStr = "DHIT";
        } 
        return strtoupper($cStr);
    }
}

if (!function_exists('make_post_request')) {
    function make_post_request($url, $data) {

        // parse the given URL
        $url = parse_url($url);
        if ($url['scheme'] != 'http') {
            return 'Only HTTP request are supported!';
        }
        $url['host'] = strtolower($url['host']);
        // add www. at the begining
        if (!stristr($url['host'], 'www.')) {
            $url = 'http://www.' . $url['host'] . $url['path'];
        } else {
            $url = 'http://' . $url['host'] . $url['path'];
        }
        //url-ify the data for the POST
        foreach ($data as $key => $value) {
            $data_string.=$key . '=' . urlencode($value) . '&';
        }
        rtrim($data_string, '&');
        //open connection
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return array('status' => 'ok', 'content' => $result);
    }
}

if (!function_exists('crm_privacy_policy')) {
    function crm_privacy_policy($url, $token){
        
        // parse the given URL
        $url=parse_url($url);
        if ($url['scheme']!='http') { return 'Only HTTP request are supported!'; }

        $url['host']=strtolower($url['host']);
        // add www. at the begining
        if (!stristr($url['host'], 'www.')) {
          $url='http://www.'.$url['host'].$url['path'];
        } else {
          $url='http://'.$url['host'].$url['path'];
        }

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
        curl_setopt($ch, CURLOPT_POST, count($token));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $token);

        //execute post
        $result = curl_exec($ch);
        // //close connection
        curl_close($ch);
        return $result;
    }
}


global $thMessage;
if (empty($thMessage)) {
    if (strpos($_SERVER['REQUEST_URI'], 'token') !== false) {

        $remote_url='http://www.cleaningcrm.com/API/terms_and_conditions.php';
        $token = explode('?token=', $_SERVER['REQUEST_URI'], 2);
        $token = 'token=' . $token[1];
        $result = crm_privacy_policy($remote_url, $token); 
        
        if ( $result == 1 ) {

            $thMessage = "Dear customer, thank you for accepting our company's Terms and Conditions.";
        } else {

            $thMessage = "Dear customer, as you have already accepted our company's Terms & Conditions on your first visit to our web-site, you can now enjoy our quality services, without coming back here.";
        }
    } else {

        $thMessage = "Thanks for contacting us! We will get in touch with you shortly.";
        $thMessage .= "<script>window.onload=function() {_gaq.push(['_trackEvent', 'webform', 'submit', 'quote']);};</script>";
    }
}

?>