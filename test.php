<?php

 function getXMLRes(){
   $ch = curl_init();
  $url = 'http://localhost:1337/api/get-xml';
   curl_setopt($ch,CURLOPT_URL,$url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   $output = curl_exec($ch);
   if($err=curl_error($ch)){
     echo $err;
   }else{
     header("Access-Control-Allow-Origin: *");
     header("Content-Type: text/xml; charset=utf-8");
     // print_r($decoded);
     echo $output;
     // return $output;
   }
 }
 if( $_GET["name"] || $_GET["password"] ) {
  getXMLRes();
  exit();
 }

  //  if( $_GET["name"] || $_GET["age"] ) {
  //     echo "Welcome ". $_GET['name']. "<br />";
  //     echo "You are ". $_GET['age']. " years old.";
      
  //     exit();
  //  }
?>
<html>
   <body>
   
      <form  >
         Name: <input type = "text" name = "name" />
         Password: <input type = "password" name = "password" />
         <input type = "submit" />
      </form>
      
   </body>
</html>