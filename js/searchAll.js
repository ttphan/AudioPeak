// JavaScript Document 
$(document).ready(function() {
	$('#searchAll').keyup(function(e) {
		// Enter key
		if(e.keyCode == 13) {
			var search = $("#searchAll").val();
			searchLastfm(search);
		}
	});  
});

var YTplayerState = 0;
var playing = 1;
var playButton = '<img src="images/playbutton.png" width="15" height="15" alt="play">'
var playNextButton = '<img src="images/playnextbutton.png" width="15" height="15" alt="playNext">'
var playList = new Array();

function track(name,artist,image){
	this.name=name;
	this.artist=artist;
	this.image=image;
}

//dit netter doen hoe??
track.prototype.addID = function addID(id){
	this.id=id;
}

track.prototype.toString = function trackToString() {
  var res = this.name + "','" + this.artist + "','" + this.image;
  return res;
}

function searchLastfm(search) {
	//loader gif zolang ajax bezig is.
	$('#ResultsDiv').html('<img src="images/ajax-loader.gif" width="200" height="157" alt="ajax loader gif">');
	$("#searchAll").val(search);
	
	//zoek gebruik makende van seachall_ajaxjson.php welke een json object echo't.
	$.ajax({
		type: "GET",
//		url: "tests/seachAll_ajaxjson.php",
//		data: "search="+search,
		url: "php/ajax.php",
		data: {search : search},
		cache: false,
		dataType: 'json',
		success: function(json) {
			showSearchResults(json);
		},
    	error: function (xhr, ajaxOptions, thrownError) {
			//TODO goed afhandelen.
			$('#ResultsDiv').html('Error: Mogelijk geen resultaten gevonden in Last.fm');
      	}
	});
}

function showSearchResults(json){
	//maak resulsdiv leeg
	$('#ResultsDiv').html('');
		
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

function searchYT(myTrack, i){	
	//zoek string
	var searchStr = myTrack.artist + ' ' + myTrack.name;
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
				showButtons(data, i, myTrack);
			});				
}

// See https://developers.google.com/youtube/2.0/developers_guide_jsonc#Understanding_JSONC for the feed information
function showButtons(transport, j, myTrack) {
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
			var button = '<a href=\"javascript:createVideo(\''+ id +'\',\''+ myTrack + '\')\">'+ playButton + '</a>';
			$('#'+j).html(button);
		}
		//plaats een playnext button als e ytplayer nog aan het afspelen is
		else {
			var button = '<a href = \"javascript:playNext(\''+ id +'\',\''+ myTrack + '\')\">'+ playNextButton + '</a>';
			$('#'+j).html(button);
		}
	}
}

//laat de video zien als er op lay wordt gedrukt, en plaats de image.
//TODO eartist, trackName info en extra info laten zienvvvvv
function createVideo(vidId, name,artist,image) {
	currentTrack = new track(name,artist,image);

	if(currentTrack.image == ''){
		//zoek op Plasticman Cha Vocal die heeft geen afbeelding
		$('#album').attr('src','images/albumgeen.jpg');
		$('#huidigenummerbalk').attr('src','images/albumgeen.jpg');		
	}
	else{
		$('#album').attr('src',currentTrack.image);
		$('#huidigenummerbalk').attr('src',currentTrack.image);
	}
	$('#artist').html('<a href = \"javascript:searchLastfm(\'' + currentTrack.artist + '\')\">' + currentTrack.artist + '</a>');
	$('#trackName').html(currentTrack.name);
	createExtraInfo(name,artist);
		
	$('#playVideoDiv').flash({	
			id: 'ytplayer',
			swf: 'http://www.youtube.com/v/' + vidId + '?enablejsapi=1&playerapiid=ytplayer&version=3&autoplay=1',
			width: 425,
			height: 356,
			allowScriptAccess: 'always',
		}
	);	
	
	//maak van de playbuttons play next buttons er is nu tenslotte iets aan het spelen.	
	for (var i=0;i<numberResults;i++) { 					
		var buttonhtml = $('#'+i).html();
		split = buttonhtml.split("'");
		
		var id = split[1];
		myTrack = new track(split[3],split[5],split[7]);
		
		var button = '<a href = \"javascript:playNext(\''+ id +'\',\''+ myTrack + '\')\">'+ playNextButton + '</a>';
		$('#'+i).html(button);
	}
	playing = 0;
	
	//TODO doe ajax request voor extra info en plaats deze.
}
function createExtraInfo(name,artist) {
	var getInfo = [artist,name];
	$.ajax({
		type: "GET",
		url: "php/ajax.php",
		data: {getInfo : getInfo},
		cache: false,
		dataType: 'json',
		success: function(json) {
			showExtraInfo(json);
		},
    	error: function (xhr, ajaxOptions, thrownError) {
			//TODO goed afhandelen.
			$('#ResultsDiv').html('Error: Mogelijk geen resultaten gevonden in Last.fm');
      	}
	});
}

function showExtraInfo(json){
	//maak resulsdiv leeg
	$('#wikiSum').html('');
	console.log(json);
	
	$('#wikiSum').html(json.wikiding);	
}


//volgende nummer
function playNext(vidId, name,artist,image) {
	endTrack = new track(name,artist,image,album);	
	endTrack.addID(vidId);

		
	if(endTrack.image == ''){
		$('#nieuwnummerbalk').attr('src','images/albumgeen.jpg');		
	}
	else{
		$('#nieuwnummerbalk').attr('src',endTrack.image);
	}
	
	//TODO 
	console.log('ajax request met currentTRack,endTrack. TODO' + currentTrack + endTrack);
		//make track object van filler + vidid
	//playList.push(filler);
	//playList.push(currentTrack);

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
	console.log('newstate=' + newState + playing );
	
	if(newState == 0 && playing == 0){
		for (var i=0;i<numberResults;i++) { 					
			var buttonhtml = $('#'+i).html();
			split = buttonhtml.split("'");
			
			var id = split[1];
			myTrack = new track(split[3],split[5],split[7]);
			
			var button = '<a href=\"javascript:createVideo(\''+ id +'\',\''+ myTrack + '\')\">'+ playButton + '</a>';
			$('#'+i).html(button);
		}
		playing = 1;
		
		//TODO TODO
		if(endTrack != ''){
			createVideo(endTrack.id, endTrack.name,endTrack.artist,endTrack.image);
			//maak next track leeg en vervang nextimage ed. maak de functies hiervoor herbruikbaar.
			endTrack = '';
			$('#nieuwnummerbalk').attr('src','images/albumnext.jpg');
		}		
	}
	else if(newState == 1 && playing == 1){
		for (var i=0;i<numberResults;i++) { 					
			var buttonhtml = $('#'+i).html();
			split = buttonhtml.split("'");
			
			var id = split[1];
			myTrack = new track(split[3],split[5],split[7]);
			
			var button = '<a href = \"javascript:playNext(\''+ id +'\',\''+ myTrack + '\')\">'+ playNextButton + '</a>';
			$('#'+i).html(button);
		}
		playing = 0;
	}	
}