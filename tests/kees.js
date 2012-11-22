// JavaScript Document

//test
window.onload = function() {
	$("run").observe("mouseup", testfunc);		
}

//test
function  testfunc(event) {
	var $jq = jQuery.noConflict();
	
	$jq.ajax({
		url: 'php/index.php?search=test',
		success: function(data) {
			$jq('#videoResultsDiv').html(data);
			alert('Load was performed.');
		}
	});	
}