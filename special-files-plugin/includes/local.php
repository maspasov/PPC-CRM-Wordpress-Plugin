<?php
/*$locations = array(
					'maps.google.com.au' => array('medium' => 'maps', 'source' => 'google.com.au'),
					'maps.google.com' => array('medium' => 'maps', 'source' => 'google.com'),
					'maps.google.co.uk' => array('medium' => 'maps', 'source' => 'google.co.uk'),
					'maps.google.' => array('medium' => 'maps', 'source' => 'other'),
					 
					'plus.url.google.com/u/' => array('medium' => 'plus', 'source' => 'business%20page'),
					'plus.url.google.com/' => array('medium' => 'plus', 'source' => 'listing'),
					
					'&tbm=plcs&'    => array('google.com.au' => array('medium' => 'google%20places','source' => 'google.com.au'),
											 'google.com' => array('medium' => 'google%20places','source' => 'google.com'),
											 'google.co.uk' => array('medium' => 'google%20places','source' => 'google.co.uk'),
											 'google.' => array('medium' => 'google%20places','source' => 'other'),
										),
					'google.com.au' => array('medium' => 'organic', 'source' => 'google.com.au'),
					'google.com' => array('medium' => 'organic', 'source' => 'google.com'),
					'google.co.uk' => array('medium' => 'organic', 'source' => 'google.co.uk'),
					'google.' => array('medium' => 'organic', 'source' => 'other'),
					
);

$source = "other";
$medium = "organic";
$campaign = str_replace(array("http://","www.","/"), "", $_SERVER['HTTP_HOST']);

if(isset($_SERVER["HTTP_REFERER"])){
	$referer = $_SERVER["HTTP_REFERER"];
	//echo $referer;
	foreach($locations as $location_key => $location_data){
		if(strpos($referer,$location_key) !== FALSE){
			if($location_key == '&tbm=plcs&'){
				foreach($location_data as $k => $src_med){
					if(strpos($referer,$k) !== FALSE){
						$source = $src_med['source'];
						$medium = $src_med['medium'];
						break;
					}
				}
			}
			else {
				$source = $location_data['source'];
				$medium = $location_data['medium'];
			}
			break;
		}
	}
}
*/

$locations = array('plus.url.google.com/' => array('medium' => 'pure', 'source' => 'google.com%20/%20places'),
					'google.com.au' => array('medium' => 'pure', 'source' => 'google.com.au%20/%20places'),
					'google.com' => array('medium' => 'pure', 'source' => 'google.com%20/%20places'),
					'google.co.uk' => array('medium' => 'pure', 'source' => 'google.co.uk%20/%20places'),
					'google.' => array('medium' => 'pure', 'source' => 'other'),
					
);

$source = "other";
$medium = "pure";
$campaign = str_replace(array("http://","www.","/"), "", $_SERVER['HTTP_HOST']);
if(isset($_SERVER["HTTP_REFERER"])){
	$referer = $_SERVER["HTTP_REFERER"];
	foreach($locations as $location_key => $location_data){
		if(strpos($referer,$location_key) !== FALSE){
			$source = $location_data['source'];
			$medium = $location_data['medium'];
			break;
		}
	}
}

header("Location: http://www.$campaign?utm_source=$source&utm_medium=$medium&utm_campaign=$campaign%20/%20Places");

?>