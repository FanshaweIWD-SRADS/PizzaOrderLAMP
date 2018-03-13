<?php
header("Content-Type: application/json");
//add validation here later on
$address = array();
$address['addr'] = $_POST['addInput'];
$address['city'] = $_POST['cityInput'];
$address['prov'] = $_POST['provInput'];
$address['post'] = $_POST['postInput'];
if(isset($_POST['apptInput'])){
    $address['appt'] = $_POST['apptInput'];
}

echo json_encode($address);
