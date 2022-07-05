// Student Name: Kyle Francis
// Student ID: 19077956
// File Description: This file contains the javascript for the booking page.
// It contains the functions that return the current date and time, checks the users inputted data
// and insert data into the database.

//returns the current date
function getCurrentDate() {
  var today = new Date();
  var currentDate =
    today.getFullYear() +
    "-" +
    (today.getMonth() + 1).toString().padStart(2, 0) +
    "-" +
    today.getDate().toString().padStart(2, 0);
  return currentDate;
}

//returns the current time
function getCurrentTime() {
  var today = new Date();
  var currentTime =
    today.getHours().toString().padStart(2, 0) +
    ":" +
    today.getMinutes().toString().padStart(2, 0);
  return currentTime;
}

//gets the current date and time and sets values for booking form date and time inputs
function getCurrentDateTime() {
  document.getElementById("date").value = getCurrentDate();
  document.getElementById("date").setAttribute("min", getCurrentDate());
  document.getElementById("time").value = getCurrentTime();
}

//checks the users inputted data
//returns true if the data is valid
//returns false and displays error messages if the data is invalid
function checkData(cname, phone, snumber, stname, date, time) {
  var errorMessage = "";
  if (cname.length == 0) {
    errorMessage += "Name must be filled in.\n";
  }
  if (!/^\d{10,12}$/.test(phone)) {
    errorMessage +=
      "Phone Number must only contain numbers and must be 10-12 characters long.\n";
  }
  if (snumber.length == 0) {
    errorMessage += "Street Number must be filled in.\n";
  }
  if (stname.length == 0) {
    errorMessage += "Street Name must be filled in.\n";
  }
  if (date.length == 0) {
    errorMessage += "Pick-Up Date must be entered.\n";
  }
  if (time.length == 0) {
    errorMessage += "Pick-Up Time must be entered.\n";
  }

  if (date + " " + time < getCurrentDate() + " " + getCurrentTime()) {
    errorMessage +=
      "Pick-Up Date and Time must not be earlier than current date and time.\n";
  }

  if (errorMessage.length != 0) {
    alert(errorMessage);
    return false;
  } else {
    return true;
  }
}

//create xhr object
function createRequest() {
  var xhr = false;
  if (window.XMLHttpRequest) {
    xhr = new XMLHttpRequest();
  } else if (window.ActiveXObject) {
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  }
  return xhr;
}

//if xhr is created and the data is valid, the data is sent to insertData.php
//sets reference innerHTML to the response text
//resets the form and sets the current date and time after the data is inserted
function insertData(
  dataSource,
  referenceName,
  cname,
  phone,
  unumber,
  snumber,
  stname,
  sbname,
  dsbname,
  date,
  time
) {
  var xhr = createRequest();
  var valid = checkData(cname, phone, snumber, stname, date, time);
  if (xhr && valid) {
    var place = document.getElementsByName(referenceName);
    var requestBody =
      "cname=" +
      encodeURIComponent(cname) +
      "&phone=" +
      encodeURIComponent(phone) +
      "&unumber=" +
      encodeURIComponent(unumber) +
      "&snumber=" +
      encodeURIComponent(snumber) +
      "&stname=" +
      encodeURIComponent(stname) +
      "&sbname=" +
      encodeURIComponent(sbname) +
      "&dsbname=" +
      encodeURIComponent(dsbname) +
      "&date=" +
      encodeURIComponent(date) +
      "&time=" +
      encodeURIComponent(time);
    xhr.open("POST", dataSource, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        place[0].innerHTML = xhr.responseText;
      }
    };
    xhr.send(requestBody);
    document.getElementById("form").reset();
    getCurrentDateTime();
  }
}