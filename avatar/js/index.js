$('#nav-right').on('click', function() {
    var MAX_WEEK = 10;
	var index = $("#hiddenIndex").val();

	index++;

	if(index > MAX_WEEK) {
		$('#nav-right').attr("disabled","disabled");
	} else {
		$('#nav-left').removeAttr("disabled");
		$('#nav-right').removeAttr("disabled");        
        $("#hiddenIndex").val(index);	
        var pos = 3 + index * 9;
        $('.runner').css("left", pos+"%");
        $('.runner').addClass('horizTranslate');
	}

});

$('#nav-left').on('click', function() {
    var MIN_WEEK = 1;
	var index = $("#hiddenIndex").val();

	index--;

	if(index < MIN_WEEK) {
		$('#nav-left').attr("disabled","disabled");
	} else {
		$('#nav-left').removeAttr("disabled");
		$('#nav-right').removeAttr("disabled");
        $("#hiddenIndex").val(index);	
        var pos = 3 + index * 9;
        $('.runner').css("left", pos+"%");
        $('.runner').addClass('horizTranslate');
	}

});