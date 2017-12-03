$(document).ready(function() {
	// Live Search
	// On Search Submit and Get Results
	function search() {
		var query_value = $('input#livesearchid').val();
		$('strong#livesearch-string').text(query_value);
		if(query_value !== ''){
			$.ajax({
				type: "POST",
				url: "livesearch.php",
				data: { query: query_value },
				cache: false,
				success: function(html){
					$("ul#livesearchresults").html(html);
				}
			});
		}return false;
	}

	$("input#livesearchid").live("keyup", function(e) {
		// Set Timeout
		clearTimeout($.data(this, 'timer'));

		// Set Search String
		var search_string = $(this).val();

		// Do Search
		if (search_string == '') {
			$("ul#livesearchresults").fadeOut();
			$('h4#livesearchresults-text').fadeOut();
		}else{
			$("ul#livesearchresults").fadeIn();
			$('h4#livesearchresults-text').fadeIn();
			$(this).data('timer', setTimeout(search, 100));
		};
	});

	//TODO highlight/animation on item 2 be able to notice where it is
});
