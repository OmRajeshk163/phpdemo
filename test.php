<?php

	function getXMLRes($jwt,$client_id){
		$ch = curl_init();
		$url = 'http://localhost:1337/api/get-xml/44';
		$authHeaders = array();
		$authHeaders[] = "Authorization: Bearer ". $jwt;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $authHeaders);
		$output = curl_exec($ch);
		if($err=curl_error($ch)){
			echo $url;
		} else {
			header("Access-Control-Allow-Origin: *");
			header("Content-Type: text/xml; charset=utf-8");
			echo $output;
		}
	}

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
		$resp_array = json_decode($response, true);
		if(isset($resp_array['error'])){
			echo 'Invalid Credientials';
		} else {
			$jwt = $resp_array['jwt'];
			$client_id = $resp_array['user']['id'];
			// $js_code = 'console.log(' . json_decode($response, true) . ');';
			getXMLRes($jwt, $client_id);
		}
	}

	if(isset($_POST['name']) || isset($_POST['password'])){
		if( $_POST["name"] || $_POST["password"] ) {
			checkAuth($_POST["name"], $_POST["password"]);
			exit();
		}
  }
?>

<html>
  <body style='text-align:center'>
		<form  method="post">
			Username: <input type = "text" name = "name" />
			Password: <input type = "password" name = "password" />
			<input type = "submit" />
		</form>
  </body>
</html>