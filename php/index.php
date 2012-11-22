<?php 
//require '../tests/Helper.php';
require_once 'Track.php';
require_once 'TrackList.php';
require_once 'Tag.php';
require_once 'TagList.php';
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$query = 'rock';
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
/* $Helper = new Helper;
print_r($Helper->searchTrack($query)); */

// werkt hetzelfde voor track search

$res = new TagList();
$res->search($query);
foreach($res as $tag)
{
	echo $tag->getName() . " - " . $tag->getCount() . "\n";
}
?>
	</pre>
  </body>

</html>