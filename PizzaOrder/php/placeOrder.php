<?php
/**
 * PizzaOrder Project - placeOrder.php
 *
 * This file runs the MySQL queries needed to save user input to the database
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
//STILL TO BE COMPLETED!
header("Content-Type: application/json");
$db_conn = connect_db();
//set up query to get cusID using email (Heredoc format)
$qry = <<<END
select cusID from customer where cusID = "{$currentOrder->uID}";
END;
//run query
$rs = $db_conn->query($qry);
//if the result is a row from the customer table, set up second query.
//else return JSON with failure status
if ($rs->num_rows = 0) {
    // $id = array("status" => "OK");
    // while ($row = $rs->fetch_assoc()) {
    //     array_push($id, $row);
    // }
    // //obtain customer ID from result of first query
    // $customerID = $id[0]['cusID'];
    //echo json_encode($id);
    //LOOKS LIKE: {"status":"OK","0":{"cusID":"2"}}

    //Set up second query to obtain address info
    $qry2 = <<<END2
insert into customer (cusID, name, email)
values ({$currentOrder->uID}, {$currentOrder->name}, {$currentOrder->email});
END2;
    //run query
    $rs2 = $db_conn->query($qry2);
}
if($currentOrder->addressInfo == "new") {
    //insert address
    if(isset($currentOrder->address->appt)){
        $qry3 = <<<END3
insert into address (cusID, addr, city, prov, post, phone, appt)
values ($currentOrder->uID, $currentOrder->address[addr], $currentOrder->address[city]
, $currentOrder->address[prov], $currentOrder->address[post], 
$currentOrder->address[phone], $currentOrder->address[appt]);
END3;
    } else {
        $qry3 = <<<END3
insert into address (cusID, addr, city, prov, post, phone)
values ($currentOrder->uID, $currentOrder->address[addr], $currentOrder->address[city]
, $currentOrder->address[prov], $currentOrder->address[post], 
$currentOrder->address[phone]);
END3;
    }
}
//run query
$rs3 = $db_conn->query($qry3);
//select addressId
$qry4 = <<<END4
select addrID from address where cusID = "{$currentOrder->uID}";
END4;
//run query
$rs4 = $db_conn->query($qry4);
$addrID = $rs4->fetch_assoc();
//insert order
$qry5 = <<<END5
insert into order (cusID, addrID)
values ({$currentOrder->uID}, {$currrentOrder->addrID});
END5;
//run query
$rs5 = $db_conn->query($qry5);
//select orderId
$qry6 = <<<END6
select orderID from orders where cusID = "{$currentOrder->uID}";
END6;
$orderID = $rs6->fetch_assoc();
//insert pizzas
$myfile = fopen("php/testfile.txt", "w");

foreach($currentOrder->pizzas as $selPizza) {
    $qry7 = <<<END7
insert into pizza (orderID, size, dough, sauce, cheese, toppings)
values ($orderID, {$selPizza->size}, {$selPizza->dough}, 
{$selPizza->sauce}, {$selPizza->cheese}, {$selPizza->toppings});
END7;
    $rs7 = $db_conn->query($qry7);
    fwrite($myfile, $qry7);
}
disconnect_db($db_conn);
//output order number
echo $orderID;
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