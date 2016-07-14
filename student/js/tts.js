//text and icon span shares the same parent
$(document).ready(function () {
    $('.tts').on('click', function () {
        //encode text for http request parameters
        var text = encodeURIComponent($(this).parent()
            .clone() //clone the element
            .children() //select all the children
            .remove() //remove all the children
            .end() //again go back to selected element
            .text()
            .trim()
        );

        //remove old tts if exists
        $(this).children().filter("audio").each(function () {
            this.pause();
            $(this).remove();
        });
        $(this).empty();

        //append new tts with en-US_AllisonVoice
        var ttsAudio = $('<audio autoplay hidden><source src="https://text-to-speech-demo.mybluemix.net/api/synthesize?voice=en-US_AllisonVoice&text=' + text + '&X-WDC-PL-OPT-OUT=1" type="audio/ogg"></audio>');
        $(this).append(ttsAudio);
    });
});
