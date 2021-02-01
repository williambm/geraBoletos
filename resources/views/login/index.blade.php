<html>
<head>
<script language=JavaScript>
function refreshWindow(){
window.opener.location.href="unidade.php";
self.close();
}

</script>
</head>
<body>
<?php
/**
* oauth-php: Example OAuth client for accessing Google Docs
*
* @author BBG
*
*
* The MIT License
*
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in
* all copies or substantial portions of the Software.
*
*/
ini_set("display_errors","On");

@session_start();

include_once "../oauth-php/library/OAuthStore.php";
include_once "../oauth-php/library/OAuthRequester.php";

//define("CONSUMER_KEY", "[CONSUMER_KEY]");
//define("CONSUMER_SECRET", "[CONSUMER_SECRET]");
//DESENV
define("CONSUMER_KEY", "eeusp");
define("CONSUMER_SECRET", "fyY31VRQWpCO90lCQ1PWR3KKdmfCfQaZiYbBN1LP");
// ambiente de teste labs.uspdigital
define("OAUTH_HOST", "https://uspdigital.usp.br/"); // PRODU��O
//define("OAUTH_HOST","https://dev.uspdigital.usp.br/");
//retirar este trecho em producao
$curl_options = array(CURLOPT_SSL_VERIFYPEER => false);

define("REQUEST_TOKEN_URL", OAUTH_HOST . "wsusuario/oauth/request_token");
define("AUTHORIZE_URL", OAUTH_HOST . "wsusuario/oauth/authorize");
define("ACCESS_TOKEN_URL", OAUTH_HOST . "wsusuario/oauth/access_token");
define('OAUTH_TMP_DIR', function_exists('sys_get_temp_dir') ? sys_get_temp_dir() :
realpath($_ENV["TMP"]));
// Init the OAuthStore
$options = array(
				'consumer_key' => CONSUMER_KEY,
				'consumer_secret' => CONSUMER_SECRET,
				'server_uri' => OAUTH_HOST,
				'request_token_uri' => REQUEST_TOKEN_URL,
				'authorize_uri' => AUTHORIZE_URL,
				'access_token_uri' => ACCESS_TOKEN_URL
				);
// Note: do not use "Session" storage in production. Prefer a database
// storage, such as MySQL.
OAuthStore::instance("Session", $options);
try
{
	// STEP 1: If we do not have an OAuth token yet, go get one
	if (empty($_GET["oauth_token"]))
	{
		// get a request token
		$tokenResultParams = OAuthRequester::requestRequestToken(CONSUMER_KEY, null, null, 'POST', null, $curl_options);
		$_SESSION["oauth_token"] = $tokenResultParams['token'];
		// redirect to the authorization page, they will redirect back
		// Informar o parametro callback_id se possuir mais de uma URL cadastrada
		//PRODU��O --> \\
		header("Location: " . AUTHORIZE_URL . "?oauth_token=" . $tokenResultParams['token']. "&callback_id=8");
		
		//DESENV
		//header("Location: " . AUTHORIZE_URL . "?oauth_token=" . $tokenResultParams['token']);
	} else {
		// STEP 2: Get an access token
		//request token
		$oauthToken = $_GET["oauth_token"];
		$oauthVerifier = $_GET["oauth_verifier"];
		try {
			OAuthRequester::requestAccessToken(CONSUMER_KEY, $oauthToken, 0, 'POST', $_GET,	$curl_options);
		}
		catch (OAuthException2 $e)
		{
			
			var_dump($e);
			// Something wrong with the oauth_token.
			// Could be:
			// 1. Was already ok
			// 2. We were not authorized
			return;
		}
		// recurso protegido
		$request = new OAuthRequester(OAUTH_HOST . "wsusuario/oauth/usuariousp", 'POST');
		$result = $request->doRequest(null, $curl_options);
		
		
		
		if ($result['code'] == 200) {
            setcookie("loginUSP",serialize($result['body']),time()+3600,"/");
            //echo $_COOKIE['loginUSP'];
			$dadosUsuario = json_decode($result['body']);
			//dd($dadosUsuario);
			$vinculo = isset($dadosUsuario->vinculo)?$dadosUsuario->vinculo:array();
			$vinculo = (array)$vinculo;
			
			foreach ($vinculo as $ddVinculo) {
				if ($ddVinculo->tipoVinculo=="SERVIDOR") {
					echo "<script>window.location.assign('http://143.107.172.21/geraBoletos/application');</script>";
					exit();
				}
			}
			
			echo "Sem Acesso";
			
		} else {
			echo 'Error';
		
		}
	}
} catch(OAuthException2 $e) {
	echo "OAuthException: " . $e->getMessage();
	var_dump($e);

}
?>
</body>
</html>