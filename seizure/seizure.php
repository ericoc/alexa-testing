<?php

require_once('../alexa.func.php');
require_once('seizure.users.php');
require_once('seizure.events.php');

// Get the input from Alexa and JSON-decode it
$input = json_decode(file_get_contents("php://input"));

// No input is probably completely meaningless, but tell how to track a seizure anyways
if ( (!isset($input->session->user->userId)) || (empty($input->session->user->userId)) ) {
	$message = 'Sorry, but please say, "Tell SeizureTracker to track a seizure", if you would like to track a seizure.';

// Otherwise, continue with finding the user and handling any intent
} else {

	// Set MySQL database credentials and connect to MySQL
	$db_hostname = 'localhost';
	$db_username = $db_database = 'seizuretest';
	require_once('.seizure.dbpassword.php');
	$db_link = new PDO("mysql:host=$db_hostname;dbname=$db_database", $db_username, $db_password);

	// Get user ID using Alexa ID
	$user_id = get_user($input->session->user->userId, $db_link);

	// Continue if user ID was found
	if (is_numeric($user_id)) {

		// Tell the user how to track a seizure if there was no intent determined
		if ( (!isset($input->request->intent)) || ($input->request->type === 'LaunchRequest') ) {
			$message = 'Sorry, but please say, "Tell SeizureTracker to track a seizure", if you would like to track a seizure.';

		// Otherwise, continue with handling the event/seizure
		} else {

			// Handle the event based on the intent sent from Alexa
			$handle_seizure = handle_seizure($db_link, $user_id, $input->request->intent);



			// Set the message awkwardly
			// (TODO: find a better way of doing this)
			if ( (isset($handle_seizure)) && (is_string($handle_seizure)) ) {
				$message = $handle_seizure;

			// Otherwise there was an error adding or finding/updating the seizure
			} else {
				$message = 'Sorry. There was an unknown error.';
			}
		}

	// Otherwise, there was an error finding or adding the user
	} else {
		$message = "Sorry. There was an error with your user account.";
	}

	// Disconnect from MySQL
	$db_link = null;

} // End input check

// The output is always JSON
header('Content-Type: application/json;charset=UTF-8');
$out = AlexaOut($message, 'SeizureTest', $message);
echo "$out\n";
