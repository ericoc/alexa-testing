<?php

/*

Right now I have this set up so that when I say "alexa seizuretest", this skill is activated

like... "Alexa, tell seizuretest to log a bad seizure" or "Alexa, tell seizuretest I'm having a seizure"

*/

// Require the file with the AlexaOut function that builds a JSON response
require_once('alexa.func.php');

// The output is always JSON
header('Content-Type: application/json;charset=UTF-8');

// Get input, decode it, and 
$input = json_decode(file_get_contents("php://input"));
if ( (isset($input)) && (!empty($input)) ) {
	$loginput = json_encode($input, JSON_PRETTY_PRINT);
	$intent = $input->request->intent;
	$timestamp = date('Y-m-d g:i:s A', strtotime($input->request->timestamp));
}

// 
if ($intent->name == 'LogSeizure') {

	if ( (isset($intent->slots->Type->value)) && (!empty($intent->slots->Type->value)) ) {
		$seizuretype = $intent->slots->Type->value;
		$badseizuretypes = array('complex', 'complex partial', 'bad', 'terrible', 'major', 'awful');
	}

	// Be more re-assuring if it is a bad seizure
	// Right now, the LogSeizure "Type" will only be set when the intent indicated that the seizure was "bad"/"awful"/"terrible"/etc...
	$card_title = 'SeizureTest';
	if ( (isset($seizuretype)) && (isset($badseizuretypes)) && (in_array($seizuretype, $badseizuretypes)) ) {
		$output = AlexaOut('Okay, just relax and you will be okay!', $card_title, 'No worries - enhance your calm and you\'ll be a\'ight!');

	// Minor seizures are no big deal
	} else {
		$output = AlexaOut('Okay, you will be fine!', $card_title, 'You\'re fine dude!');
	}
	echo $output;
}

// Get background info on the date+time/IP/user-agent of the request for debugging
if (isset($_SERVER['REMOTE_ADDR'])) { $ip = $_SERVER['REMOTE_ADDR']; } else { $ip = null; }
if (isset($_SERVER['HTTP_USER_AGENT'])) { $uagent = $_SERVER['HTTP_USER_AGENT']; } else { $uagent = null; }

// Append debugging information to a file
$debugfile = __DIR__ . '/private/seizuretest-debug.txt';
$debugfh = fopen($debugfile, 'a+');
$logmessage = "When: $timestamp\nClient: $ip / $uagent\nINPUT:\n$loginput\n---\nOUTPUT:\n$output\n\n===\n\n";
fwrite($debugfh, $logmessage);
fclose($debugfh);
