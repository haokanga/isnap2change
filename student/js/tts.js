//text and icon span shares the same parent
$(document).ready(function () {
    $('.tts').on('click', function (){
        //encode text for http request parameters
        var text = encodeURIComponent($(this).parent().text());
        //AllisonVoice
        var ttsAudio =  $( '<video autoplay hidden><source src="https://text-to-speech-demo.mybluemix.net/api/synthesize?voice=en-US_AllisonVoice&text='+ text +'&X-WDC-PL-OPT-OUT=1" type="audio/ogg"></video>' );
        $(this).append(ttsAudio);
    });
});
