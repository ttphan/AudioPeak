$(document).ready(function() {
	$('#inputSearchStrId').keyup(function(e) {
		// Enter key
		if(e.keyCode == 13) {
			searchClicked();
		}
	}); 
});

function searchClicked()
{	
	// Create an <ul> where the results are appended to
	var list = $(document.createElement('ul')).attr('id', 'vidList');
	
	var searchStr = $("#inputSearchStrId").val();
	/*	
	 *	Requests a JSON-C feed from the GData.
	 *
	 *	Link paramters:
	 *	&format=5: Show only embeddable videos
	 *	&max-results=n: Show a maximum of n results
	 *	&orderby=relevance: Order the results by relevance
	 *	&key=[KEY]: Google Developer Key, to avoid the public quota, compared to anonymous requests
	 *
	 */
	$.getJSON('https://gdata.youtube.com/feeds/api/videos?v=2' +
			'&alt=jsonc&q=' +
			searchStr + 
			'&format=5' +
			'&max-results=1' +
			'&orderby=relevance' +
			'&key=AI39si7Blv0HpIGbcHtzjaS70mFR-XEcomtJFHQcKC1-4yEthFsx4AhkMFldBeE_5UyD9jEEFCPMt2jzxDLF3hPT1SRoi1La4Q', showMyVideos)
	.complete($('#videoResultsDiv').html(list));				
}

function showMyVideos(transport) {
	var entries	= transport.data.items || [];
	$.each(entries, function(i, entry) {
		var title = entry.title;
		var playCount = entry.viewCount + ' views';
		var url = entry.player.default;
		var id = entry.id;
		var link = '<a href = \"' + url + '\">link</a>';
		var play = '<a href = \"javascript:createVideo(\''+ id + '\')\">play</a>';
		$('#vidList').append('<li>' + title + ', ' + playCount + ', ' + link + ', ' + play + '</li>');
	});
}

function createVideo(vidId) {
	
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

function onYouTubePlayerReady(playerId) {
	ytplayer = document.getElementById("ytplayer");
  	ytplayer.addEventListener("onStateChange", "onytplayerStateChange");
	alert('Hey, alles is geladen!');
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
   console.log("Player's new state: " + newState);
}

