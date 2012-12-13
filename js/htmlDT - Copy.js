// JavaScript Document for HTML document traversing

var YTplayerState = 0;
var Playing = 1;
var playButton = '<img src="images/playbutton.png" width="15" height="15" alt="play">'
var playNextButton = '<img src="images/playnextbutton.png" width="15" height="15" alt="playNext">'

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

function showButtons(transport, j, myTrack) {
	var entries	= transport.data.items || [];
	
	if (entries[0] === undefined){
		var button = '<a href=\"#\">NoYT</a>';
		$('#'+j).append(button);
	}
	else{
		//haal de id van de video uit de transport data.
		var id = entries[0].id;
		
		//plaats een play button als de ytplayer klaar is met afspelen	
		if (YTplayerState == 0 || YTplayerState == 2){
			var button = '<a href=\"javascript:createVideo(\''+ id +'\',\''+ myTrack.MytoString() + '\')\">'+ playButton + '</a>';
			$('#'+j).html(button);
		}
		//plaats een playnext button als e ytplayer nog aan het afspelen is
		else {
			var button = '<a href = \"javascript:playNext(\''+ id +'\',\''+ myTrack.MytoString() + '\')\">'+ playNextButton + '</a>';
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
	//create extra info voor huidge nummer
	createExtraInfo(name,artist);
		
	$('#playVideoDiv').flash({	
			id: 'ytplayer',
			swf: 'http://www.youtube.com/v/' + vidId + '?enablejsapi=1&playerapiid=ytplayer&version=3&autoplay=1',
			width: 425,
			height: 356,
			allowScriptAccess: 'always',
		}
	);	
	playList.push(currentTrack);
	
	
	//maak van de playbuttons play next buttons er is nu tenslotte iets aan het spelen.	
	for (var i=0;i<numberResults;i++) { 					
		var buttonhtml = $('#'+i).html();
		split = buttonhtml.split("'");
		
		var id = split[1];
		myTrack = new track(split[3],split[5],split[7]);
		
		var button = '<a href = \"javascript:playNext(\''+ id +'\',\''+ myTrack.MytoString() + '\')\">'+ playNextButton + '</a>';
		$('#'+i).html(button);
	}
	Playing = 0;
}

function showExtraInfo(json){
	//maak resulsdiv leeg
	$('#album').html(json['album']);
	$('#wikiSum').html(json['wiki']['summary']);	
}