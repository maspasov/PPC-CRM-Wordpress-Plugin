<?php
include_once("../../../../wp-load.php");

//Get from Theme Options URL path
$pdf_url = get_option('pdf_url');

if(isset($pdf_url) && !empty($pdf_url)) {
	
	if (function_exists('curl_init')) {
		$ch = curl_init();
		if( !empty($_GET['file_name']) ){
			curl_setopt($ch, CURLOPT_URL,  $pdf_url . str_replace('/', '', $_GET['file_name']));

			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
			$content=curl_exec($ch);
			$header=curl_getinfo($ch);
			curl_close($ch);
		}
	} else {
		// curl library is not installed so we better use something else
	}


	if (!$header['errno'] and $header['http_code']=='200') {	 
	 header('Content-type: application/pdf');
	 header('X-Robots-Tag: noindex', true); 
	 
	 echo $content;
	} else {
	 header("HTTP/1.0 404 Not Found");
	 header("Status: 404 Not Found");
	}
}
?>