<!--
Student Name: Kyle Francis
Student ID: 19077956
File Description: This file handles connecting the admin to the database.
From this file the admins input from the search  bar is passed through he database and the results selected and echoed.
There are no functions in this file.
-->
<?php
//getting login info from sqlinfo.inc.php to connect to database
require_once('../../conf/sqlinfo.inc.php');

//use mysqli_connect to connect to localhost
$conn = mysqli_connect(
    $sql_host,
    $sql_user,
    $sql_pass
)
    //if unable to connect display error code and error message else display successful connection message
    or die("<p>Database connection failure. Error code " . mysqli_connect_errno() . ": " . mysqli_connect_error() . "</p>");

//use mysqli_select_db to select the wfz5687 database from localhost
$dbSelect = @mysqli_select_db($conn, $sql_db)
    //if unable to select database display error code and message else display successful selection message
    or die("<p>Unable to select the database.</p>" . "<p>Error code " . mysqli_errno($conn) . ": " . mysqli_error($conn) . "</p>");

// get user input passed from client
$bsearch = $_POST["bsearch"];

//checks if table exists in database
$checkTable = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_NAME = 'bookings'";
$dbCheck = mysqli_query($conn, $checkTable);
$tableRows = mysqli_num_rows($dbCheck);
//if table doesn't exists in database, ask user to make a new booking
if ($tableRows <= 0) {
    echo "Table does not exists. Please make a new booking.";
} else {
    //if table exists, search for booking info in database
    $select = "SELECT refNum, cname, phone, sbname, dsbname, DATE_FORMAT(date,'%d/%m/%Y') AS formatDate , DATE_FORMAT(time,'%H:%i') AS formatTime, assignStatus FROM bookings";
    //if search bar is not empty, search where refNum matches search bar input
    if ($bsearch != "") {
        $select .= " WHERE refNum = '$bsearch'";
    }
    //if search bar is empty, search where status is unassigned and pickup is within 2 hours of current time
    else {
        $select .= " WHERE assignStatus = 'Unassigned' AND CONCAT(`date`, ' ', `time`) > NOW() AND CONCAT(`date`, ' ', `time`) <= DATE_ADD(NOW(), INTERVAL 2 HOUR)";
    }
    $dbSearch = mysqli_query($conn, $select);
    $numRows = mysqli_num_rows($dbSearch);
    //if no results are found, display message depending on search bar input
    if ($numRows == 0) {
        if ($bsearch != "") {
            echo "Booking reference number " . $bsearch . " can not be found.";
        } else {
            echo "No unassigned bookings can be found with 2 hours from now.";
        }
        //if results are found, display results in table
    } else {
        echo "<table width='100%' border= '1'>";
        echo "<tr><th>Booking Reference Number</th><th>Customer Name</th><th>Phone</th><th>Pick-Up Suburb</th><th>Destination Suburb</th><th>Pick-Up Date and Time</th><th>Status</th><th>Assign</th>";
        //loop through results and display in table and create variable to track number of rows
        $statNum = 0;
        while ($row = mysqli_fetch_assoc($dbSearch)) {
            echo "<tr><td>", $row['refNum'], "</td>";
            echo "<td>", $row['cname'], "</td>";
            echo "<td>", $row['phone'], "</td>";
            //if pickup or destination suburb is empty, display "Unknown", else display suburb name
            if ($row['sbname'] == "") {
                echo "<td>Unknown</td>";
            } else {
                echo "<td>", $row['sbname'], "</td>";
            }
            if ($row['dsbname'] == "") {
                echo "<td>Unknown</td>";
            } else {
                echo "<td>", $row['dsbname'], "</td>";
            }
            echo "<td>", $row['formatDate'], " ", $row['formatTime'], "</td>";
            echo "<td id='status" . $statNum . "'>", $row['assignStatus'], "</td>";
            //if status is unassigned, display assign button
            if ($row['assignStatus'] == "Unassigned") {
                echo "<td><button id=\"assignButton" . $statNum . "\" onclick=\"updateStatus('" . $row['refNum'] . "', '" . $statNum . "')\">Assign</button></td>";
            }
            //if status is assigned display disabled assign button
            else {
                echo "<td><button disabled>Assign</button></td>";
            }
            echo "</tr>";
            //increment statNum after each row
            $statNum++;
        }
        echo "</table>";
    }
}
//close connection
mysqli_close($conn);
