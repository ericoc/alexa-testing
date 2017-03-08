<?php

/*

Right now I have this set up so that when I say "alexa seizuretest", this skill is activated

... "Alexa, tell seizuretest to log a bad seizure" will send a request containing:

"request": {
        "type": "IntentRequest",
        "requestId": "amzn1.echo-api.request.a0-idk-if-this-is-secret-so-im-removing-it-z123",
        "timestamp": "2017-03-08T23:13:32Z",
        "locale": "en-US",
        "intent": {
            "name": "LogSeizure",
            "slots": {
                "Type": {
                    "name": "Type",
                    "value": "bad"		# This is only set when the intent indicated that the seizure was "bad"/"awful"/"terrible"/etc.
                },
                "Date": {
                    "name": "Date"
                }
            }
        }
    }

*/

// The output is always JSON
header('Content-Type: application/json;charset=UTF-8');

// Get input, decode it, and break off the session ID as an example of how to parse input
$input = json_decode(file_get_contents("php://input"));
if (isset($input)) {
	$intent = $input->request->intent;
}

if ($intent->name == 'LogSeizure') {

	// Be more re-assuring if it is a bad seizure
	// Right now, the LogSeizure "Type" will only be set when the intent indicated that the seizure was "bad"/"awful"/"terrible"/etc...
	if (isset($intent->slots->Type->value)) {
		$phrase = 'o.k., just relax and you will be okay!';

	// Minor seizures are no big deal
	} else {
		$phrase = 'o.k., you will be fine!';
	}
}

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
		'title'		=>	'SeizureTest',
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

// Append debugging information to a file
$debugfile = __DIR__ . '/private/seizuretest-debug.txt';
$debugfh = fopen($debugfile, 'a+');
fwrite($debugfh, "$when / $ip / $uagent / $phrase\n\n==========\n\n");
fclose($debugfh);
