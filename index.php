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




  // $name = promptName($prompt_name);
  // $password = promptPassword($prompt_Password);
  echo "<a href='response.php'>next</a>";
  if(strlen($name)>0 && strlen($password)>0){
  // header('Location: response.php');
    
    
  //  echo "jfb";
  }

    
?>