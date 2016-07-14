$(document).ready(function () {
    appendTTSButton();
});

function appendTTSButton() {
    var dataTable = $('.dataTable');
    var ttsButton = $('<span class="glyphicon glyphicon-volume-up pull-right tts" aria-hidden="true"></span>');
    dataTable.find('td').each(function () {
        // ignore hidden cols
        if ($(this).is(':visible')) {
            var text = $(this).text();
            // ignore no-text cells
            if (text.length > 0) {
                console.log(text);
                $(this).append(ttsButton);
            }
        }
    });
}
