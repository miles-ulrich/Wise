<?php

require_once "OAuth.php";       //oauth library
require_once "common.php";      //common functions and variables

$debug = FALSE;

//get request token params from cookie and parse values
$request_cookie = $_COOKIE["requestToken"];
parse_str($request_cookie);

//create required consumer variables
$test_consumer = new OAuthConsumer($key, $secret, NULL);
$req_token = new OAuthConsumer($token, $token_secret, NULL);
$sig_method = new OAuthSignatureMethod_HMAC_SHA1();

//exchange authenticated request token for access token
$params = array('oauth_verifier' => $_GET['oauth_verifier']);
$acc_req = OAuthRequest::from_consumer_and_token($test_consumer, $req_token, "GET", $oauth_access_token_endpoint, $params);
$acc_req->sign_request($sig_method, $test_consumer, $req_token);
$access_ret = run_curl($acc_req->to_url(), 'GET');

//if access token fetch succeeded, we should have oauth_token and oauth_token_secret
//parse and generate access consumer from values
$access_token = array();
parse_str($access_ret, $access_token);
// store oauth access key info
setcookie("oauthT", $access_token['oauth_token']);
setcookie("oauthTS", $access_token['oauth_token_secret']);

$access_consumer = new OAuthConsumer($access_token['oauth_token'], $access_token['oauth_token_secret'], NULL);

$url = sprintf("http://fantasysports.yahooapis.com/fantasy/v2/users;use_login=1;/games/leagues?format=json");

//build and sign request
$request = OAuthRequest::from_consumer_and_token($test_consumer, 
	$access_consumer, 
	'GET',
	$url);

$request->sign_request(new OAuthSignatureMethod_HMAC_SHA1(),
	$test_consumer, 
	$access_consumer
);

//define request headers
$headers = array("Accept: application/json");
$headers[] = $request->to_header();
$headers[] = "Content-type: application/json";

$resp = run_curl($url, 'GET', $headers);

//if debug mode, dump signatures & headers 
if ($debug){
  $debug_out = array(
    '_GET' => $_GET,
    'Request Cookie' => $request_cookie,
    'Access token' => $access_token,
    'URL'      => $url,
    'headers'  => $headers,
  );
    

}


$your_leagues = json_decode($resp, TRUE);
//print '<pre>'.print_r($your_leagues, TRUE).'</pre>';

?>

<h2>Your Leagues</h2>

<pre>
<?php
/*
foreach ($your_leagues->fantasy_content->users[0] as $count => $stuff) {
  print '<pre>'.$count.'# '.print_r($stuff, TRUE).'</pre>';
}
 */
foreach ($your_leagues['fantasy_content']['users'][0]['user'][1]['games'] as $key => $game) { // Browse through player's games
  if ($game['game'][0]['game_key'] == 242) { // Filter on current football
    foreach ($game['game'][1]['leagues'][0] as $key => $league) { // each league
      print "<p>League: <a href=\"/week1.php\">{$league[0]['name']}</a></p>";
    }
  }
}
?>
</pre>
