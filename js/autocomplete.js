$(document).ready(function(){
	$("#search_cp").keyup(function(){
		$.ajax({
			type: "POST",
			url: "ville_sql.php",
			data:'keyword='+$(this).val(),

			success: function(data){
				$("#list_city").show();
				$("#list_city").html(data);
				$("#search_city").css("background","#FFF");

				$('.city').each(function (index, value) {
					
					$(this).on('click', function() {
						var cp = $(value).text();
						$("#search_city").val(cp);
						$("#list_city").hide();
						var index_cp = index;

						$('.cp').each(function (index, value) {
							var index_city = index;
							if(index_city == index_cp) { 
								var city = $(value).text();
								$("#search_cp").val(city);
							}
						});	
					});
				});
			}
		});
	});
});

