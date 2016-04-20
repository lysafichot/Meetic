$(document ).ready(function() {

	$('#add').hide();
	$('.villes').on('change', (function () {
      $('#add').show();
	}));


	var nbclick = 0;
	var click = 0;
	$('#add').on('click', (function (event) {
		event.preventDefault();
		
		if(nbclick < 2) {

			$('#where').append('<input name="ville'+nbclick+'" placeholder="VILLE" id="ville'+nbclick+'"/>');
			$('#liste').append('<div id="list'+nbclick+'"></div>');

			$("#ville"+nbclick).keyup(function(){
				click = nbclick - 1;
				$.ajax({
					type: "POST",
					url: "search_add.php",
					data:'keyword='+$(this).val(),

					success: function(data) {
						
						$("#list"+click).show();
						$("#list"+click).html(data);

						$(".city").each(function (index, value) {

							$(this).on('click', function() {
								var ville = $(value).text();
								$("#ville"+click).val(ville);
								$("#list"+click).hide();
								
							});
						});				
					}
				});
			});
		}
		nbclick++;
		if(nbclick >= 2) {
			$('#add').hide();
		}
	}));	
});
