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

function initializeClock(endTime) {

    function updateClock() {
        var t = getTimeRemaining(endTime);

        if (t.total <= 0) {
            clearInterval(timeInterval);
            alert("Time is up!");
        }
    }

    updateClock();
    var timeInterval = setInterval(updateClock, 1000);
}
