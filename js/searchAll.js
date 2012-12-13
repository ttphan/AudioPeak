// JavaScript Document 
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

var playList = new Array();
playList[0] = "eeste";
var current = 0;
var playing = 0;
var links = 0; //0 als de links op playnext staan en 1 als de links op createvideo staan

//FIRST SEARCH STUFF
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

function createExtraInfo(name,artist) {
	$('#wikiSum').html('<img src="images/ajax-loader.gif" width="200" height="157" alt="ajax loader gif">');
	
	var track = [artist, name];
	
	$.ajax({
		type: "GET",
		url: "php/ajax.php",
		data: {getInfo : track},
		cache: false,
		dataType: 'json',
		success: function(json) {
			showExtraInfo(json);
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
	filler = new track('Smells Like Teen Spirit','Nirvana',"http:\/\/userserve-ak.last.fm\/serve\/126\/83456717.png")
	filler.addYTid();
	playList.push(filler);
		
	playList.push(endTrack);
}

//event listners voor YTplayerState zodat showButton weet of een een play of playnext knop geplaatst moet worden. 
function onYouTubePlayerReady(playerId) {
	ytplayer = document.getElementById("ytplayer");
  	ytplayer.addEventListener("onStateChange", "onytplayerStateChange");
}
/*
*    -1 (unstarted)
*    0 (ended)
*    1 (Playing)
*    2 (paused)
*    3 (buffering)
*    5 (video cued)
*
*/
function onytplayerStateChange(newState) {
	//als hij aan het afspelen is
	if(newState == 1){
		playing = 1;
		
		//als de links op createvideo staan maak er playnext van.
		if(links == 1){
			for (var i=0;i<numberResults;i++) { 					
				var buttonhtml = $('#'+i).html();
				split = buttonhtml.split("'");
				
				var id = split[1];
				myTrack = new track(split[3],split[5],split[7]);
				
				var button = '<a href = \"javascript:playNext(\''+ id +'\',\''+ myTrack.MytoString() + '\')\">'+ playNextButton + '</a>';
				$('#'+i).html(button);
			}
			links = 0;
		}		
	}
	//als hij niet aan het afspelen is.
	else {
		playing = 0;
		
		//als de playlist een volgende heeft en newstate is ended ga die dan afspelen
		if(newState == 0 && playList[current+1] != undefined){
			console.log('IK BEN HIEER' + playList[current+1] );
				current++;
				createVideo(playList[current].id, playList[current].name,playList[current].artist,playList[current].image);
				
				playList.splice(0, 1);
				current--;
				console.log("PLAYLIST: " + playList);
				console.log("CURRENT: " + current);
				console.log("NEXT: " + playList[current]);
		}
		//als de links op playnext staan maak er dan createvideo van.
		else if(links == 0) {
			//als er geen nieuwe in de playlist is of
			for (var i=0;i<numberResults;i++) { 					
				var buttonhtml = $('#'+i).html();
				split = buttonhtml.split("'");
				
				var id = split[1];
				myTrack = new track(split[3],split[5],split[7]);
				
				var button = '<a href=\"javascript:createVideo(\''+ id +'\',\''+ myTrack.MytoString() + '\')\">'+ playButton + '</a>';
				$('#'+i).html(button);
			}
			links = 1;
		}
		//als er niks volgt in de playlist hak dan de eerste er af en doe lekker niks
		else if(playList[current+1] == undefined){
			playList.splice(0, 1);
			current = 0;			
		}

	}
	console.log("newState: " + newState + " playing: "+ playing + " current: " + current + " playList: " + playList + " playList[current]: " + playList[current])
	
/*	YTplayerState = newState;
	console.log('newstate=' + newState + Playing );
	
	if(newState == 0 && Playing == 0){
		for (var i=0;i<numberResults;i++) { 					
			var buttonhtml = $('#'+i).html();
			split = buttonhtml.split("'");
			
			var id = split[1];
			myTrack = new track(split[3],split[5],split[7]);
			
			var button = '<a href=\"javascript:createVideo(\''+ id +'\',\''+ myTrack + '\')\">'+ playButton + '</a>';
			$('#'+i).html(button);
		}
		Playing = 1;
		
		//TODO TODO
		
		if(playList[current+1] != ''){
				console.log("PLAYLIST: " + playList);
				current++;
				console.log("CURRENT: " + current);
				console.log("NEXT: " + playList[current]);
				createVideo(playList[current].id, playList[current].name,playList[current].artist,playList[current].image);
		}
		

//		if(endTrack != ''){
////			if(filler != ''){
////				createVideo(endTrack.id, filler.name,filler.artist,filler.image);
////				//maak next track leeg en vervang nextimage ed. maak de functies hiervoor herbruikbaar.
////				filler = '';
////				$('#filler1').attr('src','images/emty.jpg');
////				createExtraInfo(filler.name,filler.artist);
////				filler = '';
////			}
//			if(playList != ''){
//				console.log(playList);
//				//fillerList[0]
//				createVideo(endTrack.id, filler.name,filler.artist,filler.image);
//				//maak next track leeg en vervang nextimage ed. maak de functies hiervoor herbruikbaar.
//				filler = '';
//				$('#filler1').attr('src','images/emty.jpg');
//				createExtraInfo(filler.name,filler.artist);
//				filler = '';
//			}
//			else {
//				createVideo(endTrack.id, endTrack.name,endTrack.artist,endTrack.image);
//				//maak next track leeg en vervang nextimage ed. maak de functies hiervoor herbruikbaar.
//				endTrack = '';
//				$('#nieuwnummerbalk').attr('src','images/albumnext.jpg');
//				createExtraInfo(endTrack.name,endTrack.artist);
//				endTrack = '';
//			}
//		}		
	}
	else if(newState == 1 && Playing == 1){
		for (var i=0;i<numberResults;i++) { 					
			var buttonhtml = $('#'+i).html();
			split = buttonhtml.split("'");
			
			var id = split[1];
			myTrack = new track(split[3],split[5],split[7]);
			
			var button = '<a href = \"javascript:playNext(\''+ id +'\',\''+ myTrack + '\')\">'+ playNextButton + '</a>';
			$('#'+i).html(button);
		}
		Playing = 0;
	}*/	
}