<?php 
require_once 'Track.php';
require_once 'TrackList.php';
require_once 'Tag.php';
require_once 'TagList.php';
require_once 'FillerList.php';

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('error_reporting', E_ERROR | E_WARNING | E_PARSE);

$query = 'hello';
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
//print_r($res);
$track1 = $res->get(0);
$track2 = $res->get(1);
$filler = new FillerList($track1, $track2);
//print_r($track1->getTags());
//print_r($track2->getTags());
echo "filler needed for: \"" . $track1->getArtist() . " - " . $track1->getName() . "\" <-> \"" .  $track2->getArtist() . " - " . $track2->getName() . "\" :  ";
print_r($filler->fillerNeeded());
 
?>
	</pre>
  </body>

</html>