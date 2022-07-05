<!--
Student Name: Kyle Francis
Student ID: 19077956
File Description: This file handles updating the assigned status of a selected booking.
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
$refNum = $_POST["refNum"];

//check if table exists
$checkTable = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_NAME = 'bookings'";
$dbCheck = mysqli_query($conn, $checkTable);
$tableRows = mysqli_num_rows($dbCheck);
//if table doesn't exists tell user to make a new booking to create the table
if ($tableRows <= 0) {
    echo "Table does not exists. Please make a new booking.";
}
//else update the booking status in database
else {
    $update = "UPDATE bookings SET assignStatus = 'Assigned' WHERE refNum = '" . $refNum . "'";
    $dbUpdate = mysqli_query($conn, $update);
    if ($dbUpdate) {
        echo "Congratulations! Booking request " . $refNum . " has been assigned!";
    } else {
        echo "Unable to assign to booking request " . $refNum . ".";
    }
}
//close connection
mysqli_close($conn);
