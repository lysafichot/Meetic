$(document).ready(function(){
	$(".regions").change(function(){
		$.ajax({
			type: "POST",
			url: "search_live.php",
			data:'region='+$(this).val(),

			success: function(data) {
				$(".departements").empty();
				$(".departements").html(data);
			}
		});
	});

	$(".departements").change(function(){
		$.ajax({
			type: "POST",
			url: "search_live.php",
			data:'departement='+$(this).val(),

			success: function(data) {
				$(".villes").empty();
				$(".villes").html(data);				
			}
		});
	});

});

