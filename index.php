<?php
// require "response.php";
  function promptName(){
    echo("
        <script type='text/javascript'> 
          const name = prompt('Please type your Username.')
        </script>");
        $name = "<script type='text/javascript'> document.write(name); </script>";
        return($name);
    }
    function promptPassword(){
        echo("
        <script type='text/javascript'> 
          const password = prompt('Please type your Password.');
        </script>");
        $password = "<script type='text/javascript'> document.write(password); </script>";
        return($password);
    }



ob_start();


  $name = promptName($prompt_name);
  $password = promptPassword($prompt_Password);
  // $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
  // echo "<a href='response.php'>next</a>";

  function getXMLRes(){
  $ch = curl_init();
  $url = 'http://localhost:1337/api/get-xml';
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $output = curl_exec($ch);
  if($e=curl_error($ch)){
    echo $e;
  }else{
    // header("Access-Control-Allow-Origin: *");
    header("Content-Type: text/xml; charset=utf-8");
    // print_r($decoded);
    // ob_clean();
    echo $output;
    // return $output;
  }
}
readline ( "Press Enter to continue, or Ctrl+C to cancel." );
echo ob_get_level();

if(strlen($name)>0 && strlen($password)>0)
{
  // if(ob_get_level()>0){
  //   ob_end_clean();
  // }
  // sleep(10);
  // ob_end_clean();
    //  getXMLRes();
    // require "response.php";
}


  // if(strlen($name)>0 && strlen($password)>0){
  // // header('Location: response.php');
  //   // getXMLRes($name,$password);
    
  // //  echo "jfb";
  // }

    
?>