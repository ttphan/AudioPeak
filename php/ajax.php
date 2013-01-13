<?php
require_once 'Track.php';
require_once 'TrackList.php';
require_once 'Tag.php';
require_once 'TagList.php';
require_once 'DetailedTrack.php';
require_once 'FillerList.php';

/*  getFiller()
if(isset($_POST['getFillerStart']) && isset($_POST['getFillerEnd']))
{
	
	$start = new DetailedTrack($_POST['getFillerStart'][0], $_POST['getFillerStart'][1]);
	$end = new DetailedTrack($_POST['getFillerEnd'][0], $_POST['getFillerEnd'][1]);
	$fList = new FillerList($start, $end);

	$list = $fList->getFiller();
	$res = array();
	
	foreach($list as $track) {
		$artist = $track->getArtist();	
		$res[] = array(
					'artist' => $artist['name'], 
					'title' => $track->getName(),
					'wiki' => $track->getWiki(),
					'album' => $track->getAlbum(),
					'image' => $track->getImage(),
					'topTags' => $track->getTopTags()
				);
	}
	echo json_encode($res);
}
*/
// search()
if(isset($_GET['search'])) 
{
	$query = $_GET['search'];
	$res = new TrackList();
	$res->search($query);
	
	$resArray = array();
	
	foreach($res as $track) {
		$array = array(
				'artist_php' => $track->getArtist(),
				'trackName_php'=> $track->getName(),
				'trackImage_php'=> $track->getImage(),
		);
		array_push($resArray, $array);
	}
	
	echo json_encode($resArray);
}

/**
 * getInfo() - returns json encoded array, the array is shown below:
 * Array
	(
    [album] => <album title>
    [wiki] => Array
        (
            [published] => <date>
            [summary] => <summary about track>
            [content] => <detailed content about track>
        )

    [topTags] => Array
        (
            [0] => Array
                (
                    [name] => <tag>
                    [url] => <tag url>
                )
            [1] => Array
                (
                    [name] => <tag>
                    [url] => <tag url>
                )
            .
            .
            .
        )

	)
 */
if(isset($_GET['getInfo']))
{
	$track = new DetailedTrack($_GET['getInfo'][0], ($_GET['getInfo'][1]));
	$albumArr = $track->getAlbum();
	$res = array(
			'album' => $albumArr['title'],
			'wiki' => $track->getWiki(),
			'topTags' => $track->getTopTags()
	);
	echo json_encode($res);
	
}

if(isset($_GET['getFillerEndArtist']))
{
	$db = new MySQL();
	$start = new DetailedTrack($_GET['getFillerStartArtist'], $_GET['getFillerStartTrack']);
	$end = new DetailedTrack($_GET['getFillerEndArtist'], $_GET['getFillerEndTrack']);
	
	$fList = new FillerList($start, $end);

	for ($i = 1; $i <= 5; $i++) {
    	$list = $fList->getFiller();
		if(!is_null($list)){

			break;
		}
	}
	if(!is_null($list)){
		foreach($list as $tid) {
			$trackMeta = $db->getSong($tid);
			$track = new DetailedTrack($trackMeta['artist_name'], $trackMeta['title']);
			$artist = $track->getArtist();
			$res = array(
						'artist' => $artist['name'], 
						'title' => $trackMeta['title'],
						'image' => $track->getImage(),
					);
			break;
		}
	}
	else{
		echo json_encode('geen similar tracks gevonden, probeer een ander nummer ');
	}
	echo json_encode($res);
}

?>