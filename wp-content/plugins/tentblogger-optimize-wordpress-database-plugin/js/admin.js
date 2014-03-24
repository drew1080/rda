jQuery(function($) {	

	$('#tentblogger-view-database').click(function() {
		$('#tentblogger-database-table').slideToggle('slow');
	});
	
	$('#tentblogger-trigger-optimization').click(function() {
		var sUrl = location.href + '&tbowpdb=trigger';
		$.get(sUrl, function(data) {	
			$('#tentblogger-go-optimize').fadeOut('slow', function() {
				$(this).remove();
				$('#tentblogger-optimization-container').fadeIn('slow');
			});
		});
	});
	
});