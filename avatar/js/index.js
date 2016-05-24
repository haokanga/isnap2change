$('#nav-right').on('click', function() {

	var index = $("#hiddenIndex").val();

	index++;

	if(index == 10) {
		$('#nav-right').attr("disabled","disabled");
	} else {
		$('#nav-left').removeAttr("disabled");
		$('#nav-right').removeAttr("disabled");
	}

	$("#hiddenIndex").val(index);	

	var pos = 3 + index * 9;

	$('.runner').css("left", pos+"%");
    $('.runner').addClass('horizTranslate');

});

$('#nav-left').on('click', function() {

	var index = $("#hiddenIndex").val();

	index--;

	if(index == 0) {
		$('#nav-left').attr("disabled","disabled");
	} else {
		$('#nav-left').removeAttr("disabled");
		$('#nav-right').removeAttr("disabled");
	}

	$("#hiddenIndex").val(index);	

	var pos = 3 + index * 9;

	$('.runner').css("left", pos+"%");
    $('.runner').addClass('horizTranslate');

});