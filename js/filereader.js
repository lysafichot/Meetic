function loadFile(event) {
	document.getElementById('img').innerHTML = '<div class="image"><img id="output" src="" alt="Avatar"></div>';
	var reader = new FileReader();
	reader.onload = function(){
		var output = document.getElementById('output');
		output.src = reader.result;
	};
	reader.readAsDataURL(event.target.files[0]);
}


addEventListener("DOMContentLoaded", function() {

	document.getElementsByName('avatar')[0].onchange = function(event) {
		loadFile(event);
	};

});