<?php
/**
 * PizzaOrder Project - address.php
 *
 * This file runs the MySQL queries needed to test if a user entered the
 * correct and required values in the address forms
 *
 * PHP version 7
 *
 * @category N/A
 * @package  N/A
 * @author   Aaron Thawe <aaron.thawe@gmail.com>
 * @author   Riley Sims <rileytsims@hotmail.com>
 * @author   Sean Thorpe <seanthorpe95@gmail.com>
 * @author   Diana Orr <dianaeileen89@gmail.com>
 * @author   Scott Shields <sjshields87@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version  GIT: <git_id>
 * @link     N/A
 * @since    Class available since Release 1.0
 */
header("Content-Type: application/json");
//add validation here later on
$address = array();
$address['status'] = "YES";

//Probably more validation should be added. 

if(checker($_POST['addInput'])
    && checker($_POST['postInput'])
    && checker($_POST['cityInput'])
    && checker($_POST['provInput'])
    && checker($_POST['phoneInput'])
    && checker($_POST['nameInput'])) {
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

function checker($val) {
    return (isset($val) && $val != "");
}
