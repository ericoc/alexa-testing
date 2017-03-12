<?php

// Create a big JSON response for Alexa
function AlexaOut ($speech, $cardtitle, $cardphrase) {

	// Create the outputSpeech array (This is what Alexa says in response)
	$outputspeech = array('type' => 'PlainText', 'text' => $speech);

	// Create the card array (This is shown at alexa.amazon.com and within the app)
	$card = array( 'type' => 'Simple', 'title' => $cardtitle, 'content' => $cardphrase);

	// Create a null (unused/empty) reprompt array
	// (This is used for follow up respones in a proper conversation... I'm not there yet)
	$reprompt = array('outputSpeech' => array('type' => 'PlainText', 'text' => null));

	// Create final response array combining above arrays before it gets turned in to JSON
	$response = array('version' => '0.1', 'sessionAttributes' => array(), 'response' => array('outputSpeech' => $outputspeech, 'card' => $card, 'reprompt' => $reprompt), 'shouldEndSession' => true);

	// Turn the final response array in to JSON and send it back to Amazon/Alexa
	$output = json_encode($response, JSON_PRETTY_PRINT);
	return $output;
}
