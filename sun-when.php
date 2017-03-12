<?php

/*

Unfortunately, it is not currently possible to get the device location from within a Flash Briefing skill.

This makes it difficult (impossible?) to have this work for just any one by automatically using their location,
so I have it hard-coded to Philadelphia right now :(

In any case, http://sunrise-sunset.org is awesome!

*/

// Require the file with the BriefingOut function that builds a JSON response
require_once('alexa.func.php');

// Create a function to get sunrise and sunset times from an API (thanks http://sunrise-sunset.org/ !)
function getSunTimes ($latitude, $longitude, $when = 'today') {

	// Create the full URL based on the lat/long/date
	$url = "http://api.sunrise-sunset.org/json?lat=$latitude&lng=$longitude&date=$when&formatted=0";

	// Hit the API to get JSON of the sunrise and sunset back as well as determine the HTTP response code
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, $url);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_USERAGENT, 'Sunrise-Sunset Flash Briefing Alexa Skill - https://github.com/ericoc/alexa-testing/blob/master/sun-when.php');
	curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($c, CURLOPT_TIMEOUT, 2);
	$r = curl_exec($c);
	$code = curl_getinfo($c, CURLINFO_HTTP_CODE);
	curl_close($c);

	// Decode the JSON response of the API
	$api_response = json_decode($r);

	// Proceed with returning API response only if the API responded with a HTTP 200 code and "OK" status
	if ( (isset($code)) && ($code === 200) && (isset($api_response->status)) && ($api_response->status === 'OK') ) {
		return $api_response->results;

	// Otherwise, return null if the API hit did not work for any reason
	} else {
		return null;
	}
}

// Create a function to generate a phrase detailing when sunrise and sunset are occurring or have occurred
function createPhrase ($input) {

	// Get current UNIX timestamp
	$current_time = time();

	// Pull out sunrise and sunset UNIX timestamps from object passed in to the function
	$sunrise = strtotime($input->sunrise);
	$sunset = strtotime($input->sunset);

	// Start the phrase with the time of sunrise, determining whether it is in the past or future
	$phrase = 'Sunrise ';
	if ($current_time < $sunrise) {
		$phrase .= 'will be';
	} else {
		$phrase .= 'was';
	}
	$phrase .= ' at ' . date('g:i A', $sunrise) . ' and sunset ';

	// End the phrase with the time of sunset, determining whether it is in the past or future
	if ($current_time <= $sunset) {
		$phrase .= 'will be';
	} else {
		$phrase .= 'was';
	}
	$phrase .= ' at ' . date('g:i A', $sunset) . ' today, ' . date('l, F jS') . '.';

	// Return the phrase that was built
	return $phrase;
}

// Get the sunrise and sunset times in Philadelphia, PA, USA for today
$sun_times = getSunTimes('39.9526', '-75.1652', 'today');

// Continue with building a phrase to have Alexa speak if the API did not fail
if ($sun_times != null) {

	// Build a phrase of when the sun is rising/setting to send back to Alexa
	$phrase = createPhrase($sun_times);

// Bail if the API did not respond or responded without "OK" status
} else {
	$phrase = 'Unfortunately, the sunrise and sunset times could not be determined.';
}

// Get the date in ISO format, generate a UID, and set the title for Alexa
$update_date = date('c', strtotime(date('Ymd')));
$uid = uniqid('sunrise-sunset-', true);
$title = 'Sunrise and Sunset Times (Flash Briefing)';

// Finally, generate and return the JSON
header('Content-Type: application/json;charset=UTF-8');
$out = BriefingOut($uid, $update_date, $title, $phrase, 'http://sunrise-sunset.org/');
echo $out;
