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
		filler = getFiller(playList[playList.length-1],myTrack);		
		playList.push(filler);
		playList.push(myTrack);
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


function getFiller(beginTrack,endTrack){
	console.log(beginTrack + endTrack);
	filler = new track('Smells Like Teen Spirit','Nirvana',"http:\/\/userserve-ak.last.fm\/serve\/126\/83456717.png");
	filler.addYTid();	
	return filler;
}