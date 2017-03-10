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

Paste the following in the intent schema, which simply creates a `LogSeizure` intent with two "slots" named `BAD_SEIZURE_TYPES` and `SEIZURE_ACTION `:

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
	          "name": "Date",
	          "type": "AMAZON.DATE"
	        }
	      ]
	    }
	  ]
	}

---

#### Custom Slot Types


Create these two (2) custom slot types:

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
		I'm having
		I am having


#### Sample Utterances

Enter the following sample utterances:

	LogSeizure {Action} this {Type} seizure
	LogSeizure {Action} my {Type} seizure
	LogSeizure {Action} a {Type} seizure
	LogSeizure {Action} {Type} seizure
	
---

### Configuration

Within the "Configuration" page, select "HTTPS" as the Service Endpoint Type using North America, and I've been using the following URL:

	https://alexa.ericoc.com/seizure.php

which is simply the code from [here](https://raw.githubusercontent.com/ericoc/alexa-testing/master/seizure.php)

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

...which should hopefully return a valid JSON response that you can listen to within the browser:

![Alexa Skill Service Simulator Screenshot](https://raw.githubusercontent.com/ericoc/alexa-testing/master/seizure/service-simulator.png "Alexa Skill Service Simulator Screenshot")

This skill should also be available locally on your Echo (dot) device!
