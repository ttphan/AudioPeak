<?php 
require_once 'Track.php';
require_once 'TrackList.php';
require_once 'Tag.php';
require_once 'TagList.php';

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('error_reporting', E_ERROR | E_WARNING | E_PARSE);

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
// werkt analoog voor track search

$res = new TagList();
$res->search($query);
foreach($res as $tag)
{
	echo $tag->getName() . " - " . $tag->getCount() . " - " . $tag->getScaledCount() * 100 . "%\n";
}
?>
	</pre>
  </body>

</html>