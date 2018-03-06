<?php
//header("Content-Type: application/json");
session_start();
$_POST['emailInput'] = "wow@gmail.com";
$db_conn = connect_db();

$qry = <<<END
select cusID from customer where email = "{$_POST['emailInput']}";
END;
$rs = $db_conn->query($qry);

if ($rs->num_rows > 0){
	$id = array("status" => "OK");
    while ($row = $rs->fetch_assoc()){
		array_push($id, $row);
    }
    $customerID = $id[0]['cusID'];
    //echo json_encode($id);
    //LOOKS LIKE: {"status":"OK","0":{"cusID":"2"}}
    
    $qry2 = <<<END2
    select distinct addr, city, prov, post, appt from orders where cusID = {$customerID};
END2;
    $rs2 = $db_conn->query($qry2);
    if ($rs2->num_rows > 0){
        $posts = array("status" => "OK");
        $posts['posts'] = array();
        while ($row2 = $rs2->fetch_assoc()){
            array_push($posts['posts'], $row2);
        }
        echo json_encode($posts);
    }
} else {
    echo '{ "status": "None" }';
}
disconnect_db($db_conn);

//echo '{ "userEmail": "'.$_POST['emailInput'].'", "testText": "howdy from the php code!"}';
//echo "howdy from the php code!"; 

function connect_db(){
    $db_conn = new mysqli('localhost', 'lamp1user', '!Lamp1!', 'group5');
    if ($db_conn->connect_errno) {
        printf ("Could not connect to database server\n Error: "
            .$db_conn->connect_errno ."\n Report: "
            .$db_conn->connect_error."\n");
        die;
    }
    return $db_conn;
}

function disconnect_db($db_conn){
    $db_conn->close();
}