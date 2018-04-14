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


 // Riley's SQL Logic
 // Checks if the address is new or not
 // inserts into the database accordingly
$db_conn = connect_db();
$cusId = 0;
$addrId = 0;
$orderId = 0;
if($_POST['cInfo'] != "new"){ //check if the user is using a new or old address
    $qry = "select cusID from customer where cusID = ".$_POST['cID'].";"; //grab the Id to double check
    if($result = $db_conn->query($qry)){
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $cusId = $row['cusID'];
            $addr = $_POST['addr'];
            $qry = "select addrID from address where cusID = '".$cusId."' AND addr = '".$addr."';"; //grab the address ID based on the customer Id and the address itself
            if($result = $db_conn->query($qry)){
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $addrId = $row['addrID'];
                    $qry ="insert into orders (cusID,addrID) values('".$cusId."','".$addrId."');"; //create a new order with the customerId and the addressId
                    $db_conn->query($qry);
                    $qry ="select orderID from orders where cusID ='".$cusId."' AND addrID ='".$addrId."';"; //select all orders that are for that customer and address
                    if($result = $db_conn->query($qry)){
                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){ //get the latest order number (the one just created)
                                $orderId = $row['orderID'];
                            }
                            $counter = $_POST['counter'];
                            //Add every pizza to the database
                            for($i = 0; $i<$counter; $i++){
                                $size = $_POST['size'.$i];
                                $dough = $_POST['dough'.$i];
                                $sauce = $_POST['sauce'.$i];
                                $cheese = $_POST['cheese'.$i];
                                $toppings = $_POST['toppings'.$i];
                                $qry = "insert into pizza (orderID,size,dough,sauce,cheese,toppings) values('".$orderId."','".$size."','".$dough."','".$sauce."','".$cheese."','".$toppings."');";
                                $db_conn->query($qry);
                            }
                            echo `{ "order": "{$orderId}" }`; //THIS IS WHERE IT SHOULD GOTO THE LAST PAGE
                        }
                    }
                }
            }
        }
    }
}else{ //if its a new address
    $qry = "insert into customer (name,email) values('".$_POST['cName']."', '".$_POST['cEmail']."');"; //adds customer to database
    $db_conn->query($qry);
    $qry = "select cusID from customer where name = '".$_POST['cName']."' AND email = '".$_POST['cEmail']."';"; //get the newly created customer's Id
    if($result = $db_conn->query($qry)){
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $cusId = $row['cusID'];
            $addr = $_POST['addr'];
            $city = $_POST['city'];
            $prov = $_POST['prov'];
            $post = $_POST['post'];
            $phone = $_POST['phone'];
            if(isset($_POST['appt'])){ //check if they have an appartment number
                $apt = $_POST['appt'];
                $qry = "insert into address (cusID, addr, city, prov, post, phone,appt) values('".$cusId."','".$addr."','".$city."','".$prov."','".$post."','".$phone."','".$apt."');";
                $db_conn->query($qry);
            }else{
                $qry = "insert into address (cusID, addr, city, prov, post, phone) values('".$cusId."','".$addr."','".$city."','".$prov."','".$post."','".$phone."');";
                $db_conn->query($qry);
            }
            $qry = "select addrID from address where cusID = '".$cusId."' AND addr = '".$addr."';"; //grab the address ID for the newly made address
            if($result = $db_conn->query($qry)){
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $addrId = $row['addrID'];
                    $qry ="insert into orders (cusID,addrID) values('".$cusId."','".$addrId."');"; //create a new order with the customer id and address id
                    $db_conn->query($qry);
                    $qry ="select orderID from orders where cusID ='".$cusId."' AND addrID ='".$addrId."';"; //grab that new order's ID
                    if($result = $db_conn->query($qry)){
                        if($result->num_rows > 0){
                            $row = $result->fetch_assoc();
                            $orderId = $row['orderID'];
                            $counter = $_POST['counter'];
                            //insert each pizza into the table
                            for($i = 0; $i<$counter; $i++){
                                $size = $_POST['size'.$i];
                                $dough = $_POST['dough'.$i];
                                $sauce = $_POST['sauce'.$i];
                                $cheese = $_POST['cheese'.$i];
                                $toppings = $_POST['toppings'.$i];
                                $qry = "insert into pizza (orderID,size,dough,sauce,cheese,toppings) values('".$orderId."','".$size."','".$dough."','".$sauce."','".$cheese."','".$toppings."');";
                                $db_conn->query($qry);
                            }
                            echo `{ "order": "{$orderId}" }`; //THIS IS WHERE IT SHOULD GOTO THE LAST PAGE
                        }
                    }
                }
            }
        }
    }
}


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
