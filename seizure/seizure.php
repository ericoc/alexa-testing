<?php

require_once('../alexa.func.php');
require_once('seizure.users.php');
require_once('seizure.events.php');

// Get the input from Alexa and JSON-decode it
$input = json_decode(file_get_contents("php://input"));

// Bail if there was no input from Alexa and
if ( (!isset($input->session->user->userId)) || (empty($input->session->user->userId)) ) {
	$message = 'No input';

} else {

	// Connect to MySQL and choose database

	// Set database information/credentials and connect to MySQL
	$db_hostname = 'localhost';
	$db_username = $db_database = 'seizuretest';
	require_once('.seizure.dbpassword.php');
	$db_link = new PDO("mysql:host=$db_hostname;dbname=$db_database", $db_username, $db_password);

	// Get user ID using Alexa ID
	$user_id = get_user($input->session->user->userId, $db_link);

	// Continue if user ID was found
	if (is_numeric($user_id)) {

		// Handle the event based on the intent sent from Alexa
		$handle_seizure = handle_seizure($db_link, $user_id, $input->request->intent);

		// The seizure was successfully tracked
		if ( (isset($handle_seizure)) && (is_numeric($handle_seizure)) ) {
			$message = 'Okay. The seizure has been tracked!';

		// Set the message awkwardly if we did not get an integer back, meaning an existing seizure got marked as over
		// (TODO: find a better way of doing this)
		} elseif ( (isset($handle_seizure)) && (is_string($handle_seizure)) ) {
			$message = $handle_seizure;

		// Otherwise there was an error adding or finding/updating the seizure
		} else {
			$message = 'Sorry, but there was an error tracking the seizure!';
		}

	// Otherwise, there was an error finding or adding the user
	} else {
		$message = "Error with user!";
	}

	// Disconnect from MySQL
	$db_link = null;

} // End input check

// The output is always JSON
header('Content-Type: application/json;charset=UTF-8');
$out = AlexaOut($message, 'SeizureTest', $message);
echo "$out\n";
