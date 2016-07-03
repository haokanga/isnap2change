function saveDueTime(studentID, week, dueTime) {
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            parseFeedback(xmlhttp.responseText);
        }
    };

    xmlhttp.open("POST", "save-due-time.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("studentID="+studentID +"&week="+week+"&dueTime="+ dueTime);
}

function parseFeedback(response) {
    var feedback = JSON.parse(response);

    if(feedback.message != "success"){
        alert(feedback.message + ". Please try again!");
        //jump to error page
    }
}