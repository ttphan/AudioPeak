// The array consists of [startArtist, startTitle, endArtist, endTitle]
function getFiller() {
	var startList = ['Artist1', 'Track1'];
	var endList = ['Artist2', 'Track2'];
	
	$.ajax({
		type: "POST",
		url: "ajax.php",
		data: {start : startList, end: endList},
		success: function(result) {
			     console.log(result);
		}
	});
}

// Search
function search() {
	var searchStr = $("#inputSearchStrId").val();
	
	$.ajax({
		type: "GET",
		url: "ajax.php",
		data: {query : searchStr},
		success: function(result) {
			console.log(result);
		}
	});
}