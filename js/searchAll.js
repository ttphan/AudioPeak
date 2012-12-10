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
		url: "php/ajax.php",
		data: {search : search},
		cache: false,
		dataType: 'json',
		success: function(json) {
			showSearchResults(json);
		},
    	error: function (xhr, ajaxOptions, thrownError) {
			//TODO goed afhandelen.
			$('#ResultsDiv').html('Error: Waarschijnlijk iets met php');
      	}
	});
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


function createExtraInfo(name,artist) {
	
		var track = [artist, name];
		console.log(track);
	
//	$.ajax({
//		type: "GET",
//		url: "php/ajax.php",
//		data: {getInfo : track},
////		dataType: 'json',
//		succes: function(result) {
//			console.log('deze shit werkt');
//			//showExtraInfo(result);
//		}
//	});
	
	$.ajax({
		type: "GET",
//		url: "tests/seachAll_ajaxjson.php",
//		data: "search="+search,
		url: "php/ajax.php",
		data: {getInfo : track},
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