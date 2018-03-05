<?php
header("Content-Type: application/json");

echo '{ "userEmail": "'.$_POST['emailInput'].'", "testText": "howdy from the php code!"}';
//echo "howdy from the php code!"; 
//echo '{ "status": "OK","test":"howdy from the php code!" }';