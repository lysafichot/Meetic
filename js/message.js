$(document).ready(function(){

$('#supprimer').hide();
$('#envoyer').hide();

$('#supprime').on('click', function() {
	$('#recu').hide();
	$('#envoyer').hide();
	$('#supprimer').show();
});

$('#recus').on('click', function() {
	$('#supprimer').hide();
	$('#envoyer').hide();
	$('#recu').show();
});

$('#envoye').on('click', function() {
	$('#supprimer').hide();
	$('#recu').hide();
	$('#envoyer').show();
});
});