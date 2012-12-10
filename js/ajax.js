// The array consists of [startArtist, startTitle, endArtist, endTitle]
function getFiller() {
	var startList = ['Nirvana', 'Smells Like Teen Spirit'];
	var endList = ['Foo Fighters', 'Rope'];
	
	$.ajax({
		type: "POST",
		url: "ajax.php",
		data: {getFillerStart : startList, getFillerEnd: endList},
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
		data: {search : searchStr},
		success: function(result) {
			console.log(result);
		}
	});
}

// getInfo
function getInfo() {
	var track = ['Nirvana', 'Smells Like Teen Spirit'];
	
	$.ajax({
		type: "GET",
		url: "ajax.php",
		data: {getInfo : track},
		succes: function(result) {
			console.log(result);
		}
	});
}