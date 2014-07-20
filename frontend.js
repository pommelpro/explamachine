
function merge() 
{
	var query1 = document.getElementById("query1").value;
	var query2 = document.getElementById("query2").value;
	console.log(query1 + " " + query2);
	$('#solution').html(query1 + " " + query2);
}



$(document).ready(function () {

	$('body').hide().fadeIn(1000);

	$('#submitbutton').click(function() {
		merge();
	});

});