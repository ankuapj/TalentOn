<?php
function fetch($method, $resource, $token, $body = '') {
    $params = array('oauth2_access_token' => $token,
                    'format' => 'json',
              );
     
    // Need to use HTTPS
    $url = 'https://api.linkedin.com' . $resource . '?' . http_build_query($params);
    // Tell streams to make a (GET, POST, PUT, or DELETE) request
    $context = stream_context_create(
                    array('http' => 
                        array('method' => $method,
                        )
                    )
                );
 
 
    // Hocus Pocus
    $response = file_get_contents($url, false, $context);
 
    // Native PHP object, please
    return json_decode($response);
}
if(isset($_GET['code']))
{
	if(isset($_GET['state']))
	{
		include 'authData.php';
		if($_GET['state']==$state)
		{ 	
			session_start();
			//Fetch Code
	
			$code=$_GET['code'];
	
			//Fetch Access Token
			$fields = array(
						'client_id' => $client_id_li,
						'client_secret' => $client_secret_li,
						'code' => $code,
						'redirect_uri' => 'http://www.technited.com/intern/TalentOn/linkedInApi.php',
						'grant_type' => 'authorization_code'
					);


			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string, '&');


			$ch = curl_init();


			curl_setopt($ch,CURLOPT_URL, $url_li);
			curl_setopt($ch,CURLOPT_POST, count($fields));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);


			$result = curl_exec($ch);	

			curl_close($ch);
			
			$decodedData=json_decode($result);
	
			$token=$decodedData->access_token;
			
			$user = fetch('GET', '/v1/people/~:(firstName,lastName)',$token);
			$email = fetch('GET', '/v1/people/~/email-address',$token);
			
			$result=array("name" => $user->firstName." ".$user->lastName, "email" => $email);
			
			$_SESSION['result']=json_encode($result);
	
			header("Location:index.html");
			
		}
	}
}
?>	