function parseValidInfoFeedback(response){
    var feedback = JSON.parse(response);

    if(feedback.message != "success"){
        alert(feedback.message + ". Please try again!");
        return;
    }

    if(feedback.result == "invalid"){
        $('#username-validation-text').css("color","red");
        $('#username-validation-text').text("This username is used!");
        $('#username-text').val("");
    } else if(feedback.result == "valid"){
        $('#username-validation-text').css("color","green");
        $('#username-validation-text').text("This username is valid!");
    }
}

function parseSubmitInfoFeedback(response){
    var feedback = JSON.parse(response);

    if(feedback.message != "success"){
        alert(feedback.message + ". Please try again!");
        return;
    } else if(feedback.message == "success"){
        alert("You have successfully registered!");
    }


}

function validInfo(action){
    var username = document.getElementById("username-text").value;
    var postData = "username="+username+"&action="+action;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            parseValidInfoFeedback(xmlhttp.responseText);
        }
    };

    xmlhttp.open("POST", "signup-feedback.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(postData);
}

function submitInfo(action){
    var username = $('#username-text').val();
    var password = $('#password-text').val();
    var lastname = $('#lastname-text').val();
    var firstname = $("#firstname-text").val();
    var email = $('#email-text').val();
    var dobDay = $('#dob-dd-selection').val();
    var dobMon = $('#dob-mm-selection').val();
    var dobYear = $('#dob-yyyy-selection').val();
    var gender = $("input[type='radio'][name='gender-radio']:checked").val();
    var identity = $('#identity-selection').val();
    var classID = $('#classid-hidden').val();

    var postData = "username="+username+"&password="+password+"&lastname="+lastname+"&firstname="+firstname
        +"&email="+email+"&dobDay="+dobDay+"&dobMon="+dobMon+"&dobYear="+dobYear+"&gender="+gender
        +"&identity="+identity+"&classID="+classID+"&action="+action;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            parseSubmitInfoFeedback(xmlhttp.responseText);
        }
    };

    xmlhttp.open("POST", "signup-feedback.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(postData);
}
