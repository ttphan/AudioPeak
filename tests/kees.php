<?php 
require_once '../php/Track.php';
require_once '../php/TrackList.php';
require_once '../php/Tag.php';
require_once '../php/TagList.php';

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('error_reporting', E_ERROR | E_WARNING | E_PARSE);

$query = $_GET['search'];

$res = new TrackList();
$res->search($query);

$resArray = array();

foreach($res as $track) {
	$array = array(
		'artist_php' => $track->getArtist(),
		'trackName_php'=> $track->getName(),
		'trackImage_php'=> $track->getImage()
	);       	
	array_push($resArray, $array);
}

echo json_encode($resArray);

?>
