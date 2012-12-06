<?php
require_once 'Track.php';
require_once 'TrackList.php';
require_once 'Tag.php';
require_once 'TagList.php';
require_once 'DetailedTrack.php';

//  getFiller()
if(isset($_POST['getFillerStart']) && isset($_POST['getFillerEnd']))
{
	
	$start = new DetailedTrack($_POST['getFillerStart'][0], $_POST['getFillerStart'][1]);
	$end = new DetailedTrack($_POST['getFillerEnd'][0], $_POST['getFillerEnd'][1]);
	
	echo json_encode(array(	"Start" => array(
										"Artist" => $start->getArtist(), 
										"Track" => $start->getName(),
										"Wiki" => $start->getWiki()
										), 
							"End" => array(
										"Artist" => $end->getArtist(), 
										"End" =>$end->getName(),
										"Wiki" => $end->getWiki()
									)
					)
		);
}

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
	json_encode($res);
	
}

?>