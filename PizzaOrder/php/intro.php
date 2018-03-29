<?php
/**
 * PizzaOrder Project - Intro.php
 *
 * This file runs the MySQL queries needed to test if a given user email
 * has been used in the history of our store.
 *
 * PHP version 7
 *
 * @category N/A
 * @package  N/A
 * @author   Aaron Thawe <aaron.thawe@gmail.com>
 * @author   Riley Sims <test@gmail.com>
 * @author   Sean Thorpe <test@gmail.com>
 * @author   Diana Orr <test@gmail.com>
 * @author   Scott Shields <test@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version  GIT: <git_id>
 * @link     N/A
 * @since    Class available since Release 1.0
 */
header("Content-Type: application/json");
$db_conn = connect_db();
//set up query to get cusID using email (Heredoc format)
$qry = <<<END
select cusID from customer where email = "{$_POST['emailInput']}";
END;
//run query
$rs = $db_conn->query($qry);
//if the result is a row from the customer table, set up second query.
//else return JSON with failure status
if ($rs->num_rows > 0) {
    $id = array("status" => "OK");
    while ($row = $rs->fetch_assoc()) {
        array_push($id, $row);
    }
    //obtain customer ID from result of first query
    $customerID = $id[0]['cusID'];
    //echo json_encode($id);
    //LOOKS LIKE: {"status":"OK","0":{"cusID":"2"}}

    //Set up second query to obtain address info
    $qry2 = <<<END2
select distinct cusID, addr, city, prov, post,
appt from address where cusID = {$customerID};
END2;
    //run query
    $rs2 = $db_conn->query($qry2);
    if ($rs2->num_rows > 0) {
        $addresses = array("status" => "OK");
        $addresses['addresses'] = array();
        while ($row2 = $rs2->fetch_assoc()) {
            array_push($addresses['addresses'], $row2);
        }
        //output address data to JavaScript code
        echo json_encode($addresses);
        /*LOOKS LIKE: {"status":"OK",
        "addresses":[{"addr":"12 Electric Avenue",
        "city":"Austin","prov":"Texas","post":"N6H 2B5","appt":null},
        {"addr":"98 Crowd Street","city":"Austin",
        "prov":"Texas","post":"N6H 2B5","appt":"820"}]}*/
    }
} else {
    //Sent back default if new email
    echo '{ "status": "None" }';
}
disconnect_db($db_conn);

/**
 * Connect to database
 *
 * @category N/A
 * @package  N/A
 * @author   Aaron Thawe <aaron.thawe@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: @package_version@
 * @return   db_conn
 */
function connect_db()
{
    $db_conn = new mysqli('localhost', 'lamp1user', '!Lamp1!', 'group5');
    if ($db_conn->connect_errno) {
        printf(
            "Could not connect to database server\n Error: "
            .$db_conn->connect_errno ."\n Report: "
            .$db_conn->connect_error."\n"
        );
        die;
    }
    return $db_conn;
}

/**
 * Disconnect from database
 *
 * @category N/A
 * @package  N/A
 * @author   Aaron Thawe <aaron.thawe@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: @package_version@
 * @return   none
 */
function disconnect_db($db_conn)
{
    $db_conn->close();
}