// JavaScript Document 
$(document).ready(function() {
	$('#searchAll').keyup(function(e) {
		// Enter key
		if(e.keyCode == 13) {
			searchAll();
		}
	});  

});

var YTplayerState = 0;

function searchAll() {
	//TODO vervangen door loader gif.
	$('#ResultsDiv').html('Shit is aan het laden');
	
	//zoek gebruik makende van kees.php welke een json object echo't.
	var search = $("#searchAll").val();
	$.ajax({
		type: "GET",
		url: "tests/kees.php",
		data: "search="+search,
		cache: false,
		dataType: 'json',
		success: function(json) {
		  showResults(json);
		}
	});
}

function showResults(json){
	//maak resulsdiv leeg
	$('#ResultsDiv').html('');
	
	//haal vars uit json opject plaats deze in de resultsdiv met een span id van i. i wordt doorgegeven aan seachYT om daar de youtube play link te plaatsen
	//ook de image var uit deze json wordt doorgegevn zodat showmyvideo deze ook kan plaatsen
	for (var i=0;i<json.length;i++) { 
		var artist = json[i].artist_php;
		var trackName = json[i].trackName_php;
		var image = json[i].trackImage_php;
		$('#ResultsDiv').append(artist + ' - ' + trackName + ' <span id=\"' + i + '\" ></span><br>' );		
		searchYT(artist, trackName, i, image)
	}
	
}

function searchYT(artist, trackName, i, image){	
	//zoek string
	var searchStr = artist + ' ' + trackName;
	/*	
	 *	Requests a JSON-C feed from the GData.
	 *
	 *	Link paramters:
	 *	&format=5: Show only embeddable videos
	 *	&fmt=18: Show only HQ videos
	 *	&max-results=8: Show a maximum of 8 results
	 *	&orderby=relevance: Order the results by relevance
	 *	&key=[KEY]: Google Developer Key, to avoid the public quota, compared to anonymous requests
	 *
	 */
	//gebruik json om te zoeken en geef deze data door aan showmyvideo met i en image.
	$.getJSON('https://gdata.youtube.com/feeds/api/videos?v=2' +
			'&alt=jsonc&q=' +
			searchStr + 
			'&format=5' +
			'&fmt=18' +
			'&max-results=1' +
			'&orderby=relevance' +
			'&key=AI39si7Blv0HpIGbcHtzjaS70mFR-XEcomtJFHQcKC1-4yEthFsx4AhkMFldBeE_5UyD9jEEFCPMt2jzxDLF3hPT1SRoi1La4Q', function(data) {
				showMyVideo(data, i, image);
			});				
}

// See https://developers.google.com/youtube/2.0/developers_guide_jsonc#Understanding_JSONC for the feed information
function showMyVideo(transport, j, image) {
	//haal de id van de video uit de transport data.
	var entries	= transport.data.items || [];
	var id = entries[0].id;
	
	//plaats een play button als de ytplayer klaar is met afspelen	
	if (YTplayerState == 0){
		var button = '<a href = \"javascript:createVideo(\''+ id +'\',\''+ image + '\')\">play</a>';
		$('#'+j).append(' ' + button);
	}
	//plaats een playnext button als e ytplayer nog aan het afspelen is
	else {
		var button = '<a href = \"javascript:playnext(\''+ id +'\',\''+ image + '\')\">playNEXT</a>';
		$('#'+j).append(' ' + button);
	}
}

//laat de video zien als er op lay wordt gedrukt, en plaats de image.
function createVideo(vidId, image) {
	$('#album').attr('src',image);
	$('#huidigenummerbalk').attr('src',image);
		
	$('#playVideoDiv').flash(
		{	
			id: 'ytplayer',
			swf: 'http://www.youtube.com/v/' + vidId + '?enablejsapi=1&playerapiid=ytplayer&version=3&autoplay=1',
			width: 425,
			height: 356,
			allowScriptAccess: 'always',
		}
	);
}

//volgende nummer TODO
function playnext(vidId, image) {	
	$('#nieuwnummerbalk').attr('src',image);
}

//event listners voor YTplayerState zodat showmyvideo weet of een een play of playnext knop geplaatst moet worden. 
function onYouTubePlayerReady(playerId) {
	ytplayer = document.getElementById("ytplayer");
  	ytplayer.addEventListener("onStateChange", "onytplayerStateChange");
}
/*
*    -1 (unstarted)
*    0 (ended)
*    1 (playing)
*    2 (paused)
*    3 (buffering)
*    5 (video cued)
*
*/
function onytplayerStateChange(newState) {
	YTplayerState = newState;
}