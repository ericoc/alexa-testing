## (Testing) Custom Alexa Skill for Seizure Tracking

The following documentation should explain how to set this up as a custom Alexa skill!

To get started, you will want to visit this [AWS Alexa Developer](https://developer.amazon.com/edw/home.html#/) page, click "Get Started >" under "Alexa Skills Kit", then click the "Add a New Skill" button in the top right corner.

---

### Skill Information

You want to create a "Custom" skill named any thing that you would like, and the invocation name can probably either be `seizuretest` or `seizuretracker`:

![Alexa Skill Information Screenshot](https://raw.githubusercontent.com/ericoc/alexa-testing/master/seizure/images/skill-info.png "Alexa Skill Information Screenshot")

---

### Interaction Model

These settings set how users interact with Alexa based on their "intent". This is where the magic happens.

#### Intent Schema

For the "Intent Schema", simply paste the JSON contents of the [seizure-intents.json file from this repository](seizure-intents.json) in to the textarea.

#### Custom Slot Types

Create the custom slot types as defined within the [slot-types.txt file from this repository](slot-types.txt) in this repository.

#### Sample Utterances

Additionally, enter the sample utterances from the [sample-utterances.txt file from this repository](sample-utterances.txt) in this repository.

---

### Configuration

Within the "Configuration" page, select "HTTPS" as the Service Endpoint Type using North America, the following URL:

	https://alexa.ericoc.com/seizure/seizure.php

which is simply the code from [here](seizure.php)

...and select "No" regarding Account Linking (for now).

![Alexa Skill Configuration Screenshot](https://raw.githubusercontent.com/ericoc/alexa-testing/master/seizure/images/configuration.png "Alexa Skill Configuration Screenshot")

---

### SSL Certificate

Select the second option of:

`My development endpoint is a sub-domain of a domain that has a wildcard certificate from a certificate authority`

![Alexa Skill SSL Certificate Screenshot](https://raw.githubusercontent.com/ericoc/alexa-testing/master/seizure/images/ssl-certificate.png "Alexa Skill SSL Certificate Screenshot")

---

### Service Simulator

The best way to test for now is to enter a phrase such as the following in to the "Enter Utterance" field:

	I'm having a seizure

...which should hopefully return a valid JSON response that you can listen to within the browser!

This skill should also be available locally on your Echo (dot) device which you can confirm by visiting [alexa.amazon.com](http://alexa.amazon.com/spa/index.html#skills/your-skills/?ref-suffix=ysa_gw) and searching for the name of the skill that you set originally.
