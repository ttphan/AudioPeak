// JavaScript Document
/*
searchLastfm(search)
	showSearchResults(json)
		foreach searchYT(myTrack, i)
					showButtons(data, i, myTrack)
*/

$(document).ready(function() {
	
	$('#searchAll').keyup(function(e) {
		// Enter key
		if(e.keyCode == 13) {
			var search = $("#searchAll").val();
			searchLastfm(search);
		}
	});
	
	$("#huidigenummerbalk").click(function() {
			$("#searchAll").focus();
			$("#searchAll").select();
	});
	
	$("#nieuwnummerbalk").click(function() {
                $("#searchAll").focus();
                $("#searchAll").select();
	}); 
	
});

function searchLastfm(search) {
	//loader gif zolang ajax bezig is.
	$('#ResultsDiv').html('<img src="images/ajax-loader.gif" width="200" height="157" alt="ajax loader gif">');
	$("#searchAll").val(search);
	
	//zoek gebruik makende van seachall_ajaxjson.php welke een json object echo't.
	$.ajax({
		type: "GET",
		url: "php/ajax.php",
		data: {search : search},
		cache: false,
		dataType: 'json',
		success: function(json) {
			showSearchResults(json);
		},
    	error: function (xhr, ajaxOptions, thrownError) {
			//TODO goed afhandelen.
			$('#ResultsDiv').html('Error: Waarschijnlijk iets met php of de verbinden. Probeer opnieuw.');
      	}
	});
}

function showSearchResults(json){
	//maak resulsdiv leeg
	$('#ResultsDiv').html('');

	if(json == ''){
		$('#ResultsDiv').html('Geen resultaten gevonden op last.fm');
	}
	else {
		//haal vars uit json opject plaats deze in de resultsdiv met een span id van i. i wordt doorgegeven aan seachYT om daar de youtube play link te plaatsen
		//ook de image var uit deze json wordt doorgegevn zodat showButton deze ook kan plaatsen
		window.numberResults = json.length; //door hem aan window vast te maken wordt ie global
		for (var i=0;i<numberResults;i++) { 		
			myTrack = new track(json[i].trackName_php,
								json[i].artist_php,
								json[i].trackImage_php
								);
								
			$('#ResultsDiv').append('<span id=\"' + i + '\" ></span> ' + myTrack.artist + ' - ' + myTrack.name + '<br>' );
			searchYT(myTrack, i)
		}
	}
}

function searchYT(myTrack, i){	
	//zoek string
	var searchStr = myTrack.artist + ' ' + myTrack.name;

	//gebruik json om te zoeken en geef deze data door aan showButton met i en image.
	$.getJSON('https://gdata.youtube.com/feeds/api/videos?v=2' +
			'&alt=jsonc&q=' +
			searchStr + 
			'&format=5' +
			'&fmt=18' +
			'&max-results=1' +
			'&orderby=relevance' +
			'&key=AI39si7Blv0HpIGbcHtzjaS70mFR-XEcomtJFHQcKC1-4yEthFsx4AhkMFldBeE_5UyD9jEEFCPMt2jzxDLF3hPT1SRoi1La4Q', function(data) {				
				showButtons(data, i, myTrack);
			});				
}

function showButtons(transport, j, myTrack) {
	var entries	= transport.data.items || [];
	
	if (entries[0] === undefined){
		var button = '<a href=\"#\">NoYT</a>';
		$('#'+j).append(button);
	}
	else{
		//haal de id van de video uit de transport data.
		var id = entries[0].id;
		
		var button = '<a href = \"javascript:playNext(\''+ id +'\',\''+ myTrack.MytoString() + '\')\">'+ playNextButton + '</a>';
		$('#'+j).html(button);
		
	}
}


