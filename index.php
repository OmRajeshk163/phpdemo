<?php
	// function getXMLRes($jwt,$code){
	// 	$ch = curl_init();
	// 	$url = 'http://localhost:1337/api/get-xml';
	// 	$authHeaders = array();
	// 	$authHeaders[] = "Authorization: Bearer ". $jwt;
  //   $code_string = http_build_query($code);
  //   print_r($jwt);

	// 	curl_setopt($ch, CURLOPT_URL,$url);
  //   curl_setopt($ch, CURLOPT_POST, true);
	// 	curl_setopt($ch, CURLOPT_POSTFIELDS, $code_string);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// 	curl_setopt($ch, CURLOPT_HTTPHEADER, $authHeaders);
	// 	$output = curl_exec($ch);
	// 	if($err=curl_error($ch)){
	// 		echo $url;
	// 	} else {
	// 		header("Access-Control-Allow-Origin: *");
	// 		header("Content-Type: text/xml; charset=utf-8");
	// 		echo $output;
	// 	}
	// }

	function checkAuth($username, $password){
		$fields = [
			"identifier" => $username,
			"password" => $password
		];
		$fields_string = http_build_query($fields);
		$ch = curl_init();
		$url = 'http://localhost:1337/api/auth/local';

		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

		$response = curl_exec($ch);
		$auth_resp_array = json_decode($response, true);
    $name=$auth_resp_array['user']['username'];
    $ch = curl_init();
		$url = 'http://localhost:1337/api/get-feed-by-name/'.$name;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resp_array = json_decode(curl_exec($ch), true);
		if(isset($resp_array['error'])){
			echo 'Invalid Credientials';
		} else {
			$jwt = $auth_resp_array['jwt'];
      $code=$resp_array["advance_editor_value"];
      ob_end_clean();
      // getXMLRes($jwt,$code);
      getXMLResNoAuth($code);
		}
	}
  // Check for encryption
  checkForEncryption();
  function checkForEncryption(){
    
    $name=$_GET['name'];
    $ch = curl_init();
		$url = 'http://localhost:1337/api/get-feed-by-name/'.$name;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resp_array = json_decode(curl_exec($ch), true);
    $encryption=$resp_array["encryption"];
    echo '<body style=\'text-align:center\'>
            <form  method="post">
              Username: <input type = "text" name = "name" />
              Password: <input type = "password" name = "password" />
              <input type = "submit" />
            </form>
          </body>';
    if(!$encryption){
      $code=$resp_array["advance_editor_value"];
      ob_end_clean();
      getXMLResNoAuth($code);
    }else if($encryption){
        if(isset($_POST['name']) || isset($_POST['password'])){
          if( $_POST["name"] || $_POST["password"] ) {
            checkAuth($_POST["name"], $_POST["password"]);
            exit();
          }
        }
    }
  }

  function getXMLResNoAuth($code){
    

		$ch = curl_init();
		$url = 'http://localhost:1337/api/get-xml-php';
		curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST, true);
    $code_string = http_build_query($code);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $code_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);

		if($err=curl_error($ch)){
			echo $url;
		} else {
			header("Access-Control-Allow-Origin: *");
      header("Content-Type: text/xml; charset=UTF-8");
			echo $output;
		}
	}
?>