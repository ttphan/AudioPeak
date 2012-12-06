<?php
require_once 'Track.php';
require_once 'TrackList.php';
require_once 'Tag.php';
require_once 'TagList.php';
require_once 'DetailedTrack.php';

//  getFiller()
if(isset($_REQUEST['start']) && isset($_REQUEST['end']))
{
	
	$start = new DetailedTrack($_REQUEST['start'][0], $_REQUEST['start'][1]);
	$end = new DetailedTrack($_REQUEST['end'][0], $_REQUEST['end'][1]);
	
	echo json_encode(array(	"Start" => array(
										"Artist" => $start->getArtist(), 
										"Track" => $start->getName(),
										), 
							"End" => array(
										"Artist" => $end->getArtist(), 
										"End" =>$end->getName(),
									)
					)
		);
}

// search()
if(isset($_GET['query'])) 
{
	$query = $_GET['query'];
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
?>