<?php 
require_once '../php/Track.php';
require_once '../php/TrackList.php';
require_once '../php/Tag.php';
require_once '../php/TagList.php';

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('error_reporting', E_ERROR | E_WARNING | E_PARSE);

//$query = 'rock';
$query = $_GET["search"];
?>
<!doctype html>

<html>

  <head>
    <title>AudioPeak</title>
    <meta charset="utf-8" />
  </head>

  <body>
	<h1>Search results for: <?php echo $query ?></h1>
	<pre>
<?php
// werkt analoog voor track search

$res = new TrackList();
$res->search($query);
foreach($res as $track)
{
	echo $track->getArtist() . " - " . $track->getName() . " - " .  $track->getImage() . "\n";
}
?>
	</pre>
  </body>

</html>