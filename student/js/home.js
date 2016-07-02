
$(document).ready(function () {

    $('.scrollToTop').click(function(){
        $('html, body').animate({scrollTop : 0},800);
        return false;
    });

    $('#nav').affix({
        offset: {
            top: $('header').height() - $('#nav').height()
        }
    });

    $('body').scrollspy({target: '#nav'});

    $('.scroll-top').click(function () {
        $('body,html').animate({scrollTop: 0}, 1000);
    });

    /* smooth scrolling for nav sections */
    $('#nav .navbar-nav li>a').click(function () {
        var link = $(this).attr('href');
        var posi = $(link).offset().top;
        $('body,html').animate({scrollTop: posi}, 700);
    });

    $('#login-close-btn').click(function () {
        $('#login-fail-text').text("");
        $('#username').val("");
        $('#password').val("");
    });
});

function parseFeedback(response) {
    var feedback = JSON.parse(response);

    if(feedback.message != "success"){
        alert(feedback.message + ". Please try again!");
        return;
    }

    if(feedback.result == "valid"){
        location.href = 'game-home.php';
    } else {
        $('#login-fail-text').text("Invalid username and/or password!");
        $('#password').val("");
    }
}

function validStudent() {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    //send request
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            parseFeedback(xmlhttp.responseText);
        }
    };

    xmlhttp.open("POST", "login.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("username="+username+"&password="+password);
}
