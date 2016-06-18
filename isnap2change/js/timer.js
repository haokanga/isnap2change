/**
 * Created by wwj on 2016/6/18.
 */

function getTimeRemaining(endtime) {
    var t = Date.parse(endtime) - Date.parse(new Date());
    var seconds = Math.floor((t / 1000) % 60);
    var minutes = Math.floor((t / 1000 / 60) % 60);
    var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
    var days = Math.floor(t / (1000 * 60 * 60 * 24));
    return {
        'total': t,
        'days': days,
        'hours': hours,
        'minutes': minutes,
        'seconds': seconds
    };
}

function initializeClock(id, endTime, visibility) {
    var clock = document.getElementById(id);

    if(visibility == true) {
        var hoursSpan = clock.querySelector('.hours');
        var minutesSpan = clock.querySelector('.minutes');
        var secondsSpan = clock.querySelector('.seconds');
    }

    function updateClock() {
        var t = getTimeRemaining(endTime);

        if(visibility == true) {
            hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
            minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
            secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);
        }

        if (t.total <= 0) {
            clearInterval(timeInterval);
        }
    }

    updateClock();
    var timeInterval = setInterval(updateClock, 1000);
}

function getDeadline(week) {
    var deadline;

    if(localStorage.getItem("deadline_" + week) != null) {
        deadline = localStorage.getItem("deadline_" + week);
    } else {
        deadline = new Date(Date.parse(new Date()) + 60 * 60 * 1000);
        localStorage.setItem("deadline_" + week, deadline);
    }

    return deadline;
}


initializeClock('clockdiv', deadline);