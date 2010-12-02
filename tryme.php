<?php

require_once('yos/lib/Yahoo.inc');

// debug settings
error_reporting(E_ALL | E_NOTICE); # do not show notices as library is php4 compatable
ini_set('display_errors', true);

YahooLogger::setDebug(true);
YahooLogger::setDebugDestination('LOG');

$sessionStore = new CookieSessionStore();

define('OAUTH_CONSUMER_KEY', 'dj0yJmk9UnF1WWR4V3hyaFVWJmQ9WVdrOWFEbEdUbkJhTTJVbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD01Yg--');
define('OAUTH_CONSUMER_SECRET', '3182121ab849dad50c9945eb67e1b6ecf442a4ff');
//define('OAUTH_DOMAIN', 'ff.milesulrich.com');
define('OAUTH_APP_ID', 'h9FNpZ3e');

$session = YahooSession::requireSession(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET, OAUTH_APP_ID);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js"></script>
<meta charset="utf-8" />
<title>Fantasy Football</title>

<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<!--[if lte IE 7]>
<script src="js/IE8.js" type="text/javascript"></script><![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" media="all" href="css/ie6.css"/><![endif]-->
</head>

<body>
<p>Made it</p>
<div id="results">
</div>
<script type="text/javascript" src="ff.js"></script>
</body>
</html>
