<?php
  function baseUrl(){
    return 'http://localhost:1337/api/';
  }
  // Check for Feed Encryption
  checkForEncryption();
  function checkForEncryption(){
		try{
			$name=$_GET['name'];
			$path=$_GET['path'];
			$ch = curl_init();
			$url = baseUrl().'get-feed-by-client-name/'.$name;
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$resp_array = json_decode(curl_exec($ch), true);
			$encryption = $resp_array["encryption"];
			$active = $resp_array["active"];
			if(is_countable(count($resp_array)) && count($resp_array) === 0){
				ob_end_clean();
				echo '<h2>{"error":"The username does not exist"}</h2>';
			}else{
				if($active){
					if(!$encryption){
						if(is_null($resp_array["advance_editor_value"])){
							$code=new stdClass();
						}else{
							$code = new stdClass();
							$code->code=$resp_array["advance_editor_value"];
						}
						ob_end_clean();
						getXMLResNoAuth($code);
					}else if($encryption){
						checkAuth($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
					}
				}else if(!$active){
					ob_end_clean();
					echo '<h2>{"error":"The feed you want to access is Deactivated, Please reach out to Healthday"}</h2>';
				}
			}
		}catch(err){
			print_r(err);
		}

  }
  // Check for Authorization
	function checkAuth($username, $password){
		$fields = [
			"identifier" => $username,
			"password" => $password
		];
		$fields_string = http_build_query($fields);
		$ch = curl_init();
		$url = baseUrl().'auth/local';
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
		$auth_resp_array = json_decode(curl_exec($ch), true);
    $jwt = $auth_resp_array['jwt'];
    $name = $auth_resp_array['user']['username'];
		$authHeaders = array();
		$authHeaders[] = "Authorization: Bearer ". $jwt;

    // Get the feed by client Username
    $ch = curl_init();
		$url = baseUrl().'get-feed-by-client-name/'.$name;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $authHeaders);
    $resp_array = json_decode(curl_exec($ch), true);

		if(isset($resp_array['error'])){
      header('WWW-Authenticate: Basic realm="Test Authentication System"');
      header('HTTP/1.0 401 Unauthorized');
			echo '<h2>{"error":"You must ask the owner of this resource for the credentials"}</h2>';
      exit;
		} else {
			$jwt = $auth_resp_array['jwt'];
      ob_end_clean();
			if(is_null($resp_array["advance_editor_value"])){
				$code=new stdClass();
			}else{
				$code = new stdClass();
				$code->code=$resp_array["advance_editor_value"];
			}
      getXMLRes($jwt,$code);
		}
	}
// Get the feed for Authorized User
  function getXMLRes($jwt,$code){
		$format=$_GET['format'];
		$ch = curl_init();
		$format = explode(".",$_GET['format']);
		if($format[1]==='hdxml'){
			$format[1] = 'xml';
		}
		$url = baseUrl().'get-'.$format[1];
		$authHeaders = array();
		$authHeaders[] = "Authorization: Bearer ". $jwt;
    $code_string = http_build_query($code);
		curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $code_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $authHeaders);
		$output = curl_exec($ch);
		if($err = curl_error($ch)){
			echo $url;
		} else {
				header("Access-Control-Allow-Origin: *");
				if($format[1]==='json'){
					header('Content-Type: application/json; charset=utf-8');
					print_r(json_decode($output,true));
				}else{
				header("Content-Type: text/xml; charset=utf-8");
					echo $output;
				}
		}
	}
  
  // Get the feed for Unauthorized User
  function getXMLResNoAuth($code){
		$ch = curl_init();
		$format = explode(".",$_GET['format']);
		if($format[1]==='hdxml'){
			$format[1] = 'xml';
		}
		$url = baseUrl().'get-'. $format[1].'-unauth';
		curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST, true);
    $code_string = http_build_query($code);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $code_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		if($err = curl_error($ch)){
			echo $url;
		} else {
			header("Access-Control-Allow-Origin: *");
			if($format[1]==='json'){
				header('Content-Type: application/json; charset=utf-8');
				print_r(json_decode($output,true));
			}else{
				header("Content-Type: text/xml; charset=utf-8");
				echo $output;
			}
		}
	}
?>