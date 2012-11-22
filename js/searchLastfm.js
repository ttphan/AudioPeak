// JavaScript Document last fm doorzoeken via ajax gebeuren
$(document).ready(function() {
	$('#searchLastfm').keyup(function(e) {
		// Enter key
		if(e.keyCode == 13) {
			seachlastfm();
		}
	});  
});


function  seachlastfm() {
	var searchStr = $("#searchLastfm").val();
	
	$.ajax({
		url: 'php/index.php?search=' + searchStr,
		success: function(data) {
			$('#videoResultsDiv').html(data);
		}
	});	
}