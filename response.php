<?php 
   if( $_POST["name"] || $_POST["age"] ) {
      if (preg_match("/[^A-Za-z'-]/",$_POST['name'] )) {
         die ("invalid name and name should be alpha");
      }
      echo "Welcome ". $_POST['name']. "<br />";
      echo "You are ". $_POST['age']. " years old.";
      
      exit();
   }

   // // get xml Response
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
     // header("Content-Type: text/xml; charset=utf-8");
     // print_r($decoded);
     echo $output;
     // return $output;
   }
 }
 getXMLRes()
?>
<!-- <html>
   <body>
   
      <form action = "<?php $_PHP_SELF ?>" method = "POST">
         Name: <input type = "text" name = "name" />
         Age: <input type = "text" name = "age" />
         <input type = "submit" />
      </form>
   
   </body>
</html> -->


