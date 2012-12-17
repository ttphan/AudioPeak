// JavaScript Document htmlDT2.js
/*
createVideo(vidId, name,artist,image)
	createExtraInfo(name,artist)
		showExtraInfo(json)
		
showPlayList()
*/
var playNextButton = '<img src="images/playnextbutton.png" width="15" height="15" alt="playNext">'

function createVideo() {
	currentTrack = playList[0];

	if(currentTrack.image == ''){
		//zoek op Plasticman Cha Vocal die heeft geen afbeelding
		$('#album').attr('src','images/albumgeen.jpg');	
	}
	else{
		$('#album').attr('src',currentTrack.image);
	}
	$('#artist').html('<a href = \"javascript:searchLastfm(\'' + currentTrack.artist + '\')\">' + currentTrack.artist + '</a>');
	$('#trackName').html(currentTrack.name);
	//create extra info voor huidge nummer
	createExtraInfo(currentTrack.name,currentTrack.artist);
		
	$('#playVideoDiv').flash({	
			id: 'ytplayer',
			swf: 'http://www.youtube.com/v/' + currentTrack.id + '?enablejsapi=1&playerapiid=ytplayer&version=3&autoplay=1',
			width: 425,
			height: 356,
			allowScriptAccess: 'always',
		}
	);	
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

function showExtraInfo(json){
	//maak resulsdiv leeg
	$('#palbum').html(json['album']);
	$('#wikiSum').html(json['wiki']['summary']);	
}


function showPlayList(){
	$('#balkflow').html('');
	for (var i=0;i<playList.length;i++) { 	
		if(playList[i].image == ''){
			$('#balkflow').append('<img src=\"' + 'images/albumgeen.jpg' + '\" alt="alubmimg" id=\"' + i + '\" />');	
		}
		else{
			$('#balkflow').append('<img src=\"' + playList[i].image + '\" alt="alubmimg" id=\"' + i + '\" />');	
		}
	}
	$('#balkflow').width(114*playList.length);
}