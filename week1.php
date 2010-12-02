<?php

require_once "OAuth.php";       //oauth library
require_once "common.php";      //common functions and variables

$which_week = $_GET['week'] ? $_GET['week'] : 1;
$which_team = $_GET['team'] ? $_GET['team'] : 3;

//create required consumer variables
$test_consumer = new OAuthConsumer($key, $secret, NULL);

//parse and generate access consumer from values
$oauth_token = $_COOKIE['oauthT'];
$oauth_token_secret = $_COOKIE['oauthTS'];

$access_consumer = new OAuthConsumer($oauth_token, $oauth_token_secret, NULL);

$url = sprintf("http://fantasysports.yahooapis.com/fantasy/v2/team/242.l.572253.t.{$which_team}/roster;week={$which_week};/players/stats;type=week;week={$which_week};?format=json");

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

$roster = json_decode($resp, TRUE);
//print '<pre>'.print_r($your_leagues, TRUE).'</pre>';
//

$playing = array();
$benched = array();

$positions = array("QB", "RB", "WR", "TE", "K", "DEF", "W/R");

$ideals = array(
  "QB" => 0,
  "TE" => 0,
  "K" => 0,
  "DEF" => 0,
);

$actual_score = 0;

foreach ($roster['fantasy_content']['team'][1]['roster'][0]['players'] as $count => $player) {
  $score = $player['player'][3]['player_points']['total'];
  if (in_array($player['player'][1]['selected_position'][1]['position'], $positions)) {
    $playing[] = $player['player'][0][2]['name']['full'].": $score";
    $actual_score += $score;
  } else if ($player['player'][1]['selected_position'][1]['position'] == 'BN') {
    $benched[] = $player['player'][0][2]['name']['full'].": $score";
  }
  $the_id = $player['player'][0][1]['player_id'];
  $elig = '';
  if (is_array($player['player'][0])) {
    foreach ($player['player'][0] as $count => $stat) {
      if (isset($stat['eligible_positions'])) {
        $elig = $stat['eligible_positions'];
      }
    }
  }
  if ($elig) {
    foreach ($elig as $count => $position) {
      switch($position['position']) {
        case 'QB':
          if ($score > $ideals['QB']) {
            $ideals['QB'] = $score;
          }
          break;
        case 'TE':
          if ($score > $ideals['TE']) {
            $ideals['TE'] = $score;
          }
          break;
        case 'K':
          if ($score > $ideals['K']) {
            $ideals['K'] = $score;
          }
          break;
        case 'DEF':
          if ($score > $ideals['DEF']) {
            $ideals['DEF'] = $score;
          }
          break;
        case 'RB':
          $rbs["$the_id"] = $score;
          break;
        case 'WR':
          $wrs["$the_id"] = $score;
          break;
        case 'W/R':
          $both["$the_id"] = $score;
          break;
      }
    }
  }
}

arsort($rbs);
arsort($wrs);
arsort($both);

$wr_total = 0;
$rb_count = 0;
$wr_count = 0;
foreach ($both as $id => $player) {
  if ($rb_count + $wr_count == 5) {
    break;
  }
  if (isset($rbs[$id]) && $rb_count < 3) {
    $rb_count++;
    $wr_total += $player;
  } else if (isset($wrs[$id]) && $wr_count < 3) {
    $wr_count++;
    $wr_total += $player;
  }
}

$ideal_total = $wr_total;
$ideal_total += $ideals['QB'];
$ideal_total += $ideals['TE'];
$ideal_total += $ideals['K'];
$ideal_total += $ideals['DEF'];

print "<h1>Benched</h1><ul style=\"list-style: none outside none;margin:0;padding:0;\"><li>".join($benched, '</li><li>')."</li></ul>";
print "<h1>Playing</h1><ul style=\"list-style: none outside none;margin:0;padding:0;\"><li>".join($playing, '</li><li>')."</li></ul>";
print "<h1>Actual Score</h1><p>$actual_score</p>";
print "<h1>Ideal Score</h1><p>$ideal_total</p>";
print "<h1>Dump</h1><pre>".print_r($roster, true)."</pre>";

?>
