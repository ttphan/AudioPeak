// JavaScript Document 
$(document).ready(function() {
	$('#searchAll').keyup(function(e) {
		// Enter key
		if(e.keyCode == 13) {
			var search = $("#searchAll").val();
			searchAll(search);
		}
	});  

});

var YTplayerState = 0;

function searchAll(search) {
	//loader gif zolang ajax bezig is.
	$('#ResultsDiv').html('<img src="images/ajax-loader.gif" width="200" height="157" alt="ajax loader gif">');
	
	//zoek gebruik makende van ajax.php welke een json object echo't.
	$.ajax({
		type: "GET",
		url: "php/ajax.php",
		data: {search : search},
		cache: false,
		dataType: 'json',
		success: function(json) {
		  showResults(json);
		},
    	error: function (xhr, ajaxOptions, thrownError) {
			//TODO goed afhandelen.
			$('#ResultsDiv').html('Error: Mogelijk geen resultaten gevonden in Last.fm');
      	}
	});
}

function showResults(json){
	//maak resulsdiv leeg
	$('#ResultsDiv').html('');
		
	//haal vars uit json opject plaats deze in de resultsdiv met een span id van i. i wordt doorgegeven aan seachYT om daar de youtube play link te plaatsen
	//ook de image var uit deze json wordt doorgegevn zodat showButton deze ook kan plaatsen
	for (var i=0;i<json.length;i++) { 
		var artist = json[i].artist_php;
		var trackName = json[i].trackName_php;
		var image = json[i].trackImage_php;
		$('#ResultsDiv').append('<span id=\"' + i + '\" ></span> ' + artist + ' - ' + trackName + '<br>' );
		var trackData = new Array();
			trackData[0] = json[i].topTags_php;	
		searchYT(artist, trackName, i, image, trackData)
	}	
}

function searchYT(artist, trackName, i, image, trackData){	
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
	//gebruik json om te zoeken en geef deze data door aan showButton met i en image.
	$.getJSON('https://gdata.youtube.com/feeds/api/videos?v=2' +
			'&alt=jsonc&q=' +
			searchStr + 
			'&format=5' +
			'&fmt=18' +
			'&max-results=1' +
			'&orderby=relevance' +
			'&key=AI39si7Blv0HpIGbcHtzjaS70mFR-XEcomtJFHQcKC1-4yEthFsx4AhkMFldBeE_5UyD9jEEFCPMt2jzxDLF3hPT1SRoi1La4Q', function(data) {
				showButton(data, i, image, artist, trackName, trackData);
			});				
}

// See https://developers.google.com/youtube/2.0/developers_guide_jsonc#Understanding_JSONC for the feed information
function showButton(transport, j, image, artist, trackName, trackData) {
	var entries	= transport.data.items || [];
	
	if (entries[0] === undefined){
		var button = '<a href=\"#\">NoYT</a>';
		$('#'+j).append(' ' + button);
	}
	else{
		//haal de id van de video uit de transport data.
		var id = entries[0].id;
		
		//plaats een play button als de ytplayer klaar is met afspelen	
		if (YTplayerState == 0){
			var button = '<a href = \"javascript:createVideo(\''+ id +'\',\''+ image +'\',\''+ artist +'\',\''+ trackName +'\',\''+ trackData + '\')\"><img src="images/playbutton.png" width="15" height="15" alt="play"></a>';
			$('#'+j).append(' ' + button);
		}
		//plaats een playnext button als e ytplayer nog aan het afspelen is
		else {
			var button = '<a href = \"javascript:playnext(\''+ id +'\',\''+ image +'\',\''+ artist +'\',\''+ trackName +'\',\''+ trackData + '\')\">playNEXT</a>';
			$('#'+j).append(' ' + button);
		}
	}
}

//laat de video zien als er op lay wordt gedrukt, en plaats de image.
//TODO eartist, trackName info en extra info laten zienvvvvv
function createVideo(vidId, image, artist, trackName, trackData) {
	if(image == ''){
		//zoek op Plasticman Cha Vocal die heeft geen afbeelding
		$('#album').attr('src','images/albumgeen.jpg');
		$('#huidigenummerbalk').attr('src','images/albumgeen.jpg');		
	}
	else{
		$('#album').attr('src',image);
		$('#huidigenummerbalk').attr('src',image);
	}
	$('#artist').html('<a href = \"javascript:searchAll(\'' + artist + '\')\">' + artist + '</a>');
	$('#trackName').html(trackName);
		
	$('#playVideoDiv').flash({	
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
	if(image == ''){
		$('#nieuwnummerbalk').attr('src','images/albumgeen.jpg');		
	}
	else{
		$('#nieuwnummerbalk').attr('src',image);
	}
}

//event listners voor YTplayerState zodat showButton weet of een een play of playnext knop geplaatst moet worden. 
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