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
header("Content-Type: application/json");
$db_conn = connect_db();

//set up query to get cusID using email (Heredoc format)
$qry = "select cusID from customer where cusID = ".$_POST['cID'].";";
//run query
$rs = $db_conn->query($qry);

if (!isset($rs->num_rows)) {
    $qry2 = "insert into customer (name, email) values (".$_POST['cName']."', '".$_POST['cEmail']."');";
    //run query
    $rs2 = $db_conn->query($qry2);
}
$id = 0;
if($_POST['cInfo'] == "new") {
    $qry = "select cusID from customer where email = ".$_POST['cEmail'].";";
    //run query
    $rs = $db_conn->query($qry);
    $id = $rs->fetch_assoc();
    //insert address
    if(isset($_POST['appt'])){
        $qry3 = <<<END3
insert into address (cusID, addr, city, prov, post, phone, appt)
values ({$id}, {$_POST['addr']}, {$_POST['city']}
, {$_POST['prov']}, {$_POST['post']}, 
{$_POST['phone']}, {$_POST['appt']});
END3;
    } else {
        $qry3 = <<<END3
insert into address (cusID, addr, city, prov, post, phone)
values ({$_POST['cID']}, {$_POST['addr']}, {$_POST['city']}
, {$_POST['prov']}, {$_POST['post']}, 
{$_POST['phone']});
END3;
    }
    //run query
    $rs3 = $db_conn->query($qry3);

}

//select addressId
$qry4 = <<<END4
select addrID from address where cusID = "{$id}";
END4;

//run query
$rs4 = $db_conn->query($qry4);
$addrID = $rs4->fetch_assoc();
//insert order
$qry5 = <<<END5
insert into order (cusID, addrID)
values ({$id}, {$addrID});
END5;
//run query
$rs5 = $db_conn->query($qry5);
//select orderId
$qry6 = <<<END6
select orderID from orders where cusID = "{$id}";
END6;
$rs6 = $db_conn->query($qry6);
$orderID = $rs6->fetch_assoc();
//insert pizzas
// $iterator = 0;
// while(isset($_POST['size'.$iterator])) {
//     $qry7 = "insert into pizza (orderID, size) values (".$orderID.", '".$_POST['size'.$iterator]."');";

//     $rs7 = $db_conn->query($qry7);
//     $iterator++;
// }
//, dough, sauce, cheese, toppings
//, "$_POST['dough'$iterator]", "$_POST['sauce'$iterator]", "$_POST['cheese'$iterator]", "$_POST['toppings'$iterator]"
disconnect_db($db_conn);
//output order number

echo `{ "order": "{$orderID}" }`;
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