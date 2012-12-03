<?php 
//require_once 'Track.php';
require_once 'DetailedTrack.php';
//require_once 'TrackList.php';
//require_once 'Tag.php';
//require_once 'TagList.php';
//require_once 'FillerList.php';

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('error_reporting', E_ERROR | E_WARNING | E_PARSE);

$query = 'black metal';
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

print_r(new DetailedTrack("Nirvana","Lithium"));
?>
	</pre>
  </body>

</html>