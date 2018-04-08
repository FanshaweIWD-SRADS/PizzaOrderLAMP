<?php
header("Content-Type: application/json");
//add validation here later on
$address = array();
$address['status'] = "YES";

//Probably more validation should be added. 

if(ctype_alnum($_POST['addInput']) && ctype_alnum($_POST['postInput']) && ctype_alpha($_POST['cityInput']) && ctype_alpha($_POST['provInput'])) {
    $address['addr'] = $_POST['addInput'];
    $address['city'] = $_POST['cityInput'];
    $address['prov'] = $_POST['provInput'];
    $address['post'] = $_POST['postInput'];
    //Then check for apartment info
    if(!empty($_POST['apptInput'])) {
        if(ctype_alnum($_POST['apptInput'])) {
            $address['appt'] = $_POST['apptInput'];
        } else {
            $address['status'] = "Invalid";
        }
    }
} else {
    $address['status'] = "Invalid";
}
//return json
echo json_encode($address);
