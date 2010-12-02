<?php

$key = 'dj0yJmk9UnF1WWR4V3hyaFVWJmQ9WVdrOWFEbEdUbkJhTTJVbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD01Yg--';
$secret = '3182121ab849dad50c9945eb67e1b6ecf442a4ff';
$appid = 'h9FNpZ3e';

$debug = true;
$base_url = "http://ff.milesulrich.com/complete.php";
$request_token_endpoint = 'https://api.login.yahoo.com/oauth/v2/get_request_token';
$authorize_endpoint = 'https://api.login.yahoo.com/oauth/v2/request_auth';
$oauth_access_token_endpoint = 'https://api.login.yahoo.com/oauth/v2/get_token';

/***************************************************************************
 * Function: Run CURL
 * Description: Executes a CURL request
 * Parameters: url (string) - URL to make request to
 *             method (string) - HTTP transfer method
 *             headers - HTTP transfer headers
 *             postvals - post values
 **************************************************************************/
function run_curl($url, $method = 'GET', $headers = null, $postvals = null){
    $ch = curl_init($url);
    
    if ($method == 'GET'){
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($headers) {
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
    } else {
        $options = array(
            CURLOPT_HEADER => true,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_VERBOSE => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $postvals,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_TIMEOUT => 3
        );
        curl_setopt_array($ch, $options);
    }
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}
?>
