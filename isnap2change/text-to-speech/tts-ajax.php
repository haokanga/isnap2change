/**
* Created by ABS on 7/13/2016.
*/

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

    $('.speak-button').click(function (evt) {
        evt.stopPropagation();
        evt.preventDefault();
        $('.result').hide();
        var text = "How things change in a year. In addition to the header attribute in place of xhr.setRequestHeader, current jQuery (1.7.2+) includes a username and password attribute with the $.ajax call";

        var utteranceOptions = {
            text: text,
            voice: voice,
            sessionPermissions: JSON.parse(localStorage.getItem('sessionPermissions')) ? 0 : 1
        };

        synthesizeRequest(utteranceOptions, audio);

        return false;
    });
</script>