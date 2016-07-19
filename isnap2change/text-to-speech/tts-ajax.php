/**
* Created by ABS on 7/13/2016.
*/
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Origin, Authorization");
header("Access-Control-Allow-Credentials: true");
?>
<div class="col-lg-6 col-md-6 col-sm-12">
    <audio class="audio">
        Your browser does not support the audio element.
    </audio>
    <button type="button" class="speak-button btn btn-secondary">
        Speak 
        <span>(Only in Chrome &amp; Firefox)</span>
    </button>
</div>
<!-- js files -->
<script src="Text%20to%20Speech_files/jquery-1.js"></script>
<script src="Text%20to%20Speech_files/bootstrap.js"></script>
<script src="Text%20to%20Speech_files/constants.js"></script>
<script>
    var voice = 'en-US_AllisonVoice';
    var audio = $('.audio').get(0);
    var username = "6678c2a8-0261-44f8-a9d3-9a2fb43d65b6";
    var password = "40byApeYUV7f";
    function setHeader(xhr) {
        xhr.setRequestHeader("Authorization", "Basic " + btoa(username + ":" + password));
    }

    $('.speak-button').click(function (evt) {
        evt.stopPropagation();
        evt.preventDefault();
        $('.result').hide();
        var text = "How things change in a year. In addition to the header attribute in place of xhr.setRequestHeader, current jQuery (1.7.2+) includes a username and password attribute with the $.ajax call";

        $.ajax
        ({
            type: "POST",
            url: "https://stream.watsonplatform.net/text-to-speech/api/v1/synthesize",
            dataType: 'jsonp',
            xhrFields: {
                withCredentials: true
            },
            crossDomain: true,
            headers: {
                Accept: "audio/wav",
                "Content-Type": "Content-Type: application/json"
            },
            data: "{\"text\":\"Eating a balanced diet is vital.\"}",
            success: function () {
                alert('Success!');
            },
            error: function () {
                alert('Failed!');
            },
            beforeSend: setHeader
        });
        //synthesizeRequest(utteranceOptions, audio);

        /*
         * curl -X POST -u 6678c2a8-0261-44f8-a9d3-9a2fb43d65b6:40byApeYUV7f --header "Content-Type: application/json" --header "Accept: audio/wav" --data "{\"text\":\"Eating a balanced diet is vital for your health and wellbeing. The food we eat is responsible for providing us with the energy to do all the tasks of daily life. For optimum performance and growth a balance of protein, essential fats, vitamins and minerals are required. We need a wide variety of different foods to provide the right amounts of nutrients for good health. The different types of food and how much of it you should be aiming to eat is demonstrated on the pyramid below.\"}" --output balanced_diet.wav "https://stream.watsonplatform.net/text-to-speech/api/v1/synthesize"
         *
         * */

        return false;
    });


    function synthesizeRequest(options, audio) {
        var sessionPermissions = JSON.parse(localStorage.getItem('sessionPermissions')) ? 0 : 1;
        var downloadURL = 'https://stream.watsonplatform.net/text-to-speech/api/v1/synthesize' +
            '?voice=' + options.voice +
            '&text=' + encodeURIComponent(options.text) +
            '&X-WDC-PL-OPT-OUT=' + sessionPermissions;

        if (options.download) {
            downloadURL += '&download=true';
            window.location.href = downloadURL;
            return true;
        }
        audio.pause();
        audio.src = downloadURL;
        audio.muted = true;
        audio.play();
        $('body').css('cursor', 'wait');
        $('.speak-button').css('cursor', 'wait');
        return true;
    }
</script>