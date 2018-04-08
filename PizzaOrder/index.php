<?php
/**
 * PizzaOrder Project
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

</head>
<!--start document with first JS function to load greeting page -->
<body onload="loadFirstPage();" >

    <header>
    <h1>Group 5's Pizza Order Site!</h1>
    </header>

    <main>
        <!--all real output goes into this div -->
        <div id="outputDiv"></div>
        <!--Errors and output FOR TESTING -->
        <p id="error"></p> <!-- Error output area -->
        <p id="test"></p> <!-- Test output area -->
    </main>

    <footer>

    </footer>

</body>
</html>