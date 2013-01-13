// JavaScript Document playList.js
var playList = new Array();

function playNext(vidId, name,artist,image) {		
	myTrack = new track(name,artist,image,album);	
	myTrack.addID(vidId);
	
	if(playList[0] == undefined){
		playList[0] = myTrack;
		createVideo();
	}
	else{
		$('#ResultsDiv').html('<img src="images/ajax-loader.gif" width="200" height="157" alt="ajax loader gif">');
		$.ajax({	
			type: "GET",
			url: "php/ajax.php",
			data: {getFillerStartTrack : playList[playList.length-1].name,
					getFillerStartArtist : playList[playList.length-1].artist,
					getFillerEndTrack : myTrack.name,
					getFillerEndArtist : myTrack.artist},
			cache: false,
			dataType: 'json',
			success: function(json) {
				getFillerJSON(json);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				//TODO goed afhandelen.
				console.log(thrownError);
				console.log(xhr);
				$('#ResultsDiv').html('Error: Waarschijnlijk iets met php of de verbinden. Probeer opnieuw.');
			}
		});		
	}
	showPlayList();
}


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
	if(newState == 0){
		if(playList[1] == undefined){
			playList.splice(0, 1);
			console.log('GEEN NIEUWE VIDEO' + playList );
		}
		else{
			playList.splice(0, 1);
			createVideo();
		}
	showPlayList();
	}
}

function getFillerJSON(json){
	filler = new track(json.title,
						json.artist,
						json.image
						);
	filler.addYTid();	
	playList.push(filler);
	playList.push(myTrack);
	showPlayList();
	$('#ResultsDiv').html('Filler toegevoegd!');
}

function next() {
	if(playList[1] == undefined){
		console.log('GEEN NIEUWE VIDEO' + playList );
	}
	else{
		playList.splice(0, 1);
		createVideo();
	}
	showPlayList();
}
