<!--
Student Name: Kyle Francis
Student ID: 19077956
File Description: This file handles connecting the user to the database.
From this file the users inputs from the bookings html page is inserted into the database and the results booking reference number, pick-up date and time are selected and echoed.
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

//create table if it doesn't exist
$create = "CREATE TABLE IF NOT EXISTS bookings (bookingID INT PRIMARY KEY AUTO_INCREMENT NOT NULL, refNum VARCHAR(50) NOT NULL, cname VARCHAR(255) NOT NULL, phone VARCHAR(12) NOT NULL, unumber VARCHAR(10), snumber VARCHAR(10) NOT NULL, stname VARCHAR(255) NOT NULL, sbname VARCHAR(255), dsbname VARCHAR(255), date DATE NOT NULL, time TIME NOT NULL, bookingDateTime DATETIME NOT NULL, assignStatus VARCHAR(255) NOT NULL)";
$dbCreate = mysqli_query($conn, $create);

// get user input passed from client
$cname = $_POST["cname"];
$phone = $_POST["phone"];
$unumber = $_POST["unumber"];
$snumber = $_POST["snumber"];
$stname = $_POST["stname"];
$sbname = $_POST["sbname"];
$dsbname = $_POST["dsbname"];
$date = $_POST["date"];
$time = $_POST["time"];
$bookingDateTime = date("Y-m-d H:i:s");

//insert user input into database
$insert = "INSERT INTO bookings (cname, phone, unumber, snumber, stname, sbname, dsbname, date, time, bookingDateTime, assignStatus)
VALUES ('$cname', '$phone', '$unumber', '$snumber', '$stname', '$sbname', '$dsbname', '$date', '$time', '$bookingDateTime', 'Unassigned');";
$dbInsert = mysqli_query($conn, $insert);
//get the booking ID of the last inserted row
$select = "SELECT bookingID FROM bookings WHERE bookingDateTime = '$bookingDateTime'";
$dbSearch = mysqli_query($conn, $select);
$numRows = mysqli_num_rows($dbSearch);
$searchRow = mysqli_fetch_assoc($dbSearch);
if ($numRows == 0) {
    echo "Booking was unsuccessful";
} else {
    //update the booking reference number to add the booking ID
    $update = "UPDATE bookings SET refNum = CONCAT('BRN', '" . str_pad($searchRow['bookingID'], 5, "0", STR_PAD_LEFT) . "') WHERE bookingDateTime = '$bookingDateTime'";
    $dbUpdate = mysqli_query($conn, $update);
    //get the booking reference number, pick-up date and time of the last inserted row
    $getBookingInfo = "SELECT refNum, DATE_FORMAT(date,'%d/%m/%Y') AS formatDate , DATE_FORMAT(time,'%H:%i') AS formatTime  FROM bookings WHERE bookingDateTime = '$bookingDateTime'";
    $infoRows = mysqli_query($conn, $getBookingInfo);
    $row = mysqli_fetch_assoc($infoRows);
    //echo the booking reference number, pick-up date and time
    echo "<p>Thank you for your booking!</p>Booking Reference Number: " . $row['refNum'] . "<br/> Pick-Up Time: " . $row['formatTime'] . "<br/> Pick-Up Date: " . $row['formatDate'];
}

//close connection
mysqli_close($conn);
