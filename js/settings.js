RondinLogConfig={
	addHandler: function (handlerName) {

		$('#config_handlers').append( $('<li>').text(handlerName));
	}
};

$(document).ready(function() {
	$('#avail_handler').on('change', function() {
		RondinLogConfig.addHandler($(this).val());
		//Reset value
		$(this).val('');
	});	
});