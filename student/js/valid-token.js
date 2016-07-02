function parseFeedback(response){
    var feedback = JSON.parse(response);

    if(feedback.message != "success"){
        alert(feedback.message + ". Please try again!");
        return;
    }

    if(feedback.result == "valid"){
        $('#valid-token').submit();
    } else {
        $('#token-validation-fail-text').text("Invalid token!");
        $('#token').val("");
    }
}

function validInfo(action){
    var token = document.getElementById("token").value;
    var postData = "token="+token+"&action="+action;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            parseFeedback(xmlhttp.responseText);
        }
    };

    xmlhttp.open("POST", "signup-feedback.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(postData);
}
