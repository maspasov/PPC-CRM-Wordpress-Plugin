<?php
header('Content-Type: image/png');

$site = $_GET['siteuri'];
$lang = $_GET['lang'];

$img = imagecreatetruecolor(450, 90);
$black = imagecolorallocate($img, 0, 0, 0);
imagecolortransparent($img, $black);
$textcolor= imagecolorallocate($img, 157, 157, 157);

if(isset($_GET['address']) && !(empty($_GET['address']))) {
	$ADDRESS = urldecode($_GET['address']);
	if (strpos($ADDRESS,'<br>') !== false) {
   		$address_by_line = explode('<br>', $ADDRESS);
   		for($i=0; $i<sizeof($address_by_line); $i++){
   			$lines[$i+1] = $address_by_line[$i];
   		}
	}else {
		$lines[1] = "We are based at: ".$ADDRESS;
	}
} else {
	if($lang == 'en-AU') {
		$lines[1] = "This website is owned by FANTASTIC SERVICES GROUP PTY LTD.";
		$lines[2] = "Registered company in Australia ACN# 148 976 256, ";
		$lines[3] = "trading as ".$site;
		$lines[4] = "Address: 13 Thomas Place, Prahran, VIC";
	} else {
		$lines[1] = "This website is owned by 1st Online Solutions Ltd.";
		$lines[2] = "Registered in England and Wales,";
		$lines[3] = "trading as ".$site;
		$lines[4] = "Registration number: 07082485";
		$lines[5] = "Registered Address: 98 Tooley Street, SE1 2TH, London";
	}
}

foreach ($lines as $key => $value) {
        imagestring($img , 2, 0, ($key*15), $value, $textcolor );
}
imagepng($img);
?>