//Student Name: Kyle Francis
//Student ID: 19077956
//File Description: This file contains the javascript for the admin page.
// It contains the functions for the search and update status buttons.

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

//checks that inputted text matches the reference number regex
//if it does, it stores the reference number in an xhr request body and sends it to admin.php
//the innerHTML of the divID is set to the response text and the confirmation div is set to an empty string
//if reference number doesn't match the regex, an error message is displayed
function searchData(divID, bsearch) {
  var regex = new RegExp(/^$|^BRN[0-9]{5}$/);
  if (!bsearch.match(regex)) {
    alert("Reference  number must be in correct format ie BRN00001.");
  } else {
    var xhr = createRequest();
    if (xhr) {
      var place = document.getElementById(divID);
      var requestBody = "bsearch=" + encodeURIComponent(bsearch);
      xhr.open("POST", "admin.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
          place.innerHTML = xhr.responseText;
          document.getElementById("confirmation").innerHTML = "";
        }
      };
      xhr.send(requestBody);
    }
  }
}

//stores the reference number in an xhr request body and sends it to updateStatus.php
//innerHTML of confirmation div is set to the response text and the status is set to assigned
//the assign button is then disabled
function updateStatus(refNum, statNum) {
  var xhr = createRequest();
  if (xhr) {
    var requestBody = "refNum=" + encodeURIComponent(refNum);
    xhr.open("POST", "updateStatus.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        document.getElementById("confirmation").innerHTML = xhr.responseText;
        document.getElementById("status" + statNum).innerHTML = "Assigned";
        document.getElementById("assignButton" + statNum).disabled = true;
      }
    };
    xhr.send(requestBody);
  }
}
