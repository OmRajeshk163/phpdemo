<?php
// get xml Response
function getXMLRes(){
  $ch = curl_init();
  $url = 'http://localhost:1337/api/get-xml';
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $output = curl_exec($ch);
  if($e=curl_error($ch)){
    echo $e;
  }else{
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: text/xml; charset=utf-8");
    // print_r($decoded);
    echo $output;
    // return $output;
  }
}
getXMLRes($name,$password);
?>