<?php
require_once '../php/Track.php';
require_once '../php/TrackList.php';
require_once '../php/Tag.php';
require_once '../php/TagList.php';

//  getFiller()
if(isset($_REQUEST['start']) && isset($_REQUEST['end']))
{
	$start = $_REQUEST['start'];
	$end = $_REQUEST['end'];
	echo json_encode(array("Start" => array("Artist" => $start[0], "Track" => $start[1]), "End" => array("Artist" => $end[0], "End" =>$end[1])));
}

// search()
if(isset($_GET['query'])) 
{
	$query = $_GET['query'];
	$res = new TrackList();
	$res->search($query);
	
	$resArray = array();
	
	//echo $res;
	
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