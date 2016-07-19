$(document).ready(function () {
    appendTTSButton();
});

function appendTTSButton() {
    //foreach td in data table
    $('.dataTable td').each(function () {
        // ignore hidden cols
        if ($(this).is(':visible')) {
            var text = $(this).clone() //clone the element
                .children() //select all the children
                .remove() //remove all the children
                .end() //again go back to selected element
                .text()
                .trim();
            // ignore no-text/numeric cells
            if (text.length > 0 && !$.isNumeric(text)) {
                $(this).append($('<span class="glyphicon glyphicon-volume-up pull-right tts" aria-hidden="true"></span>'));
            }
        }
    });
}

//include tts.js to play audio
$.getScript('../student/js/tts.js', function () {
});