<?php
/**
 * PizzaOrder Project
 *
 * PHP version 7
 *
 * @category N/A
 * @package  N/A
 * @author   Aaron Thawe <aaron.thawe@gmail.com>
 * @author   Riley Sims <rileytsims@hotmail.com>
 * @author   Sean Thorpe <seanthorpe95@gmail.com>
 * @author   Diana Orr <test@gmail.com>
 * @author   Scott Shields <sjshields87@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version  GIT: <git_id>
 * @link     N/A
 */

//Begin session for user
session_start();?>
<!DOCTYPE html>
<html lang="en">
<!--head info, scripts and styles-->
<head>
    <title>Group 5 Pizza Order Site</title>
    <!--styles-->
    <link href="https://fonts.googleapis.com/css?family=Krona+One" rel="stylesheet">
    <link href="styles/style.css" rel="stylesheet"/>
    <!-- scripts -->
    <script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="scripts/Main.js"></script>
    <style>
        /* Riley's Styling Fiesta */
        body{
            background-image: url("Pizza.jpg");
        }

        #wrap{
            margin-top:20px;
            margin-left:20%;
            margin-right: 20%;
            padding: 10px;
            background: rgb(255,255,255);
            border: 1px solid #000000;
            border-radius: 50px;
        }

        #outputDiv{
            font-size: 20px;
        }

    </style>
</head>
<!--start document with first JS function to load greeting page -->
<body onload="loadFirstPage();" >
<div id="wrap">
    <center>
        <header>
        <h1>Group 5's Pizza Order Site!</h1>
        </header>

        <main>
                <!--all real output goes into this div -->
                <div id="outputDiv"></div>
                <!--Errors and output FOR TESTING -->
                <p id="error"></p> <!-- Error output area -->
        </main>

        <footer>
            <img src="PizzaMan.jpg" alt="PizzaMan"/>
        </footer>
    </center>
</div>
</body>
</html>
