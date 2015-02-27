<?php
if (function_exists('curl_init')) {
 $ch = curl_init();
 $img = explode("/", $_GET['image_name']);
 if($img[1]){
 curl_setopt($ch, CURLOPT_URL, 'http://www.cheapapartmentsistanbul.com/newsletter/images/'.$img[0].'/'.$img[1]);
 }else{
 curl_setopt($ch, CURLOPT_URL, 'http://www.cheapapartmentsistanbul.com/newsletter/images/mailtemplates/'.$_GET['image_name']);
 }
 curl_setopt($ch, CURLOPT_HEADER, 0);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
 $content=curl_exec($ch);
 $header=curl_getinfo($ch);
 curl_close($ch);
} else {
   // curl library is not installed so we better use something else
}
if (!$header['errno'] and $header['http_code']=='200') {
 header('Content-Type: image/jpeg');
 echo $content;
} else {
 header("HTTP/1.0 404 Not Found");
 header("Status: 404 Not Found");
}
?>