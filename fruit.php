<?php

/*

Right now I have this set up so that when I say "alexa fruit", it responds with "XYZ are a fruit" where XYZ is one of the fruits within the $fruits array!

*/

require_once('alexa.func.php');

// The output is always JSON
header('Content-Type: application/json;charset=UTF-8');

// Create an array of fruits and pick one of the fruits to respond with
$fruits = array('pineapples', 'apples', 'oranges', 'mangoes', 'pears', 'tomatoes', 'strawberries', 'bananas', 'peaches', 'grapes', 'cherries', 'grapefruits');
shuffle($fruits);
$fruit = $fruits[0];
$phrase = "$fruit are a fruit.";
$out = AlexaOut($phrase, 'Fruit', $phrase);
echo $out;
