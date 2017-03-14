## (Testing) Custom Alexa Skill for Seizure Tracking

The following documentation should explain how to set this up as a custom Alexa skill!

To get started, you will want to visit this [AWS Alexa Developer](https://developer.amazon.com/edw/home.html#/) page, click "Get Started >" under "Alexa Skills Kit", then click the "Add a New Skill" button in the top right corner.

---

### Skill Information

You want to create a "Custom" skill named any thing that you would like, and the invocation name can probably either be `seizuretest` or `seizuretracker`:

![Alexa Skill Information Screenshot](https://raw.githubusercontent.com/ericoc/alexa-testing/master/seizure/skill-info.png "Alexa Skill Information Screenshot")

---

### Interaction Model

These settings set how users interact with Alexa based on their "intent":

![Alexa Skill Interaction Model Screenshot](https://raw.githubusercontent.com/ericoc/alexa-testing/master/seizure/interaction-model.png "Alexa Skill Interaction Model Screenshot")

#### Intent Schema

Paste the following in the intent schema, which simply creates a `LogSeizure` using the three "slots" named `Type`, `Action`, and `Thing` that are defined later as well as the `UpdateSeizure` intent which will also use the `Thing` slot:

	{
	    "intents": [
	    {
	        "intent": "LogSeizure",
	        "slots": [
	            {
	                "name": "Type",
	                "type": "BAD_SEIZURE_TYPES"
	            },
	            {
	                "name": "Action",
	                "type": "SEIZURE_ACTION"
	            },
	            {
	                "name": "Thing",
	                "type": "THING"
	            }
	        ]
	    },
	    {
	        "intent": "UpdateSeizure",
	        "slots": [
	            {
	                "name": "Thing",
	                "type": "THING"
	            }
	        ]
	    }
	    ]
	}

---

#### Custom Slot Types

Create these three (3) custom slot types:

* Name/Type: `BAD_SEIZURE_TYPES`

  Value:

		complex
		complex partial
		bad
		terrible
		major
		awful

* Name/Type: `SEIZURE_ACTION`

  Value:

		add
		log
		track
		record
		store
		report
		is having
		I'm having
		I am having

* Name/Type: `THING`

  Value:

		event
		seizure

#### Sample Utterances

Enter the following sample utterances:

	LogSeizure {Action} this {Type} {Thing}
	LogSeizure {Action} my {Type} {Thing}
	LogSeizure {Action} a {Type} {Thing}
	LogSeizure {Action} {Type} {Thing}
	UpdateSeizure {Thing} is over
	UpdateSeizure {Thing} ended
	UpdateSeizure {Thing} has ended
	UpdateSeizure {Thing} stopped
	UpdateSeizure {Thing} has stoppped

---

### Configuration

Within the "Configuration" page, select "HTTPS" as the Service Endpoint Type using North America, and I've been using the following URL:

	https://alexa.ericoc.com/seizure/seizure.php

which is simply the code from [here](seizure.php)

...and select "No" regarding Account Linking.

![Alexa Skill Configuration Screenshot](https://raw.githubusercontent.com/ericoc/alexa-testing/master/seizure/configuration.png "Alexa Skill Configuration Screenshot")

---

### SSL Certificate

Select the second option of:

`My development endpoint is a sub-domain of a domain that has a wildcard certificate from a certificate authority`

![Alexa Skill SSL Certificate Screenshot](https://raw.githubusercontent.com/ericoc/alexa-testing/master/seizure/ssl-certificate.png "Alexa Skill SSL Certificate Screenshot")

---

### Service Simulator

The best way to test for now is to enter a phrase such as the following in to the "Enter Utterance" field:

	I'm having a seizure

...which should hopefully return a valid JSON response that you can listen to within the browser!

This skill should also be available locally on your Echo (dot) device which you can confirm by visiting [alexa.amazon.com](http://alexa.amazon.com/spa/index.html#skills/your-skills/?ref-suffix=ysa_gw) and searching for the name of the skill that you set originally.
