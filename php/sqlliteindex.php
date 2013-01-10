<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('error_reporting', E_ERROR | E_WARNING | E_PARSE);
require_once 'Track.php';
require_once 'TrackList.php';
require_once 'Tag.php';
require_once 'TagList.php';
require_once 'DetailedTrack.php';
require_once 'FillerList.php';
require_once 'Database.php';
?>
<!doctype html>

<html>

  <head>
    <title>AudioPeak</title>
    <meta charset="utf-8" />
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>	
    <script src="../js/ajax.js"></script>
  </head>

  <body>
	<h1>Fillerlist Test</h1>
	<pre>
	<?php
	$start = new DetailedTrack("Nirvana", "Lithium");
	$end = new DetailedTrack("Rihanna", "SOS");
	$flist = new FillerList($start, $end);
	
	for ($i = 1; $i <= 5; $i++) {
    	$res = $flist->getFiller();
		if(!is_null($res)){
			var_dump($res);
			break;
		}
	}
	?>
	</pre>
  </body>
</html>
