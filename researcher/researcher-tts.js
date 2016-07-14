$(document).ready(function () {
    appendTTSButton();
});

function appendTTSButton() {
    $('.dataTable td').each(function () {
        // ignore hidden cols
        if ($(this).is(':visible')) {
            var text = $(this).text();
            // ignore no-text cells
            if (text.trim().length > 0) {
                $(this).append($('<span class="glyphicon glyphicon-volume-up pull-right tts" aria-hidden="true"></span>'));
            }
        }
    });
}

//include tts.js to play audio
$.getScript('../student/js/tts.js', function () {
});