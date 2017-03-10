<?php

/*

Right now I have this set up so that when I say "alexa fruit", it responds with "XYZ are a fruit" where XYZ is one of the fruits within the $fruits array!

*/

// The output is always JSON
header('Content-Type: application/json;charset=UTF-8');

// Create an array of fruits and pick one of the fruits to respond with
$fruits = array('pineapples', 'apples', 'oranges', 'mangoes', 'pears', 'tomatoes', 'strawberries', 'bananas', 'peaches', 'grapes', 'cherries', 'grapefruits');
shuffle($fruits);
$fruit = $fruits[0];
$phrase = "$fruit are a fruit.";

// Create the outputSpeech array
// (this is what alexa says in response)
$outputspeech = array(
			'type'	=>	'PlainText',
			'text'	=>	$phrase
		);

// Create the card array
// (this is shown at alexa.amazon.com and within the app)
$card = array(
		'type'		=>	'Simple',
		'title'		=>	'Fruit',
		'content'	=>	$phrase
	);

// Create a null (unused/empty) reprompt array
// (this is used for follow up respones in a proper conversation... I'm not there yet)
$reprompt = array(
		'outputSpeech'	=>	array('type' => 'PlainText', 'text' => null)
	);

// Create final response array combining above arrays, turn it in to JSON, and print it
// (this is the final JSON returned in response to the JSON request)
$response = array(
		'version'		=>	'0.1',
		'sessionAttributes'	=>	array(),
		'response'		=>	array('outputSpeech' => $outputspeech, 'card' => $card, 'reprompt' => $reprompt),
		'shouldEndSession'	=>	true
	);
$output = json_encode($response, JSON_PRETTY_PRINT);
echo $output;

// Get background info on the date+time/IP/user-agent of the request for debugging
$when = date('Y-m-d g:i:s A');
if (isset($_SERVER['REMOTE_ADDR'])) { $ip = $_SERVER['REMOTE_ADDR']; } else { $ip = null; }
if (isset($_SERVER['HTTP_USER_AGENT'])) { $uagent = $_SERVER['HTTP_USER_AGENT']; } else { $uagent = null; }

// Get input, decode it, and break off the session ID as an example of how to parse input
$input = json_decode(file_get_contents("php://input"));
if (isset($input)) {
	$sessionid = $input->session->sessionId;
} else {
	$sessionid = null;
}

// Append debugging information to a file
$debugfile = __DIR__ . '/private/fruit-debug.txt';
$debugfh = fopen($debugfile, 'a+');
fwrite($debugfh, "$when / $ip / $uagent / $sessionid / $fruit\n$output\n\n==========\n\n");
fclose($debugfh);
