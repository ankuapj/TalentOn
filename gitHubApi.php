<?php
session_start();
if(isset($_GET['code']))
{
	include 'authData.php';
	
	//Fetch Code
	
	$code=$_GET['code'];
	
	//Fetch Access Token
	
	$fields = array(
						'client_id' => $client_id_gh,
						'client_secret' => $client_secret_gh,
						'code' => $code,
						'Accept' => 'json'
					);


	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');


	$ch = curl_init();


	curl_setopt($ch,CURLOPT_URL, $url_gh);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);


	$result = curl_exec($ch);	

	curl_close($ch);
	
	$myText = (string)$result;
	$split1=explode("&",$myText);
	$split2=explode("=",$split1[0]);
	$token=$split2[1];
	//Fetch JSON
	
	$ch = curl_init();
	
	$data_url="https://api.github.com/user?access_token=".$token;

	$useragent = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';
	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	curl_setopt($ch,CURLOPT_URL, $data_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$result = curl_exec($ch);	
	
	curl_close($ch);
	
	//Pass result to session variable
	
	$_SESSION['result']=$result;
	
	header("Location:index.html");

}
else
{
	header("Location:error.html");
}
?>