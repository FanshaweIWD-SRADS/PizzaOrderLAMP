<?php
session_start();?>
<!DOCTYPE html>
<html lang="en">
<!--head info, scripts and styles-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group 5 Pizza Order Site</title>
    <!--styles-->
    <link href="https://fonts.googleapis.com/css?family=Krona+One" rel="stylesheet"> 
    <!-- scripts -->
    <script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="scripts/AaronScript.js"></script>

</head>
<!--start document with first JS function to load greeting page -->
<body onload="loadFirstPage();" >
    <h1>Pizza Order Title</h1>
    <!--all real output goes into this div -->
    <div id="outputDiv">
    </div>
    <!--Errors and output for testing -->
    <p id="error"></p>
    <p id="test">User Email: </p>
</body>
</html>