<?php
header("Content-Type: application/json");
//add validation here later on
$address = array();
$address['status'] = "YES";

//Probably more validation should be added. 

if(isset($_POST['addInput']) && isset($_POST['postInput']) && isset($_POST['cityInput']) 
&& isset($_POST['provInput']) && isset($_POST['phoneInput']) && isset($_POST['nameInput'])) {
    if(preg_match("/\A[(]?[0-9]{3}[)]?[ ,-]?[0-9]{3}[ ,-]?[0-9]{4}\z/", $_POST['phoneInput'])) {
        $address['addr'] = $_POST['addInput'];
        $address['city'] = $_POST['cityInput'];
        $address['prov'] = $_POST['provInput'];
        $address['phone'] = $_POST['phoneInput'];
        $address['post'] = $_POST['postInput'];
        $address['name'] = $_POST['nameInput'];
        //Then check for apartment info
        if(!empty($_POST['apptInput'])) {
            $address['appt'] = $_POST['apptInput'];
        }
    } else {
        $address['status'] = "Invalid";
    }
} else {
    $address['status'] = "Invalid";
}
//return json
echo json_encode($address);
